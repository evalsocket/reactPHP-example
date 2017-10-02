<?php
    // http client making a request to github api
    require 'vendor/autoload.php';

    # Creating a event loop for our server
    $loop = React\EventLoop\Factory::create();

    # Create a HHTP Client and add it in event loop
    $client = new React\HttpClient\Client($loop);

    # Send a Get reuest to 
    $request = $client->request('GET', 'https://github.com/reactphp/react/archive/master.zip');
    # Define events for request 
    $request->on('response', function ($response) {
        $buffer = '';
        $response->on('data', function ($data) use (&$buffer) {
            $buffer .= $data;
            echo ".";
        });
        $response->on('end', function () use (&$buffer) {
            $decoded = json_decode($buffer, true);
            $latest = $decoded[0]['commit'];
            $author = $latest['author']['name'];
            $date = date('F j, Y', strtotime($latest['author']['date']));
            echo "\n";
            echo "Latest commit on react was done by {$author} on {$date}\n";
            echo "{$latest['message']}\n";
        });
    });
    $request->on('end', function ($error, $response) {
        echo $error;
    });

    $request->end();

    $loop->run();