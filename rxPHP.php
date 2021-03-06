<?php
  require './vendor/autoload.php';
  use React\EventLoop\Factory;
  use Rx\Disposable\CallbackDisposable;
  use Rx\ObserverInterface;
  use Rx\Scheduler\EventLoopScheduler;

  $loop = Factory::create();

  $observable = Rx\Observable::create(function (ObserverInterface $observer) use ($loop) {
      $handler = function () use ($observer) {
          $observer->onNext(42);
          $observer->onCompleted();
      };
      // Change scheduler for here
      $timer = $loop->addTimer(0.001, $handler);
      return new CallbackDisposable(function () use ($timer) {
          // And change scheduler for here
          if ($timer) {
              $timer->cancel();
          }
      });
  });
  $observable
      ->subscribeOn(new EventLoopScheduler($loop))
      ->subscribe($stdoutObserver);
  $loop->run();
  //Next value: 42
  //Complete!