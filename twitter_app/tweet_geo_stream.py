# tweet_geo_stream.py
# Get tweets and add to Elasticsearch Cloud.

CONFIG_FILE = "./env.cfg"
LOCATIONS = [-180,-90,180,90]

import sys
import ConfigParser
from tweepy.streaming import StreamListener
from tweepy import OAuthHandler
from tweepy import Stream
import eslib
import json

class TweetListener(StreamListener):
    def __init__(self):
        StreamListener.__init__(self)
        self.es = eslib.ElasticSearchForTweets()

    def on_data(self, data):
        tweet = json.loads(data)
        coordinates = tweet.get("coordinates", None)

        if coordinates:
            tweet_to_add = {
                "id": tweet["id_str"], 
                "coordinates": coordinates["coordinates"],
                "timestamp_ms": tweet["timestamp_ms"]
            }
            self.es.add_tweet(json.dumps(tweet_to_add))
        return True

    def on_error(self, status):
        print("Error: " + str(status))

if __name__ == '__main__':
    tweet_handler = TweetListener()
    config = ConfigParser.ConfigParser()
    config.read(CONFIG_FILE)

    consumer_key = config.get('TWITTER_APP', 'consumer_key')
    consumer_secret = config.get('TWITTER_APP', 'consumer_secret')
    access_token = config.get('TWITTER_APP', 'access_token')
    access_token_secret = config.get('TWITTER_APP', 'access_token_secret')

    auth = OAuthHandler(consumer_key, consumer_secret)
    auth.set_access_token(access_token, access_token_secret)

    stream = Stream(auth, tweet_handler)
    stream.filter(locations = LOCATIONS)

