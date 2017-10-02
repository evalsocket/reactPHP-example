<?php

    // downloading the two github project in parallel

      require './vendor/autoload.php';
      use React\Stream\ReadableResourceStream;
      use React\Stream\WritableResourceStream;
      
      # Creating a event loop for our server
      $loop = React\EventLoop\Factory::create();
      # project url 
      $files = array(
          'react.zip' => 'https://github.com/reactphp/react/archive/master.zip',
          'event-loop.zip' => 'https://github.com/reactphp/react/archive/master.zip',   
          'stream.zip' => 'https://github.com/reactphp/stream/archive/master.zip',
      );


    foreach ($files as $file => $url) {
        #Open Files for read and write
        $readStream = fopen($url, 'r');
        $writeStream = fopen("./download/".$file, 'w');
        #Disable blocking mode
        stream_set_blocking($readStream, 0);
        stream_set_blocking($writeStream, 0);
        print($readStream);
        # Create Stream and added to event loop
        $read = new ReadableResourceStream($readStream, $loop);
        $write = new WritableResourceStream($writeStream, $loop);
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