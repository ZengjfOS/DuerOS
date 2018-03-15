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
 * @desc BotMonitor is a sdk that can collect and analyze application data, 
 *       it's can help you find application problems easily,to facilitate you to improve 
 *       the application and improve user experience.at present, It can provide performance 
 *       analysis, user behavior statistics,etc. if you want to detail,please visit 
 *       http://dueros.baidu.com.
 * @author liudesheng01@baidu.com
 **/
namespace Baidu\Apm\BotMonitorsdk;

use \Baidu\Apm\BotMonitorsdk\Model\Response;
use \Baidu\Apm\BotMonitorsdk\Model\Request;

class BotMonitor {
    
    /**
     * DuerOS rrequest for Bot. instance of Request
     **/
    private $request;
    /**
     * The result of Bot returning to DuerOS. instance of Response
     **/
    private $response;

    /**
     * request start time
     **/
    private $requestStartTime;
    /**
     * request end time
     **/
    private $requestEndTime;    

    /**
     * event(intent handler) start time
     **/
    private $eventStartTime;
    /**
     * event(intent handler) execution time
     **/
    private $eventCostTime;

    /**
     * device event start time
     **/
    private $deviceEventStartTime;
    /**
     * device event execution time
     **/
    private $deviceEventCostTime;

    /**
     * preprocess event list
     **/
    private $preEventList;
    /**
     * postprocess event list
     **/
    private $postEventList;

    /**
     * custom events list
     **/
    private $userEventList;
    /**
     * list of whether the custom events are called in pairs
     **/
    private $isEventMakePair;

    /**
     * open application info,application name,or packagename,or deeplink
     **/
    private $appInfo;

    /**
     * the open audio url,like music url.etc
     **/
    private $audioUrl;

    /**
     * certificate of data
     **/
    private $certificate;

    /***
     * openssl private key.
     */
    private $privateKey;

    /*
     * your bot current status.0 represent debug, 1 represent online.
     */
    private $environment;

    /*
     * is bot monitor enabled
     */
    private $enabled;

    /**
     * @param Array   $postData
     * @param String  $privateKeyContent
     * @return null
     **/
    public function __construct($postData) {
        $this->requestStartTime = $this->getMillisecond();
        $this->eventCostTime    = 0.0;
        $this->deviceEveCostTime= 0.0;
        $this->preEventList     = [];
        $this->postEventList    = [];
        $this->userEventList    = [];
        $this->appInfo['appName'] = '';
        $this->appInfo['packageName'] = '';
        $this->appInfo['deepLink'] = '';
        $conf = BotMonitorConfig::getConfig();
        $this->uploadUrl    = $conf['uploadUrl'];
        $this->sdkType      = $conf['sdkType'];
        $this->sdkVersion   = $conf['sdkVersion'];
        $this->enabled      = true;
        $this->request = new Request($postData);
    }

    /*
     * @param String $privateKey your openssl private key content
     * @param String $environment you bot current status.0:debug 1:online
     * @return null
     */
    public function setEnvironmentInfo($privateKey, $environment)
    {
        $this->environment = $environment;
        $this->privateKey = $privateKey;
        $this->certificate = new Certificate($privateKey);
    }

    /*
     * @param bool $enabled is bot monitor sdk enable
     * @return null
     */
    public function setMonitorEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @param Array $responseData response return to dueros
     * @return null
     **/
    public function setResponseData($responseData) {
        if($this->isShouldDisable()) {
            return;
        }
    	$this->requestEndTime = $this->getMillisecond();
        if (is_string($responseData)) {
            $responseArray = json_decode($responseData, true);
            $this->response = new Response($responseArray);
        } else {
            $this->response = new Response($responseData);
        }
    }

    /**
     * @desc when preprocess start,record current timestamp
     * @return null
     **/
    public function setPreEventStart() {
        if($this->isShouldDisable()) {
            return;
        }
        $keyStr = 'preEvent' . strval(count($this->preEventList));
        $this->preEventList[$keyStr] = $this->getMillisecond();
    }

    /**
     * @desc when preprocess ends,calculate current pre-event execution time
     * @return null
     **/
    public function setPreEventEnd() {
        if($this->isShouldDisable()) {
            return;
        }
        $keyArr = array_keys($this->preEventList);
        $lastKey = end($keyArr);
        $this->preEventList[$lastKey] = $this->getMillisecond() - $this->preEventList[$lastKey];
    }

    /**
     * @desc when postprocess start,record current timestamp
     * @return null
     **/
    public function setPostEventStart() {
        if($this->isShouldDisable()) {
            return;
        }
        $keyStr = 'postEvent' . count($this->postEventList);
        $this->postEventList[$keyStr] = $this->getMillisecond();
    }

    /**
     * @desc when postprocess ends,calculate current post-event execution time
     * @return null
     **/
    public function setPostEventEnd() {
        if($this->isShouldDisable()) {
            return;
        }
        $keyArr = array_keys($this->postEventList);
        $lastKey = end($keyArr);
        $this->postEventList[$lastKey] = $this->getMillisecond() - $this->postEventList[$lastKey];
    }

    /**
     * @desc when intent handler start,record current timestamp
     * @return null
     **/
    public function setEventStart() {
        if($this->isShouldDisable()) {
            return;
        }
        $this->eventStartTime = $this->getMillisecond();
    }

    /**
     * @desc when intent handler ends,calculate current event execution time
     * @return null
     **/
    public function setEventEnd() {
        if($this->isShouldDisable()) {
            return;
        }
        $this->eventCostTime = $this->getMillisecond() - $this->eventStartTime;
    }

    /**
     * @desc when device event start,record current timestamp
     * @return null
     **/
    public function setDeviceEventStart()
    {
        if($this->isShouldDisable()) {
            return;
        }
        $this->deviceEveStartTime = $this->getMillisecond();
    }

    /**
     * @desc when device event ends,calculate device event execution time
     * @return null
     **/
    public function setDeviceEventEnd()
    {
        if($this->isShouldDisable()) {
            return;
        }
        $this->deviceEveCostTime = $this->getMillisecond() - $this->deviceEveStartTime;
    }

    /**
     * @desc define your own performance events,and call this funcion at the beginning of the event
     * Notice this function call must appear in pairs with setOprationToc
     * @param string $taskName a name used to uniquely identify as user event
     * @return null
     **/
    public function setOprationTic($taskName) {
        if($this->isShouldDisable()) {
            return;
        }
        if ($taskName) {
            $currTime = $this->getMillisecond();
            $this->userEventList[$taskName] = $currTime;
            $this->isEventMakePair[$taskName] = false;
        }
    }

    /**
     * @desc stop timing for an event,call this funcion at the end of the event
     * Notice this function call must appear in pairs with setOprationTic
     * @param string $taskName a name used to uniquely identify as user event
     * @return null
     **/
    public function setOprationToc($taskName) {
        if($this->isShouldDisable()) {
            return;
        }
        if ($taskName && isset($this->userEventList[$taskName])) {
            $oldTime = $this->userEventList[$taskName];
            $currTime = $this->getMillisecond();
            $costTime = 0;
            if ($oldTime) {
                $costTime = $currTime - $oldTime;
            }
            $this->userEventList[$taskName] = $costTime;
            $this->isEventMakePair[$taskName] = true;
        }
    }

    /**
     * @desc set open application name
     * @param string $appName application name
     * @return null
     **/
    public function setAppName($appName){
        if($this->isShouldDisable()) {
            return;
        }
        if($appName) {
            $this->appInfo['appName'] = $appName;
        }
    }

    /**
     * @desc set package name
     * @param string $packageName packageName
     * @return null
     **/
    public function setPackageName($packageName){
        if($this->isShouldDisable()) {
            return;
        }
        if($packageName) {
            $this->appInfo['packageName'] = $packageName;
        }
    }

    /**
     * @desc set deepLink
     * @param string $deepLink 
     * @return null
     **/
    public function setDeepLink($deepLink){
        if($this->isShouldDisable()) {
            return;
        }
        if($deepLink) {
            $this->appInfo['deepLink'] = $deepLink;
        }
    }

    /**
     * @desc set audio url
     * @param string $url audio url
     * @return null
     **/
    public function setAudioUrl($audioUrl){
        if($this->isShouldDisable()) {
            return;
        }
        if($audioUrl) {
            $this->audioUrl = $audioUrl;
        }
    }

    /**
     * @desc get the current timestamp
     * @return timestamp
     **/
    public function getMillisecond() {
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }


    /**
     * @desc data aggregation
     * @return Array, data ready to send
     **/
    public function collectData(){
        if($this->isShouldDisable()) {
            return;
        }
        $botId      = $this->request->getBotId();
        $requestId  = $this->request->getRequestId();
        $query      = $this->request->getQuery();
        $reason     = $this->request->getReson();
        $deviceId   = $this->request->getDeviceId();
        $requestType= $this->request->getType();
        $userId     = $this->request->getUserId();
        $intentName = $this->request->getIntentName();
        $sessionId  = $this->request->getSessionId();
        $location   = $this->request->getLocation();

        $slotName           = $this->response->getSlotName();
        $shouldEndSession   = $this->response->getShouldEndSession();
        $outputSpeech       = $this->response->getOutputSpeech();
        $reprompt           = $this->response->getReprompt();
        
        $sysEvent = array(
            'preEventList'        => $this->preEventList,
            'postEventList'       => $this->postEventList,
            'eventCostTime'       => $this->eventCostTime,
            'deviceEventCostTime' => $this->deviceEventCostTime,
        );

        foreach($this->userEventList as $key=>$value){
            if (!$this->isEventMakePair[$key]) {
                $this->userEventList[$key] = 0;
            }
        }

        $timestamp  = time();
        $retData =['serviceData' => [
            "sdkType"           => $this->sdkType,
            "sdkVersion"        => $this->sdkVersion,
            'requestId'         => $requestId,
            'query'             => $query,
            'reason'            => $reason,
            'deviceId'          => $deviceId,
            'requestType'       => $requestType,
            'userId'            => $userId,
            'intentName'        => $intentName,
            'sessionId'         => $sessionId,
            'location'          => $location,
            'slotToElicit'      => $slotName,
            'shouldEndSession'  => $shouldEndSession,
            'outputSpeech'      => $outputSpeech,
            'reprompt'          => $reprompt,
            'audioUrl'          => $this->audioUrl,
            'appInfo'           => $this->appInfo,
            'requestStartTime'  => $this->requestStartTime,
            'requestEndTime'  	=> $this->requestEndTime,
            'timestamp'         => $timestamp,

            'sysEvent'          => $sysEvent,
            'usrEvent'          => $this->userEventList,
            ]
        ];
        return $retData;
    }

    /**
     * @desc upload data to server
     * @return String, http response
     **/
    public function uploadData()
    {
        if($this->isShouldDisable()) {
            return;
        }
        $botId      = $this->request->getBotId();
        $retData    = $this->collectData();
        $pkversion  = '';
        if ($this->environment == 0) {
            $pkversion = 'debug';
        } else {
            $pkversion = 'online';
        }
        $timestamp  = time();
        $jsonData   = json_encode($retData, JSON_UNESCAPED_UNICODE);
        $base64Data = base64_encode($jsonData);
        $at = $this->certificate->getSig($base64Data.$botId.$timestamp.$pkversion);
        //if config info error, don't upload data
        if ($at == false || is_null($pkversion)
            || $pkversion === '') {
            return false;
        }

        $request = [
            'url'=>$this->uploadUrl,
            'method'=>'post',
            "headers"=>[
                'SIGNATURE:'.$at,
                'botId:'.$botId,
                'timestamp:'.$timestamp,
                'pkversion:'.$pkversion
            ],
            'data'=>$base64Data
        ];

        $res = $this->postWithoutWait($request);
        return $res;
    }

    /**
     * @desc send data with params
     * @param array $request http request data info
     * @return null
     **/
    public function postWithoutWait($request)
    {
        $url = $request['url'];
        $header = $request['headers'];
        $params = $request['data'];
        $parts=parse_url($url);
        $fp = fsockopen("ssl://" . $parts['host'],
            443, $errno, $errstr, 0.5);
        stream_set_blocking($fp, false);
        stream_set_timeout($fp, 0.5);
        $out = "POST ".$parts['path']." HTTP/1.1\r\n";
        $out.= "Host: ".$parts['host']."\r\n";
        $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out.= "Content-Length: ".strlen($params)."\r\n";
        foreach ($header as $key => &$val) {
            $out.= $val ."\r\n";
        }
        $out.= "Connection: Close\r\n\r\n";
        $out.= $params;
        fwrite($fp, $out);
        fclose($fp);
    }

    /*
     * @desc is should disable botmonitor
     * @return bool
     */
    public function isShouldDisable()
    {
        if (!isset($this->privateKey) || empty($this->privateKey)
            || !isset($this->environment) || !$this->enabled) {
            return true;
        }
        return false;
    }
}