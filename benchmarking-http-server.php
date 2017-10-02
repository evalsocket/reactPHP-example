<?php
  require 'vendor/autoload.php'
  # Creating a event loop for our server
  $loop   = React\EventLoop\Factory::create();
  # Creating a server and passing event loop to that socket 
  $socket = new React\Socket\Server($loop);
  # Creating a http server and passing socket 
  $http   = new React\Http\Server($socket);
  $i = 0;
  # Define a request event on http with callback  
  $http->on("request", function($request, $response) {
      
      $i++;
      # Write header of response 
      $response->writeHead(["Content-Type" => "text/plain"]);
      # end that request
      $response->end("Hello World");
  });

  $loop->addPeriodicTimer(5, function () use (&$i) {
    $kmem = memory_get_usage(true) / 1024;
    echo "Request: $i\n";
    echo "Memory: $kmem KiB\n";
  });

  # listen http server on PORT 8080 
  $socket->listen(8080);
  # start event loop
  $loop->run();
?>