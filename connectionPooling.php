<?php
  
  require './vendor/autoload.php';
  use Psr\Http\Message\ServerRequestInterface;
  use React\Http\Response;
  use React\Http\Server;
  
 
  # Creating a event loop for our server
  $loop = React\EventLoop\Factory::create();
   
  $config = "mongodb://127.0.0.1:27017";
  
  $connection = null;
  
  $loop->addTimer(0.001, function () use ($loop,&$connection,&$config) {
        //$connection = new MongoClient( $config );
        $connection = array(100,2,3);
  });
  
  # Creating a server and passing event loop to that socket 
  $server = new Server(function (ServerRequestInterface $request) use (&$connection) {
    $data = "Your first element is $connection[0]";
    return new Response(
        200,
        array('Content-Type' => 'text/plain'),
        $data
    );
  });

  $socket = new React\Socket\Server(3001, $loop);
  $server->listen($socket);

  $loop->run();
 ?>