<?php
// 服务器协程风格的ws服务器
Co\run(function () {
    //完全协程化的 WebSocket 服务器实现，继承自 Co\Http\Server，底层提供了对 WebSocket 协议的支持，在此不再赘述，只说差异。
    $server = new Co\Http\Server("127.0.0.1", 9502, false);
    //服务器端
    $server->handle('/websocket', function ($request, $ws) {
        // 向客户端发送websocket握手信息
        $ws->upgrade();
        //循环接受和发送信息
        while (true) {
            //接受消息zhen
            $frame = $ws->recv();
            var_dump($frame);
            if ($frame === false) {
                echo "error : " . swoole_last_error() . "\n";
                break;
            } else if ($frame == '') {
                break;
            } else {
                if ($frame->data == "close") {
                    $ws->close();
                    return;
                }
                //推送消息
                $ws->push("Hello {$frame->data}!");
                $ws->push("How are you, {$frame->data}?");
            }
        }
    });
    // client html端
    $server->handle('/', function ($request, $response) {
        $response->end(<<<HTML
    <h1>Swoole WebSocket Server</h1>
    <script>
var wsServer = 'ws://127.0.0.1:9502/websocket';
var websocket = new WebSocket(wsServer);
websocket.onopen = function (evt) {
    console.log("Connected to WebSocket server.");
    websocket.send('hello test ');
};

websocket.onclose = function (evt) {
    console.log("Disconnected");
};

websocket.onmessage = function (evt) {
    console.log('Retrieved data from server: ' + evt.data);
};

websocket.onerror = function (evt, e) {
    console.log('Error occured: ' + evt.data);
};
</script>
HTML
        );
    });

    $server->start();
});

