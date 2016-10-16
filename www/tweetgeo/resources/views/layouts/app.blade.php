<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

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
         margin-top: 30px;
         padding-top: 10px;
         background: #222222;
         color: #999;
     }

     html {
         position: relative;
         min-height: 100%;
     }

     body {
         background: #606860;
         margin-bottom: 60px;
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
    <script>
     function getLocation() {
         if (navigator.geolocation) {
             navigator.geolocation.getCurrentPosition(showPosition);
         } else {
             alert("Failed to get your location.");
         }
     }
     function showPosition(position) {
         $("#lat").val(position.coords.latitude)
         $("#lon").val(position.coords.longitude)
         $("#radius").val(10)
     }
    </script>

</head>
<body>
    <nav class="navbar navbar-default navbar-static-top" style="margin-bottom: 0px; background: #F0F0E0">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="/tweetgeo" style="color: #555;">
                    <strong>Tweet. Geo</strong>
                </a>
            </div>
      
            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <form method="GET" action="get_tweets">
                    {{ csrf_field() }}

                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    &nbsp;
                    <li class="coodinates"><input tyle="text" name="lat" id="lat" class="form-control" 
                                                  placeholder="Latitude" pattern="[-.0-9]+" value="@if(!empty($lat)){{$lat}}@endif" required/></li>
                    <li class="coodinates"><input tyle="text" name="lon" id="lon" class="form-control" 
                                                  placeholder="Longitude" pattern="[-.0-9]+" value="@if(!empty($lon)){{$lon}}@endif"  required/></li>
                    <li class="coodinates radius"><input tyle="text" name="radius" id="radius" class="form-control" 
                                                         placeholder="Radius (miles)" pattern="[0-9]+" value="@if(!empty($radius)){{$radius}}@endif" required/></li>
                    <li class="coodinates filter"><input tyle="text" name="filter" id="filter" class="form-control" style="background:#F9F9F9" 
                                                         placeholder="Keyword Filter (optional)" value="@if(!empty($filter)){{$filter}}@endif"/></li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right" style="color: #999;">
                    <li><button type="button" class="btn btn-info" style="margin: 8px 5px;" onClick="getLocation(); return false;">My Location</button></li>
                    <li><button type="submit" class="btn btn-primary" style="margin: 8px 5px;">Search</button></li>
                </ul>
                </form>
            </div>
        </div>
    </nav>

    @yield('content')
    <footer class="copywrite footer">
        <div class="container">
            <p>Powered By <a href="http://lazycrossing.com/">Lazy Crossing</a></p>
        </div>
    </footer>

    
</body>
</html>
