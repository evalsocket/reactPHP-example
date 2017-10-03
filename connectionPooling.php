<?php
  
  require './vendor/autoload.php';

  use Psr\Http\Message\ServerRequestInterface;
  use React\Http\Response;
  use React\Http\Server;
 
  # Creating a event loop for our server
  $loop = React\EventLoop\Factory::create();
   
  $config = "mongodb://127.0.0.1:27017";
  
  $connection = null;
  
  $loop->addTimer(0.0001, function () use ($loop,&$connection,&$config) {
        // connection kind of stuff
        //$connection = new MongoClient( $config );
        $connection = 'No Email Dude';
  });
  
  # Creating a server and passing event loop to that socket 
  $server = new Server(function (ServerRequestInterface $request) use (&$loop,&$connection) {
    $loop->addTimer(0.0001, function () use (&$loop,&$request,&$connection,&$config) {
          
        $parama = $request->getQueryParams();
          if(isset($parama['email'])) $data = "Your email is ".$parama['email'];
          else $data = $connection;
          // business Logic with connection 
          return new Response(
              200,
              array('Content-Type' => 'text/plain'),
              "kjbjk"
          );
     });
  });

  $socket = new React\Socket\Server(3001, $loop);
  $server->listen($socket);

  $loop->run();
 ?>