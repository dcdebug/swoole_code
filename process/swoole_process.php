<?php

// swoole 进程操作
use Swoole\Process;

echo "父进程id是:".getmypid().PHP_EOL;

//Process 的构造函数

for ($n = 1 ;$n<=10; $n++){
    $process = new Process(function()use ($n){
            echo " Child pid is #".getmypid(). " start and sleep {$n}s" .PHP_EOL;
            sleep($n);
            echo " Child pid 's processid ".getmypid()." exit; ".PHP_EOL;
    });
     //$process->pid.PHP_EOL;
    // echo $process->pipe.PHP_EOL;
    $process->start();
    var_dump("子进程的id :".$process->pid);

}

for($n = 10; $n--;){
    //阻塞等待 子进程退出
    $status = Process::wait(true);
    // echo "进程信息:".print_r($status).PHP_EOL;
    echo "Recycled  the pid :".$status['pid']." , code = ".$status['code']." and signal = ".$status['signal']." ;".PHP_EOL;
}

echo "父进程信息id :".getmypid();
