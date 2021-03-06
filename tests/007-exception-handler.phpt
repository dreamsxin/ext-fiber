--TEST--
FiberScheduler throwing from run() with an uncaught exception handler
--SKIPIF--
<?php if (!extension_loaded('fiber')) echo "ext-fiber not loaded";
--FILE--
<?php

require dirname(__DIR__) . '/scripts/bootstrap.php';

\set_exception_handler(function (\Throwable $exception): void {
    echo "Caught Exception: ", $exception->getMessage(), PHP_EOL;
});

$loop = new Loop;

$loop->defer(function (): void {
    throw new Error('test');
});

$promise = new Promise($loop);

echo Fiber::await($promise, $loop);

--EXPECTF--
Caught Exception: test

Fatal error: Uncaught Error thrown from FiberScheduler::run(): test in %s on line %d
