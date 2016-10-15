# elastic_search.py
# A module that handles elasticsearch connection

ES_CONFIG_FILE = "./env.cfg"

from elasticsearch import Elasticsearch, RequestsHttpConnection
import ConfigParser
import json
from datetime import datetime


INDEX_PREFIX = "twitter-"

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
        self.hour = None
        self.current_index = None
        self.del_old_create_new_index()

    def add_tweet(self, tweet_body):
        if datetime.today().hour != self.hour:
            del_old_create_new_index()
        self.es.index(index=self.current_index, doc_type="tweet", body=tweet_body)


    def del_old_create_new_index(self):
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
        indices = self.es.indices.get(INDEX_PREFIX + "*")
        indexSeqs = []
        for index in indices:
            indexSeqs.append(int(index.split('-')[1]))
        indexSeqs.sort(reverse=True)

        # remove indices that are older than 24 hours
        for i in xrange(24, len(indexSeqs)):
            self.es.indices.delete(index = INDEX_PREFIX + str(indexSeqs[i]))

        if len(indexSeqs) > 0:
            newSeq = indexSeqs[0] + 1
        else:
            newSeq = 0

        newIndex = INDEX_PREFIX + str(newSeq)
        self.es.indices.create(index = newIndex)
        self.es.indices.put_mapping(index = newIndex, doc_type = "tweet", body = json.dumps(mappings))
        print("New index %s is created."%(newIndex))

        self.hour = datetime.today().hour
        self.current_index = newIndex
        

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
    #es.indices.delete("twitter")
    #es.indices.create(index=INDEX_PREFIX+"2")
    #es.indices.put_mapping(index="twitter", doc_type="tweet", body=json.dumps(mappings))
    #json_pretty_print(es.indices.get("twitter*"))
    #json_pretty_print(es.search("twitter*", body={"query": {"match_all": {}}}))

    query = {
        "sort" : [
            {"timestamp_ms" : "desc"}
        ],
        "from" : 0, 
        "size" : 250,
        "query": {
            "bool" : {
                "must" : {
                    "match_all" : {}
                },

                "filter" : {
                    "geo_distance" : {
                        "distance" : "120km",
                        "coordinates" : "37,-122"
                    }
                }
            }
        }
    }

    #res = es.search(index = "twitter*", body = query)
    #print("Got %d Hits:" % res['hits']['total'])
    #for hit in res['hits']['hits']:
    #    print("%s" % hit["_source"])


# TODO: remove
#est_debug()




