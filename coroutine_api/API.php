<?php

use Swoole\Coroutine;


Coroutine::set(
    array(
        "max_coroutine"=>10000,
        "stack_size"=>'2M', //
        "log_level"=>SWOOLE_LOG_DEBUG, // 0 https://wiki.swoole.com/#/consts?id=%e6%97%a5%e5%bf%97%e7%ad%89%e7%ba%a7
    )
);
echo "Parent Pid is ".getmypid().PHP_EOL;
Coroutine::create(function(){
    $cid = Coroutine::getCid();
    echo "my cid is ".$cid.PHP_EOL;
});


$cid = go(function () {
    echo "co 1 start\n";
    co::yield();
    echo "co 1 end\n";
});

go(function () use ($cid) {
    echo "co 2 start\n";
    co::sleep(0.5);
    co::resume($cid);
    echo "co 2 end\n";
});
