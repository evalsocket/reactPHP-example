<?php

  require './vendor/autoload.php';
  # Creating a event loop for our server
  $loop = React\EventLoop\Factory::create();
  # Creating a server and passing event loop to that socket 
  $socket = new React\Socket\Server(8080, $loop);
  # Creating a http server and passing socket 
  $http   = new React\Http\Server($socket);
  # Define a request event on http with callback  
  $http->on("request", function($request, $response) {
      # Write header of response 
      $response->writeHead(["Content-Type" => "text/plain"]);
      # end that request
      $response->end("Hello World");
  });
  # listen http server on PORT 8080 
  $socket->listen(8080);
  # start event loop
  $loop->run();
?>