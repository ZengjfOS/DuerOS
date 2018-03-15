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
 * @desc 封装Bot对DuerOS的返回结果
 * @author liudesheng01@baidu.com
 **/
namespace Baidu\Apm\BotMonitorsdk\Model;

class Response{

    /**
     * 返回给DuerOS的数组
     **/
    private $data;

    /**
     * @param Request $request 请求对象
     * @param Nlu $nlu nlu对象
     * @return null
     **/
    public function __construct($responseData){
        $this->data = $responseData;
    }


    /**
     * @desc 获取outputSpeech
     * @param null
     * @return array
     **/
    public function getOutputSpeech(){
        if (isset($this->data['response']['outputSpeech'])) {
            return $this->data['response']['outputSpeech'];
        }
    }

    /**
     * @desc 返回对话是否结束
     * @param null
     * @return bool
     **/
    public function getShouldEndSession(){
        if (isset($this->data['response']['shouldEndSession'])) {
            return $this->data['response']['shouldEndSession'];
        }
    }

    /**
     * @desc 返回本次更新的slot名字
     * @param null
     * @return string
     **/
    public function getSlotName(){
        $directive = $this->data['response']['directives'];

        if ($directive && $directive[0] && isset($directive[0]['slotToElicit'])) {
            return $directive[0]['slotToElicit'];
        }
        return  "";
    }

    /**
     * @desc 返回DuerOS的提示
     * @param null
     * @return Array
     **/
    public function getReprompt(){
        if (isset($this->data['response']['reprompt']['outputSpeech'])) {
            return $this->data['response']['reprompt']['outputSpeech'];
        }
    }
}
