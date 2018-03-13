# DuerOS BAE IoT Test

* 目前部署的是：https://github.com/dueros/bot-sdk/tree/master/samples/thirdparty_nlu_self；
* 部署的项目目前是不对的，主要只是提供一个环境，用于测试，这里面有Log模块，可以用于做一些验证；

## index.php

```PHP
<?php
/**
 * Copyright (c) 2017 Baidu, Inc. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 * @desc 入口文件
 **/

require "Bot.php";
$bot = new Bot(file_get_contents("php://input"));
if($_SERVER['REQUEST_METHOD'] == 'HEAD'){
    header('HTTP/1.1 204 No Content');
}
header("Content-Type: application/json");

//记录整体执行时间
$bot->log->markStart('all_t');
$ret = $bot->run();
$bot->log->markEnd('all_t');

//打印日志
//or 在register_shutdown_function增加一个执行函数
$bot->log->notice('bot');

print $ret;
```

## Bot.php

```PHP
...
class Bot extends Baidu\Duer\Botsdk\Bot{
    /**
     * @param null
     * @return null
     **/
    public function __construct($postData = []) {
        parent::__construct($postData);

        $this->log = new Baidu\Duer\Botsdk\Log([
            //日志存储路径
            'path' => 'log/',
            //日志打印最低输出级别
            'level' => Baidu\Duer\Botsdk\Log::NOTICE,
        ]);

        //test fatal log，你可以这样来输出一个fatal日志
        //$this->log->fatal("this is a fatal log");
        $json_post = json_decode($postData, true);;

        //log 一个字段
        $this->log->setField('postData', $postData);
        $this->log->setField('postDatatype', gettype($postData));
        $this->log->setField('json_posttype', gettype($json_post));
        $this->log->setField('json_postHeader', $json_post["header"]);
        $this->log->setField('json_postHeader', $json_post["header"]["name"]);
        $this->log->setField('query', $this->request->getQuery());
        $this->log->setField('session.status', $this->getSessionAttribute('status'));
        ...
    }
    ...
}
```

## BAE WebSSH查看Log

`[NOTICE] [2018-03-13 09:13:46:848249] [15209036269554] [bot] postData:{"header":{"payloadVersion":"1","name":"DiscoverAppliancesRequest","namespace":"DuerOS.ConnectedHome.Discovery","messageId":"1be86a992789459c9f6f8b550e11a4da_0_Smarthome_5aa725cac03f37.64764941"},"payload":{"accessToken":"21.e6e236aa5055049bb19c119974a2c2bd.2592000.1523452149.879846760-10915439"}} postDatatype:string json_posttype:array json_postHeader:DiscoverAppliancesRequest query: session.status: all_t:0`


