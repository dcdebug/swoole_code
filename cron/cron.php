<?php

//设置一个间隔时钟定时器
/*$swoole_handler = swoole_timer_tick(5000,function(){
    echo date("Y-m-d H:i:s").PHP_EOL;
});*/
//sleep(60);

echo "Current Pid is ".getmypid().PHP_EOL;


$timer_id = swoole\Timer::tick(3000,function ($timer_id,$param1, $param2){
    echo "timer_id is ".$timer_id." after 3000ms \n";
    echo "param1 is $param1, param2 is $param2 \n";
    swoole\Timer::tick(14000,function($timer_id){
        echo " sub timer_id is $timer_id, after 14000 ms \n";
    });
},"A","B");
var_dump($timer_id);
sleep(3);
swoole\Timer::clear($timer_id);
