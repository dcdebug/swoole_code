<?php
/**
 * 创建一个tcp服务器
 */
//Swoole\Coroutine\Server 是一个完全协程化的类，用于创建协程 TCP 服务器，支持 TCP 和 unixSocket 类型。
$host = '0.0.0.0';
$port = '9999';
$ssl = false;
$reuse_port = true;//是否开启端口重用，
// 此节包含 Swoole\Server 类的全部方法、属性、配置项以及所有的事件。
//Swoole\Server 类是所有异步风格服务器的基类，
//后面章节的 Http\Server、WebSocket\Server、Redis\Server 都继承于它。
//

//swoole process
$pool = new Swoole\Process\Pool(2);
$pool->set(['enable_coroutine'=>true]);

$pool->on("workerStart",function($pool,$id) use($host,$port,$ssl,$reuse_port){

    echo "pool is ".PHP_EOL;
    var_dump($pool);
    echo "id is ".$id.PHP_EOL;
    $swoole_tcp_server =  new Swoole\Coroutine\Server($host,$port,$ssl,$reuse_port);

//设置协议的参数
    //如果用户开启了Ctrl+c信号，就停止
    Swoole\Process::signal(SIGTERM,function () use ($swoole_tcp_server){
       $swoole_tcp_server->shutdown();
       echo "tcp server shutdonw\n";
    });
    $swoole_tcp_server->set([
        'open_length_check' => true,
        'package_max_length' => 1024 * 1024,
        'package_length_type' => 'N',
        'package_length_offset' => 0,
        'package_body_offset' => 4,
    ]);
//ssl证书设置
    $swoole_tcp_server->set([
        'ssl_cert_file' => dirname(__DIR__) . '/ssl/server.crt',
        'ssl_key_file' => dirname(__DIR__) . '/ssl/server.key',
    ]);

//设置连接处理函数
    $swoole_tcp_server->handle(
    /**
     * @param \Swoole\Coroutine\Server\Connection $connection
     *  服务回调处理函数
     */
    function (Swoole\Coroutine\Server\Connection $connection){
        $data = $connection->recv();
        if(empty($data)){
            $connection->close();
        }
        //while(true){
            //打印connection的socket属性
            echo "connected :".PHP_EOL;
            var_dump($connection);
            echo PHP_EOL;
            echo "socket 属性".PHP_EOL;
            var_dump($connection->socket);
            echo PHP_EOL;
            echo "接收到的数据".PHP_EOL;
            var_dump($data);
            //回应的数据
            $connection->send("I get Your message");
        //}
    });
    $swoole_tcp_server->start();
});
$pool->start();

