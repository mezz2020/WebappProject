<?php
// 事件定义文件
return [
    'bind'      => [],

    'listen'    => [
        'AppInit'  => [],
        'HttpRun'  => [],
        'HttpEnd'  => [],
        'LogLevel' => [],
        'LogWrite' => [],

        "Listest"  =>  ['app\listener\WebsocketMsg']
    ],

    'subscribe' => [
    ],
];
