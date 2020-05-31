<?php


use Swoole\Coroutine as co;

$id = go(function () {
    $id = co::getuid();
    echo "start coro $id \n";
    co::suspend();
    echo "resume coro $id @1\n";
    co::suspend();
    echo "resume coro $id @2\n";

});

echo "start to resume $id @1\n";
co::resume($id);
echo "start to resume $id @2\n";
co::resume($id);
echo "main\n";

for($i=0;$i<10000;$i++){

    go(function(){
       $id = co::getuid();

       echo "start the coroutine id :".$id.PHP_EOL;
       co::yield();
    });
}

$coros = Swoole\Coroutine::listCoroutines();
/*echo count($coros);
return false;*/
foreach($coros as $cid)
{
   /* $result = $cid->stats();
    print_r($result);*/
    //var_dump(Swoole\Coroutine::getBackTrace($cid));
}
$result = co::stats();
print_r($result);
var_dump(co::getElapsed());




