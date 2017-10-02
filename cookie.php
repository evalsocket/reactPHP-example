<?php

  require './vendor/autoload.php';
  use Psr\Http\Message\ServerRequestInterface;
  use React\Http\Response;
  use React\Http\Server;

  # Creating a event loop for our server
  $loop = React\EventLoop\Factory::create();

 $server = new Server(function (ServerRequestInterface $request) {
    $key = 'login';
    if (isset($request->getCookieParams()[$key])) {
        $body = "Your cookie value is: " . $request->getCookieParams()[$key];
        return new Response(
            200,
            array('Content-Type' => 'text/plain'),
            $body
        );
    }
    return new Response(
        200,
        array(
            'Content-Type' => 'text/plain',
            'Set-Cookie' => urlencode($key) . '=' . urlencode('true')
        ),
        "Your cookie has been set."
    );
});

  $socket = new React\Socket\Server(3000, $loop);
  $server->listen($socket);

  $loop->run();
 ?>