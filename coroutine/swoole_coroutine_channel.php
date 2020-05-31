<?php

Co\run(function () {

    //声明一个Channel

    $channel = new Swoole\Coroutine\Channel(1);
    print_r($channel);
    Swoole\Coroutine::create(function () use ($channel) {
        for ($i = 0; $i < 10; $i++) {
            co::sleep(1.0);
            $channel->push(['rand' => rand(1000, 9999), 'index' => $i."test. coroutine . ".$i]);
            echo "$i\n";
        }
    });
    Swoole\Coroutine::create(function () use ($channel) {
        while (1) {
            $data = $channel->pop();
            if($data){
                var_dump($data);
            }else{
                return false;
            }
        }
    });
});


Swoole\Coroutine::create(function(){
    echo "Current Pid Process is :".getmypid().PHP_EOL;
    echo "Current Coroutine Pid is :".Swoole\Coroutine::getPcid().PHP_EOL;
    echo "Current Coroutine id is :".Swoole\Coroutine::getCid().PHP_EOL;
    print_r(Swoole\Coroutine::getContext(),true);

    echo "获取当前父协程的上下文".PHP_EOL;
    var_dump(Swoole\Coroutine::getContext(Swoole\Coroutine::getPcid()));

    print_r(\Swoole\Coroutine::getContext(\Swoole\Coroutine::getPcid()));

});


