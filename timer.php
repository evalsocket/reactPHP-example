<?php
  require './vendor/autoload.php';
  # Creating a event loop for our server
  $loop   = React\EventLoop\Factory::create();

  $loop->addTimer(0.1, function () {
    echo 'world!' . PHP_EOL;
  });

  $loop->addTimer(0.1, function () {
      echo 'hello ';
  });


  # start event loop
  $loop->run();

?>



