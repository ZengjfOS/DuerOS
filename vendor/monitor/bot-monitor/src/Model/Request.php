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
 * @desc DuerOS对Bot的请求封装
 * @author yuanpeng01@baidu.com
 **/
namespace Baidu\Apm\BotMonitorsdk\Model;

class Request {
    /**
     * 当前请求的类型，对应request.type
     **/
    private $requestType;

    /**
     * UIC 用户信息
     **/
    private $arrUserProfile;

    /**
     * 原始数据
     **/
    private $data;

    /**
     * 设备信息。比如闹钟列表
     **/
    private $deviceData;

    /**
     * @desc 返回request 请求体
     * @param null
     * @return array
     **/
    public function getData(){
        return $this->data; 
    }
    
    /**
     * @deprecated
     * @desc 返回设备信息
     * @param null
     * @return Nlu
     **/
    public function getDeviceData(){
        return $this->deviceData;
    }

    /**
     * 获取设备id
     * @desc 获取设备id
     * @param null
     * @return string
     **/
    public function getDeviceId() {
        if (isset($this->data['context']['System']['device']['deviceId'])) {
            return $this->data['context']['System']['device']['deviceId']; 
        }
        return '';
    }

    /**
     * 获取设备音频播放的状态
     *
     * @desc 获取设备音频播放的状态
     * @param null
     * @return array
     **/
    public function getAudioPlayerContext() {
        return $this->data['context']['AudioPlayer']; 
    }

    /**
     * 获取设备app安装列表
     *
     * @desc 获取设备app安装列表
     * @param null
     * @return array
     **/

    public function getAppLauncherContext() {
        return $this->data['context']['AppLauncher']; 
    }

    /**
     * 获取event请求
     *
     * @desc 返回event request数据
     * @param null
     * @return array
     **/
    public function getEventData() {
        if($this->requestType == 'IntentRequest'
           || $this->isSessionEndedRequest()
           || $this->isLaunchRequest()) {
              return; 
           }

        return $this->data['request'];
    }

    /**
     * @deprecated
     * @param null
     * @return array
     **/
    public function getUserInfo() {
        return $this->data['user_info'];
    }
    
    /**
     * 获取request类型
     *
     * @param null
     * @return string 
     */
    public function getType() {
        return $this->requestType;
    }

    /**
     * 获取用户id
     *
     * @param null
     * @return string
     **/
    public function getUserId() {
        if (isset($this->data['context']['System']['user']['userId'])) {
            return $this->data['context']['System']['user']['userId'];
        }
        return '';
    }

    /**
     * @deprecated
     * @desc 获cuid
     * @param null
     * @return string
     **/
    public function getCuid() {
        return $this->data['cuid'];
    }


    /**
     * 获取query
     * @desc 获取当前请求的query
     *
     * @param null
     * @return string
     **/
    public function getQuery() {
        if($this->requestType == 'IntentRequest') {
            return $this->data['request']['query']['original'];
        }
        return '';
    }


    /**
     * 获取地址
     * @desc 获取当前用户设备的位置信息。具体协议参考连接TODO
     *
     * @param null
     * @return array
     **/
    public function getLocation() {
        if(isset($this->data['context']['System']['user']['userInfo']['location']['geo'])) {
            return $this->data['context']['System']['user']['userInfo']['location']['geo'];
        }
    }

    /**
     * 获取请求的时间戳
     *
     * @return string
     */
    public function getTimestamp() {
        if (isset($this->data['request']['timestamp'])) {
            return $this->data['request']['timestamp'];
        }
        return '';
    }

    /**
     * 获取log_id
     *
     * @deprecated
     * @param null
     * @return string
     */
    public function getLogId() {
        return $this->data['log_id'];
    }
    
    /**
     * 获取botid
     *
     * @param null
     * @return string
     **/
    public function getBotId() {
        return $this->data['context']['System']['application']['applicationId']; 
    }


    /**
     * 获取requestId
     *
     * @param null
     * @return string
     **/
    public function getRequestId() {
        if (isset($this->data['request']['requestId'])) {
            return $this->data['request']['requestId'];
        }
        return '';
    }

    /**
     * 获取reson
     *
     * @param null
     * @return string
     **/
    public function getReson() {
        if (isset($this->data['request']['reason'])) {
            return $this->data['request']['reason'];
        }
        return '';
    }

    /**
     * 获取intentName
     *
     * @param null
     * @return string
     **/
    public function getIntentName() {
        if ($this->data['request'] && $this->data['request']['intents'] 
            && $this->data['request']['intents'][0]) {
            return $this->data['request']['intents'][0]['name'];
        }
        return "";
    }

    /**
     * 获取sessionId
     *
     * @param null
     * @return string
     **/
    public function getSessionId() {
        if (isset($this->data['session']['sessionId'])) {
            return $this->data['session']['sessionId'];
        }
        return '';
    }

    /**
     * 构造函数
     *
     * @param array
     * @return null
     **/
    public function __construct($data) {
        $this->data = $data;
        $this->requestType = $data['request']['type'];
    }
}

