<?php

  require './vendor/autoload.php';
  use Psr\Http\Message\ServerRequestInterface;
  use React\Http\Response;
  use React\Http\Server;
  use React\Stream\CompositeStream;
  use React\Stream\ThroughStream;

  # Creating a event loop for our server
  $loop = React\EventLoop\Factory::create();
  # Creating a server and passing event loop to that socket 
  $chat = new ThroughStream();
  $server = new Server(function (ServerRequestInterface $request) use ($loop, $chat) {
    if ($request->getHeaderLine('Upgrade') !== 'chat' || $request->getProtocolVersion() === '1.0') {
        return new Response(426, array('Upgrade' => 'chat'), '"Upgrade: chat" required');
    }
    // user stream forwards chat data and accepts incoming data
    $out = $chat->pipe(new ThroughStream());
    $in = new ThroughStream();
    $stream = new CompositeStream(
        $out,
        $in
    );
    // assign some name for this new connection
    $username = 'user' . mt_rand();
    // send anything that is received to the whole channel
    $in->on('data', function ($data) use ($username, $chat) {
        $data = trim(preg_replace('/[^\w\d \.\,\-\!\?]/u', '', $data));
        $chat->write($username . ': ' . $data . PHP_EOL);
    });
    // say hello to new user
    $loop->addTimer(0, function () use ($chat, $username, $out) {
        $out->write('Welcome to this chat example, ' . $username . '!' . PHP_EOL);
        $chat->write($username . ' joined' . PHP_EOL);
    });
    // send goodbye to channel once connection closes
    $stream->on('close', function () use ($username, $chat) {
        $chat->write($username . ' left' . PHP_EOL);
    });
    return new Response(
        101,
        array(
            'Upgrade' => 'chat'
        ),
        $stream
    );
});

  $socket = new React\Socket\Server(3000, $loop);
  $server->listen($socket);

  $loop->run();
 ?>