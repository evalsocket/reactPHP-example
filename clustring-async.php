<?php
    require 'vendor/autoload.php'
    // a simple, single-process, horizontal scalable http server listening on 5 ports
    
    # Creating a event loop for our server
    $loop = React\EventLoop\Factory::create();

    for ($i=0;$i<5;++$i) {
        # check for documentation of stream_socket_server http://php.net/manual/en/function.stream-socket-server.php
        $server=stream_socket_server('tcp://127.0.0.1:'.(808+$i));
        stream_set_blocking($server, 0);
        # add server to event loop with a callback function
        $loop->addReadStream($server, function ($server) use ($i) {
            # start accepting request from port 808+$i
            $conn=stream_socket_accept($server);
             
            $len=strlen($i)+4;
          
            # Add connection to a event loop
            $loop->addWriteStream($conn, function ($conn) use (&$len, $i) {
                $data = "HTTP/1.1 200 OK\r\nContent-Length: $len\r\n\r\nHi\n";
                $written = fwrite($conn, $data);
                if ($written === strlen($data)) {
                    fclose($conn);
                    $loop->removeStream($conn);
                } else {
                    $data = substr($data, $written);
                }
            });
            # Wwrite header of response 
            fwrite($conn,$data);
            echo "Served on port 800$i\n";
        });
    }
    echo "Access your brand new HTTP server on 127.0.0.1:808x. Replace x with any number from 0-9\n";
    # start event loop
    $loop->run();