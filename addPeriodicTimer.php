<?php
    require './vendor/autoload.php';
      
    # Creating a event loop for our server
    $loop   = React\EventLoop\Factory::create();

    # Print Current memory usages ever 5 sec
    $loop->addPeriodicTimer(5, function () {
        $memory = memory_get_usage() / 1024;
        $formatted = number_format($memory, 3).'K';
        echo "Current memory usage: {$formatted}\n";
    });

    $loop->run();