<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Elasticsearch\ClientBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TweetController extends Controller
{

    public function get_tweets(Request $request) {
        $hosts = [env("ES_HOST", '')];
        $client = ClientBuilder::create()->setHosts($hosts)->build();
        
        $lat = $request -> lat;
        $lon = $request -> lon;
        $radius = $request -> radius;

        $query = [
            'index' => 'twitter-*',
            'type' => 'tweet',
            "sort" => [
                'timestamp_ms:desc'
            ],
            "from" => 0,
            "size" => 250,
            'body' => [
                'query' => [

                    "bool" => [

                        "must" => [
                            "match_all" => []
                        ],
                        "filter" => [
                            "geo_distance" => [
                                "distance" => $radius . "mi",
                                "coordinates" => $lat . ', ' . $lon
                            ]
                        ]
                    ]
                ]
            ]
        ];

        try {
            $response = $client->search($query);

        } catch (\Exception $e) {
            $msg = 'Error: '.$e->getMessage();
            return view("home", ["tweets" => [], "msg" => $msg]);
        }

        $showNum = min($response["hits"]["total"], 250);
        $msg = "Total hits: ".$response["hits"]["total"].". Showing " . $showNum .".";
        $tweets = $response["hits"]["hits"];

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = new Collection($tweets);
        $perPage = 15;
        $tweets = $collection->slice(($currentPage-1) * $perPage, $perPage)->all();
        $paginator = new LengthAwarePaginator($tweets, count($collection), $perPage);
        $paginator -> setPath($request->url()."?lat=$lat"."&lon=$lon"."&radius=$radius");


        return view("home", ["tweets"=>$paginator, 
                             "msg"=>$msg, 
                             "lat"=>$lat,
                             "lon"=>$lon,
                             "radius"=>$radius]);
    }


    public function home() {
        return view("home",  ["tweets" => [], "msg" => "Input Latitude, Longitude, and Radius above. e.g. Charlottsvile, VA: 38.031524, -78.510559"]);
    }
}
