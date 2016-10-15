<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Elasticsearch\ClientBuilder;

class TweetController extends Controller
{

    public function get_tweets(Request $request) {
        $hosts = [env("ES_HOST", '')];
        $client = ClientBuilder::create()->setHosts($hosts)->build();
        
        $lat = $request -> lat;
        $lon = $request -> lon;
        $radius = $request -> radius . "mi";

        $query = [
            'index' => 'twitter-*',
            'type' => 'tweet',
            "sort" => [
                'timestamp_ms:desc'
            ],
            "from" => 0,
            "size" => 25,
            'body' => [
                'query' => [

                    "bool" => [

                        "must" => [
                            "match_all" => []
                        ],
                        "filter" => [
                            "geo_distance" => [
                                "distance" => $radius,
                                "coordinates" => $lat . ', ' . $lon
                            ]
                        ]
                    ]
                ]
            ]
        ];

        try {
            $response = $client->search($query);
            $msg = "Total hits: ".$response["hits"]["total"];
            $tweets = $response["hits"]["hits"];
            return view("home", ["tweets"=>$tweets, 
                                 "msg"=>$msg, 
                                 "lat"=>$lat,
                                 "lon"=>$lon,
                                 "radius"=>$request->radius]);

        } catch (\Exception $e) {
            $msg = 'Error: '.$e->getMessage();
            return view("home", ["tweets" => [], "msg" => $msg]);
        }
        
        return view("home");
    }

    public function home() {
        return view("home",  ["tweets" => [], "msg" => "Input Latitude, Longitude, and Radius above."]);
    }
}
