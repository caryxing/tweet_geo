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
            self.del_old_create_new_index()
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
                        "type": "long"
                    },
                    "text": {
                        "type": "string"
                    }
                }
            }
        }

        try:
            indices = self.es.indices.get(INDEX_PREFIX + "*")
        except:
            indices = []
            
        index_seqs = []
        for index in indices:
            index_seqs.append(int(index.split('-')[1]))
        index_seqs.sort(reverse=True)

        # remove indices that are older than 24 hours
        for i in xrange(24, len(index_seqs)):
            self.es.indices.delete(index = INDEX_PREFIX + str(index_seqs[i]))

        if len(index_seqs) > 0:
            new_seq = index_seqs[0] + 1
        else:
            new_seq = 0

        new_index = INDEX_PREFIX + str(new_seq)
        self.es.indices.create(index = new_index)
        self.es.indices.put_mapping(index = new_index, doc_type = "tweet", body = json.dumps(mappings))
        print("New index %s is created."%(new_index))

        self.hour = datetime.today().hour
        self.current_index = new_index
        

    def get_instance(self):
        return self.es
    
    def info(self):
        return self.es.info()


def est_debug():
    es = ElasticSearchForTweets().get_instance()
    #es.indices.delete("twitter*")
    #es.indices.create(index=INDEX_PREFIX+"0")
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


