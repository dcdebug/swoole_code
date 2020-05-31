<?php

//Swoole Table ，Swoole 共享内存表

$fds = array();

$server = new \Swoole\Server("127.0.0.1",'9999');

$server->on('connect',function($server,$fd){
    echo "connection open :{$fd}\n";
    global $fds;
    $fds[] = $fd;
    var_dump($fds);
});
$server->start();
