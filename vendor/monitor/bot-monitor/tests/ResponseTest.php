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
 * @desc Response类的测试类
 */


require dirname(__FILE__) . "/../vendor/autoload.php";
use PHPUnit\Framework\TestCase;
use Baidu\Apm\BotMonitorsdk\Model\Request;
use Baidu\Apm\BotMonitorsdk\Model\Response;

class ResponseTest extends PHPUnit_Framework_TestCase{
	
	/**
     * @before
     */
    public function setupSomeFixtures()
    {
        $responseStr = '{"version":"2.0","context":{"intent":{"name":"inquiry","confirmationStatus":"NONE","slots":{"compute_type":{"name":"compute_type","value":"个税","confirmationStatus":"NONE","score":0}},"score":100}},"session":{"attributes":{"key_2":"value_2","key_1":"value_1"}},"response":{"needDetermine":false,"fallBack":false,"outputSpeech":{"type":"SSML","text":"","ssml":"java-sdk您的税前工资是多少呢?"},"reprompt":{"outputSpeech":{"type":"SSML","text":"","ssml":"java-sdk您的税前工资是多少呢?"}},"resource":{"entities":null},"card":{"type":"txt","content":"您的税前工资是多少呢?","url":"www:......","anchorText":"链接文本","cueWords":["您的税前工资是多少呢?"]},"directives":[{"type":"Dialog.ElicitSlot","updatedIntent":{"name":"inquiry","confirmationStatus":"NONE","slots":{"compute_type":{"name":"compute_type","value":"个税","confirmationStatus":"NONE","score":0}},"score":100},"slotToElicit":"monthlysalary"}],"shouldEndSession":false}}';
        $responseData = json_decode($responseStr,true);
        $this->response = new Response($responseData);	
    }

    public function testGetSlotName()
    {
        $this->assertEquals($this->response->getSlotName(),"monthlysalary");
    }
}
