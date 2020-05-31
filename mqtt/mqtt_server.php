<?php

//创建一个物联网服务器
//mqtt 协议介绍:https://mcxiaoke.gitbook.io/mqtt/01-introduction


class Mqtt
{

    /**
     * 解析Value
     * @param $data
     * @return float|int
     */
    public static function decode_value($data)
    {
        return 256 * ord($data[0]) + ord($data[1]);
    }

    public static function decode_string($data)
    {
        $length = self::decode_string($data);
        return substr($data, 2, $length);
    }


    static function mqttGetHeader($data)
    {
        $byte = ord($data[0]);

        $header['type'] = ($byte & 0xF0) >> 4;
        $header['dup'] = ($byte & 0x08) >> 3;
        $header['qos'] = ($byte & 0x06) >> 1;
        $header['retain'] = $byte & 0x01;
        return $header;
    }

    static function eventConnect($header, $data)
    {
        $connection_info['protocol_name'] = self::decode_string($data);
        $offset = strlen($connection_info['protocol_name']) + 2;
        $connection_info['version'] = ord(substr($data, $offset, 1));

        $offset += 1;

        $byte = ord($data[$offset]);

        $connection_info['willRetain'] = ($byte & 0x20 == 0x20);
        $connection_info['willQos'] = ($byte & 0x18 >> 3);
        $connection_info['willFlag'] = ($byte & 0x04 == 0x04);
        $connection_info['cleanStart'] = ($byte & 0x02 == 0x02);

        $offset += 1;

        $connection_info['keeplive'] = self::decode_value(substr($data, $offset, 2));
        $offset += 2;
        $connection_info['clientId'] = self::decode_string(substr($data, $offset));
        return $connection_info;
    }
}

//创建swoole 服务器
$host = '127.0.0.1';
$port = '9501';
$model = SWOOLE_BASE;

$server = new Swoole\Server($host, $port, $model);

$server->set(
    array('open_mqtt_protocol' => 1,  //启用mqtt协议
        'worker_num' => 1)
);

$server->on("start",function(){
    echo date("Y-m-d H:i:s").",mqtt server start".PHP_EOL;
});
$server->on("connect",function($server,$d){
    echo "Connect the mqtt Server";
});

$server->on("receive",function($server,$fd,$from_id,$data){
    $header = Mqtt::mqttGetHeader($data);

    var_dump($header);
    if($header['type'] == 1){
        $resp = chr(32).chr(2).chr(0).chr(0);

        Mqtt::eventConnect($header,substr($data,2));
        $server ->send($fd,$resp);
    }else if($header['type'] == 3){
        $offset = 2;

        $topic = Mqtt::decode_string(substr($data,$offset));

        $offset += strlen($topic) +2;
        $msg = substr($data,$offset);

        echo "client msg :$topic \n ---------------\n $msg \n";
        // file_put_content();
    }

    echo "received length = ". strlen($data)."\n";
});

$server->on("close",function($server,$fd){
    echo 'Client '.$fd." Close".PHP_EOL;
});

$server->start();


