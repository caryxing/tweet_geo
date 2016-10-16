@extends('layouts.app')

<!DOCTYPE HTML>
<html>
<head>
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script sync src="https://platform.twitter.com/widgets.js"></script>

    <style>
     .msg {
         color: #CCC;
     }
    </style>

    <script type="text/javascript">
     function reveal_tweet(tweet) {
         var id = tweet.getAttribute("tweetID");
         
         twttr.widgets.createTweet(
             id, tweet,
             {
                 conversation: 'none',
                 cards: 'visible',
                 theme: 'light'
             })

     }
     window.onload = (function(){
         tweets = document.getElementsByName("tweet");
         $.each(tweets, function( index, value ) {
             reveal_tweet(value)
         });
     });
    </script>


</head>

@section('content')
<body>

<div class="content">
    <div class="container">
        <div class="msg">{{$msg}}</div>
        <div>@if (!empty($tweets)) {{$tweets->render()}}@endif</div>
        @foreach ($tweets as $tweet)
            <div name="tweet" tweetID="{{$tweet['_source']['id']}}"></div>
        @endforeach
        <div>@if (!empty($tweets)) {{$tweets->render()}}@endif</div>
    </div>
</div>

</body>
</html>

@endsection
