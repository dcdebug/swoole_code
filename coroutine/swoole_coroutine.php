<?php


use Swoole\Coroutine;

$host = "127.0.0.1";

$port = "9999";

$ssl = false;
/*
$server = new   Swoole\Coroutine\Server($host,$port,$ssl);

$server->set([
    'open_length_check' => true,
    'package_max_length' => 1024 * 1024,
    'package_length_type' => 'N',
    'package_length_offset' => 0,
    'package_body_offset' => 4,
]);


$server->handler(function(){
    echo strtolower(__FUNCTION__).PHP_EOL;
});*/


//多进程管理模块
$pool = new Swoole\Process\Pool(2);

//让每一个onWorkerStart 回掉都自动创建一个携程
$pool->set(['enable_coroutine'=>true]);

$pool->on("workerStart",function($pool,$id) use($host,$port,$ssl){
        //每个进程都监听9999端口
        $server = new Swoole\Coroutine\Server($host,$port,$ssl,!$ssl);
        //收到15信号关闭服务SIGTERM
        Swoole\Process::signal(SIGTERM,function() use ($server){
           $server->shutdown();
        });
        $server->handle(function (Swoole\Coroutine\Server\Connection $conn){
           // 接受数据
            $data = $conn->recv();
            echo "Recv the Data: ".$data.PHP_EOL;
            if(empty($data)){
                //关闭链接
                $conn->close();
            }
            //发送数据
            $conn->send("hello".print_r($data,true));
        });
        //开始监听端口
        $server->start();
});
$pool->start();
