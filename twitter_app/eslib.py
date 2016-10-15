# elastic_search.py
# A module that handles elasticsearch connection

ES_CONFIG_FILE = "./env.cfg"

from elasticsearch import Elasticsearch, RequestsHttpConnection
import ConfigParser
import json


def json_pretty_print(json_obj):
    print json.dumps(json_obj, indent=4)


class ElasticSearchForTweets:
    def __init__(self):
        config = ConfigParser.ConfigParser()
        config.read(ES_CONFIG_FILE)

        endpoint = config.get('ELASTICSEARCH', 'endpoint')
        username = config.get('ELASTICSEARCH', 'username')
        password = config.get('ELASTICSEARCH', 'password')
        port = config.getint('ELASTICSEARCH', 'port')

        self.es = Elasticsearch([endpoint],
                           port=port,
                           http_auth=(username, password),
                           use_ssl=True,
                           connection_class=RequestsHttpConnection,
                           verify_certs=False)

    def add_tweet(self, tweet_body):
        #indices = self.es.indices.get('twitter')
        #json_pretty_print(indices)
        self.es.index(index="twitter", doc_type="tweet", body=tweet_body)

    def get_instance(self):
        return self.es
    
    def info(self):
        return self.es.info()


def est_debug():
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
                    "type": "string"
                }
            }
        }
    }
    

    es = ElasticSearchForTweets().get_instance()
    es.indices.delete("twitter")
    es.indices.create(index="twitter")
    es.indices.put_mapping(index="twitter", doc_type="tweet", body=json.dumps(mappings))
    json_pretty_print(es.indices.get("twitter"))


# TODO: remove
#est_debug()




