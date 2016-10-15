<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
     .coodinates {
         margin-top: 8px;
         margin-left: 10px;
         margin-bottom: 0px;
     }

     .radius {
         width: 20%;
     }
     
     .copywrite {
         margin-top: 20px;
         padding-top: 10px;
         background: #97b5d0;
     }

     .footer {
         position: absolute;
         bottom: 0;
         width: 100%;
     }
    </style>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>


</head>
<body>
    <nav class="navbar navbar-default navbar-static-top" style="margin-bottom: 0px;">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="/tweetgeo" style="color: #555;">
                    Tweet. Geo
                </a>
            </div>
      
            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <form method="GET" action="get_tweets">
                    {{ csrf_field() }}

                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    &nbsp;
                    <li class="coodinates"><input tyle="text" name="lat" class="form-control" 
                                                  placeholder="Latitude" pattern="[-.0-9]+" value="@if(!empty($lat)){{$lat}}@endif" required/></li>
                    <li class="coodinates"><input tyle="text" name="lon" class="form-control" 
                                                  placeholder="Longitude" pattern="[-.0-9]+" value="@if(!empty($lon)){{$lon}}@endif"  required/></li>
                    <li class="coodinates radius"><input tyle="text" name="radius" class="form-control" 
                                                         placeholder="Radius (miles)" pattern="[0-9]+" value="@if(!empty($radius)){{$radius}}@endif" required/></li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right" style="color: #999;">
                    <li><button class="btn btn-primary" style="margin: 8px 5px;">SEARCH</button></li>
                </ul>
                </form>
            </div>
        </div>
    </nav>

    @yield('content')
    <footer class="copywrite footer">
        <div class="container">
            <p>Copyright <a href="http://lazycrossing.com/">Lazy Crossing</a></p>
        </div>
    </footer>

    
</body>
</html>
