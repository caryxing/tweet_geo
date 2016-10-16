# Tweet. Geo
Get tweets by geographical areas (coordinates).
You can optionally specify a filter by key words.


# Design
Utilize Twitter's Stream API to get tweets, and forward them to Elasticsearch Cloud.
Query Elastic Cloud upon requests from Web Portal.

## Technologies
*   ElasticSearch Cloud 2.4.1 (https://www.elastic.co/) is used as search engine and database.
*   Python 2.7 is used as programming language to handle Twitter Stream API and forward data to Elastic Cloud.
*   Laravel 5.3.10 is used as an MVC framework for Web Portal.
*   Apache/2.4.6 is used for Web (HTTP) Server. 
*   PHP 7.0.11 is used for backend scripting.
*   GIT is used for version control. 
*   Javascript is used as frontend programming language.
*   Bootstrap is used for web page Styling.

## Elastic Mapping
```json
        mappings = {
            "tweet": {
                "properties": {
                    "id": {
                        "type": "string"
                    },
                    "coordinates": {
                        "type": "geo_point"
                    },
                    "timestamp_ms": {
                        "type": "long"
                    },
                    "text": {
                        "type": "string"
                    }
                }
            }
        }
```

## Data Retiring
Save Tweets in the same hour as the same index, name them in a sequence, e.g. "twitter-0", "twitter-1".
Before forwarding a new tweet, check if it is in a new hour, if so, remove indices that are older than 24 hours, then create a new index.

## View Design
|    View Name                |    Description                                                           |
|-----------------------------|--------------------------------------------------------------------------|
|    home.blade.php           |    List of the requested tweets.                                         |


## Controller Design
|    Controller Name           |    Description                                                     |
|------------------------------|--------------------------------------------------------------------|
|    TweetController.php       |    Handle the Tweets search.                                       |




# Web UI Snapshot
![alt text](https://github.com/caryxing/tweet_geo/blob/master/doc/ui_snapshot.png)
