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
 * @desc 
 **/
namespace Baidu\Duer\Botsdk\Extensions;

class TTSTemplate{

    private $data = [];

     /**
     * @param TTSTemplateItem  $TTSTemplate 话术模板
     **/
    public function __construct($TTSTemplates = []) {
        $this->data['type'] = 'TTSTemplate';
        if(is_array($TTSTemplates) && $TTSTemplates){
            foreach($TTSTemplates as $TTSTemplate){
                if($TTSTemplate instanceof TTSTemplateItem){
                    $this->data['ttsTemplates'][] = $TTSTemplate->getData();
                }
            }
        }
    }


    /**
     * @desc 添加TemplateSlot
     * @param string $slotKey 槽位名称
     * @param string $slotValue 槽位值
     **/
    public function addTTSTemplate($TTSTemplate){
        if($TTSTemplate instanceof TTSTemplateItem){
            $this->data['ttsTemplates'][] = $TTSTemplate->getData();
        }
    }


    /**
     * @desc 清除话术模板的槽位信息
     **/
    public function clearTTSTemplates(){
        $this->data['ttsTemplates'] = [];
    }

    /**
     * @desc 获取数据
     * @return array 
     **/
    public function getData(){
        return $this->data;
    }
}
