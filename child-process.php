<?php
  require './vendor/autoload.php';
  # Creating a event loop for our server
  $loop = React\EventLoop\Factory::create();
  # Define a blocking process
  $process = new React\ChildProcess\Process('php child-block.php');

  $process->on('exit', function($exitCode, $termSignal) {
    echo "Child exit\n";
  });
  
  
  $loop->addTimer(1, function($timer) use ($process) {
    $process->start($timer->getLoop());
    $process->stdout->on('data', function($output) {
        echo "Child script says: {$output}";
    });
  });

  $loop->addPeriodicTimer(5, function($timer) {
     echo "Parent cannot be blocked by child\n";
  });
  
  $loop->run();

  