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
 * @desc IntentRequest类的测试类
 */

use PHPUnit\Framework\TestCase;
use \Baidu\Apm\BotMonitorsdk\Model\Response;
use \Baidu\Apm\BotMonitorsdk\Model\Request;

class IntentRequestTest extends PHPUnit_Framework_TestCase{
	
	/**
     * @before
     */
    public function setupSomeFixtures()
    {
        $this->data = json_decode(file_get_contents(dirname(__FILE__).'/json/intent_request.json'), true);
        $this->request = new Request($this->data);
    }	

	/**
	 * @desc 测试getData方法
	 */
	function testGetData(){
		$this->assertEquals($this->request->getData(), $this->data);
	}

	/**
	 * @deprecated   sdk更新后测试 
	 * @desc 测试getAudioPlayerContext方法
	 */
	function testGetAudioPlayerContext(){

	}

	/**
	 * @desc 测试getType方法
	 */
	function testGetType(){
		$this->assertEquals($this->request->getType(), 'IntentRequest');
	}

	/**
	 * @desc 测试getUserId方法
	 */
	function testGetUserId(){
		$this->assertEquals($this->request->getUserId(), 'userId');
	}

	/**
	 * @desc 测试getQuery方法
	 */
	function testGetQuery(){
		$this->assertEquals($this->request->getQuery(), '所得税查询');
	}

	/**
	 * @desc 测试getBotId方法
	 */
	function testGetBotId(){
		$this->assertEquals($this->request->getBotId(), '5ab85a14-29e0-6532-7f56-0b53c1e0d70b');
	}

	/**
	 * @desc 测试getRequestId方法
	 */
	function testGetRequestId(){
		$this->assertEquals($this->request->getRequestId(), '1e335011-80cf-49cc-93c8-dc689826bb46');
	}

	/**
	 * @desc 测试getReson方法
	 */
	function testGetReson(){
		$this->assertEquals($this->request->getReson(), null);
	}

	/**
	 * @desc 测试getIntentName方法
	 */
	function testGetIntentName(){
		$this->assertEquals($this->request->getIntentName(), "intentName");
	}

	/**
	 * @desc 测试getSessionId方法
	 */
	function testGetSessionId(){
		$this->assertEquals($this->request->getSessionId(), "4c627098-5663-4c28-96e4-ece15c2b43c0");
	}
}
