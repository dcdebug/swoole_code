<?php
//ini_set("swoole.use_shortname",'on');

echo swoole_version().PHP_EOL;

//Swoole\Runtime::enableCoroutine(); //此行代码后，文件操作，sleep，Mysqli，PDO，streams等都变成异步IO，见'一键协程化'章节

$s = microtime(true);
$host = "127.0.0.1";
$port = '9999';
$timeout = 0.5;


Swoole\Coroutine::create(function()use ($s,$host,$port,$timeout){
    $client = new Swoole\Coroutine\Client(SWOOLE_SOCK_TCP);

    if(!$client->connect($host,$port,$timeout)){
        echo "connect failed . Error : ".$client->errCode.PHP_EOL;
    }else{
        $client->send(date("Y-m-d H:i:s ",$s). " msg :hello world " .PHP_EOL);
        echo date("Y-m-d H:i:s")." Recive ms : ".$client->recv().PHP_EOL;
        $client->close();
    }
});
