<?php

  require './vendor/autoload.php';
  use Psr\Http\Message\ServerRequestInterface;
  use React\Http\Response;
  use React\Http\Server;

  # Creating a event loop for our server
  $loop = React\EventLoop\Factory::create();
  # Creating a server and passing event loop to that socket 
  $server = new Server(function (ServerRequestInterface $request) {
    $queryParams = $request->getQueryParams();
    if (isset($queryParams['email'])) {
        $body = 'The value of "email" is: ' . htmlspecialchars($queryParams['email']);
    }else{
       $body = 'You are doing Shit. Try /?email=xyz@gmail.com';
    } 
    return new Response(
        200,
        array('Content-Type' => 'text/plain'),
        $body
    );
  });

  $socket = new React\Socket\Server(3000, $loop);
  $server->listen($socket);

  $loop->run();
 ?>