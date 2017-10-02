<?php

    // downloading the two github project in parallel

    require 'vendor/autoload.php'
      
    # Creating a event loop for our server
    $loop = React\EventLoop\Factory::create();
    # project url 
    $files = array(
        'react' => 'https://github.com/reactphp/react/archive/master.zip',
        'event-loop' => 'https://github.com/reactphp/react/archive/master.zip',   
        'stream' => 'https://github.com/reactphp/stream/archive/master.zip'
    );


    foreach ($files as $file => $url) {
        #Open Files for read and write
        $readStream = fopen($url, 'r');
        $writeStream = fopen($file, 'w');
        #Disable blocking mode
        stream_set_blocking($readStream, 0);
        stream_set_blocking($writeStream, 0);
        # Create Stream and added to event loop
        $read = new React\Stream\Stream($readStream, $loop);
        $write = new React\Stream\Stream($writeStream, $loop);
        # Creat a event of read stream with a callback
        $read->on('end', function () use ($file, &$files) {
            unset($files[$file]);
            echo "Finished downloading $file\n";
        });
        # Stream Pip
        $read->pipe($write);
    }

    $loop->addPeriodicTimer(5, function ($timer) use (&$files) {
        if (0 === count($files)) {
            $timer->cancel();
        }

        foreach ($files as $file => $url) {
            $mbytes = filesize($file) / (1024 * 1024);
            $formatted = number_format($mbytes, 3);
            echo "$file: $formatted MiB\n";
        }
    });

    echo "This script will show the download status every 5 seconds.\n";

    $loop->run();