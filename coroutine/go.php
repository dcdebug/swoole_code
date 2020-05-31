<?php

ini_set("swoole.use_shortname",'on'); //开启Coroutine 需要的必须的条件:swoole.use_shortname = off;
var_dump(ini_get("swoole.use_shortname"));
go(function(){
    $redis = new Swoole\Coroutine\Redis();
    $redis->connect('127.0.0.1','6379');
    $redis->set("key",'aaaa');
    $value = $redis->get('key');
    var_dump($value);
});