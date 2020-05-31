<?php

//定时任务，基于Swoole::Timer
// 底层基于epoll_wait和setitimer 实现，数据结构使用"最小堆"，可支持添加大量定时器。
Swoole\Timer::tick(5000,function(){
    echo date("Y-m-d H:i:s").PHP_EOL;
});
