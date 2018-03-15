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
 * @desc BotMonitor类的测试类
 * 
 **/

require dirname(__FILE__) . "/../vendor/autoload.php";

use PHPUnit\Framework\TestCase;
use Baidu\Apm\BotMonitorsdk\BotMonitor;

/**
 * @desc BotMonitorTest类用于测试BotMonitor类
 */
class BotMonitorTest extends PHPUnit_Framework_TestCase{
	
	/**
     * @before
     */
    public function setupSomeFixtures()
    {
    	$request = '{"version":"v2.0","session":{"new":false,"sessionId":"0dc598c4-c72e-48a1-9e34-550bc63cd006","attributes":[]},"context":{"System":{"user":{"userId":"55188137","userInfo":{"location":{"geo":{"bd09ll":{"longitude":116.41650585765,"latitude":39.922589823265},"wgs84":{"longitude":116.40387397,"latitude":39.91488908},"bd09mc":{"longitude":12959567.403034,"latitude":4827021.8235075}}}}},"application":{"applicationId":"5ab85a14-29e0-6532-7f56-0b53c1e0d70b"}}},"request":{"query":{"type":"TEXT","original":"查个税","rewritten":"查个税"},"dialogState":"COMPLETED","determined":false,"intents":[{"name":"inquiry","score":100,"confirmationStatus":"NONE","slots":{"compute_type":{"name":"compute_type","value":"个税","values":["个税"],"score":0,"confirmationStatus":"NONE"}}}],"type":"IntentRequest","requestId":"cf9e4558cb664ed0993a4f3f5662efb7_1","timestamp":"1509502673"}}';

		$this->data = json_decode($request, true);

		$this->botMonitor = new BotMonitor($this->data);
		$this->botMonitor->setEnvironmentInfo(
		    file_get_contents(dirname(__FILE__)."/../../rsa_private_key.pem1"),0);
		$this->botMonitor->setMonitorEnabled(true);

    }

	/**
	 * @desc 用于测试整个BotMonitor监控流程
	 */
	function testRun(){
		$this->botMonitor->setPreEventStart();
		usleep(100 * 1000);
		$this->botMonitor->setPreEventEnd();

		$this->botMonitor->setPostEventStart();
		usleep(100 * 1000);
		$this->botMonitor->setPostEventEnd();

		$this->botMonitor->setPostEventStart();
		usleep(100 * 1000);
		$this->botMonitor->setPostEventEnd();

		$this->botMonitor->setEventStart();
		usleep(100 * 1000);
		$this->botMonitor->setEventEnd();

		$this->botMonitor->setDeviceEventStart();
		usleep(100 * 1000);
		$this->botMonitor->setDeviceEventEnd();

		$this->botMonitor->setOprationTic("event1");
		usleep(100 * 1000);
		$this->botMonitor->setOprationToc("event1");

		//test error use
		$this->botMonitor->setOprationTic("event2");
		usleep(100 * 1000);
		$this->botMonitor->setOprationToc("event3");

		$this->botMonitor->setAppName("shoubai");
		$this->botMonitor->setPackageName("shoubai");
		$this->botMonitor->setDeepLink("shoubai");
		$this->botMonitor->setAudioUrl("www.baidu.com");


		$responseJson = '{"version":"2.0","context":{"intent":{"name":"inquiry","confirmationStatus":"NONE","slots":{"compute_type":{"name":"compute_type","value":"个税","confirmationStatus":"NONE","score":0}},"score":100}},"session":{"attributes":{"key_2":"value_2","key_1":"value_1"}},"response":{"needDetermine":false,"fallBack":false,"outputSpeech":{"type":"SSML","text":"","ssml":"java-sdk您的税前工资是多少呢?"},"reprompt":{"outputSpeech":{"type":"SSML","text":"","ssml":"java-sdk您的税前工资是多少呢?"}},"resource":{"entities":null},"card":{"type":"txt","content":"您的税前工资是多少呢?","url":"www:......","anchorText":"链接文本","cueWords":["您的税前工资是多少呢?"]},"directives":[{"type":"Dialog.ElicitSlot","updatedIntent":{"name":"inquiry","confirmationStatus":"NONE","slots":{"compute_type":{"name":"compute_type","value":"个税","confirmationStatus":"NONE","score":0}},"score":100},"slotToElicit":"monthlysalary"}],"shouldEndSession":false}}';


		$uploadJsonData = '{"serviceData":{"requestId":"cf9e4558cb664ed0993a4f3f5662efb7_1","query":"查个税","reason":null,"deviceId":null,"requestType":"IntentRequest","userId":"55188137","intentName":"inquiry","sessionId":"0dc598c4-c72e-48a1-9e34-550bc63cd006","location":{"bd09ll":{"longitude":116.41650585765,"latitude":39.922589823265},"wgs84":{"longitude":116.40387397,"latitude":39.91488908},"bd09mc":{"longitude":12959567.403034,"latitude":4827021.8235075}},"slotToElicit":"monthlysalary","shouldEndSession":false,"outputSpeech":{"type":"SSML","text":"","ssml":"java-sdk您的税前工资是多少呢?"},"audioUrl":"www.baidu.com","appInfo":{"appName":"shoubai","packageName":"shoubai","deepLink":"shoubai"},"responseTime":715,"timestamp":1509514183,"sysEvent":{"preEventList":{"preEvent0":103},"postEventList":{"postEvent0":101,"postEvent1":104},"eventCostTime":101,"deviceEventName":null,"deviceEventCostTime":null},"usrEvent":{"event1":102,"event2":0,"event3":0}}}';

		$this->botMonitor->setResponseData($responseJson);
		$ret = $this->botMonitor->uploadData();
	}

}
