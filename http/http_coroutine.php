<?php


// coroutine 协程


Co\run(function () {
    $server = new Co\Http\Server("127.0.0.1", 9502, false);
    $server->handle('/', function ($request, $response) {

        echo "Hello ,My /".PHP_EOL;
        $response->end("<h1>Index</h1>");
    });
    $server->handle('/test', function ($request, $response) {
        echo "Hello ,My /test".PHP_EOL;
        $response->end("<h1>Test</h1>");
    });
    $server->handle('/stop', function ($request, $response) use ($server) {
        echo "Hello, My /stop".PHP_EOL;
        $response->end("<h1>Stop</h1>");
        $server->shutdown();
    });
    $server->start();
});
