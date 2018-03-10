# dueros/bot-sdk Notice Analyse

https://github.com/dueros/bot-sdk

## PHP Notice: Undefined index: xxx

### Notice跟踪

* 运行Notice：
  ```Shell
  root@localhost:~/zengjf/bae/vendor/dueros/bot-sdk/samples/personal_income_tax# ./post-part.sh part/launch.php 
  PHP Notice:  Undefined index: request in /root/zengjf/bae/vendor/dueros/bot-sdk/tools/genUsData.php on line 106
  PHP Notice:  Undefined index: query in /root/zengjf/bae/vendor/dueros/bot-sdk/tools/genUsData.php on line 110
  PHP Notice:  Undefined index: bot_name in /root/zengjf/bae/vendor/dueros/bot-sdk/tools/genUsData.php on line 114
  PHP Notice:  Undefined index: intent in /root/zengjf/bae/vendor/dueros/bot-sdk/tools/genUsData.php on line 118
  PHP Notice:  Undefined index: session in /root/zengjf/bae/vendor/dueros/bot-sdk/tools/genUsData.php on line 142
  xargs: curl: No such file or directory
  ```
* `cat /root/zengjf/bae/vendor/dueros/bot-sdk/tools/genUsData.php`
  ```PHP
  function genUsDataV2($usData, $sendData){
      $usData['request']['type'] = 'IntentRequest';
      if($sendData['type']) {
          $usData['request']['type'] = $sendData['type'];
      }
  
      if($sendData['request']) {
          $usData['request'] = array_merge($usData['request'], $sendData['request']);
      }
  
      if($sendData['query']) {
          $usData['request']['query']['original'] = $sendData['query'];
      }
  
      if($sendData['bot_name']) {
          $usData['context']['system']['bot']['botId'] = $sendData['bot_name'];
      }
  
      $intent = $sendData['intent'];
      if($intent) {
          //trick
          if($intent[0] === null) {
              $intent = [$intent];
          }
  
          $arr = [];
          foreach($intent as $item) {
              $i = [
                  'name' => $item['name'],
                  'slots' => [],
              ];
  
              foreach($item['slots'] as $slot) {
                  $i['slots'][$slot['name']] = $slot;
              }
  
              $arr[] = $i;
          }
  
          $usData['request']['intents'] = $arr;
      }
  
      $session = $sendData['session'];
      if($session) {
          $usData['session']['attributes'] = $session;
      }
  
      return $usData;
  }
  ```
* Grep genUsDataV2
  ```Shell
  root@localhost:~/zengjf/bae# grep genUsDataV2 * -r
  vendor/dueros/bot-sdk/tools/gen_us_v2.php:    print json_encode(genUsDataV2($usData, $sendData));
  vendor/dueros/bot-sdk/tools/genUsData.php:function genUsDataV2($usData, $sendData){
  ```
* `cat vendor/dueros/bot-sdk/tools/gen_us_v2.php`
  ```PHP
  if(php_sapi_name()=='cli'){
      $file = $argv[1];
      if(!$file) {
          exit(1);
      }
  
      $filename = $file;
  
      if(!file_exists($filename)) {
          echo "?[m~V~G浠朵?[m~M瀛~X?[m~\?[m\n";
          exit(1);
      }
  
      $sendData = require $filename;
  
      $template = dirname($filename) . '/template-v2.json';
      if(!file_exists($template)) {
          echo "娌℃~\~I?[m~I惧~H皌emplate-v2.json\n";
          exit(1);
      }
  
      $usData = json_decode(file_get_contents($template), true);
      print json_encode(genUsDataV2($usData, $sendData));
  }
  ```
* cat part/template-v2.json
  ```JSON
  {
      "version":"2.0",
      "session": {
          "new": true,
          "sessionId": "sessionId",
          "attributes":{}
      },
      "context":{
          "System": {
              "application": {
                  "applicationId": "sample_personal_tax"
              }
          }
      },
      "request": {
  
      }
  }
  ```

## Notice分析
* `cat vendor/dueros/bot-sdk/tools/gen_us_v2.php`
  ```PHP
  if(php_sapi_name()=='cli'){
      $file = $argv[1];
      if(!$file) {
          exit(1);
      }
  
      $filename = $file;
  
      if(!file_exists($filename)) {
          echo "?[m~V~G浠朵?[m~M瀛~X?[m~\?[m\n";
          exit(1);
      }
  
      $sendData = require $filename;
  
      $template = dirname($filename) . '/template-v2.json';
      if(!file_exists($template)) {
          echo "娌℃~\~I?[m~I惧~H皌emplate-v2.json\n";
          exit(1);
      }
  
      $usData = json_decode(file_get_contents($template), true);
      print_r($usData);
      print_r($sendData);
      print json_encode(genUsDataV2($usData, $sendData));
      print "\r\n";
  }
  ```
* `cat part/launch.php`
  ```PHP
  <?php
  return [
      'type' => 'LaunchRequest',
  ];
  ?>
  ```
* `root@localhost:~/zengjf/bae/vendor/dueros/bot-sdk/samples/personal_income_tax# ./post-part.sh part/launch.php`
  ```
  Array
  (
      [version] => 2.0
      [session] => Array
          (
              [new] => 1
              [sessionId] => sessionId
              [attributes] => Array
                  (
                  )
  
          )
  
      [context] => Array
          (
              [System] => Array
                  (
                      [application] => Array
                          (
                              [applicationId] => sample_personal_tax
                          )
  
                  )
  
          )
  
      [request] => Array
          (
          )
  
  )
  Array
  (
      [type] => LaunchRequest
  )
  PHP Notice:  Undefined index: request in /root/zengjf/bae/vendor/dueros/bot-sdk/tools/genUsData.php on line 106
  PHP Notice:  Undefined index: query in /root/zengjf/bae/vendor/dueros/bot-sdk/tools/genUsData.php on line 110
  PHP Notice:  Undefined index: bot_name in /root/zengjf/bae/vendor/dueros/bot-sdk/tools/genUsData.php on line 114
  PHP Notice:  Undefined index: intent in /root/zengjf/bae/vendor/dueros/bot-sdk/tools/genUsData.php on line 118
  PHP Notice:  Undefined index: session in /root/zengjf/bae/vendor/dueros/bot-sdk/tools/genUsData.php on line 142
  {"version":"2.0","session":{"new":true,"sessionId":"sessionId","attributes":[]},"context":{"System":{"application":{"applicationId":"sample_personal_tax"}}},"request":{"type":"LaunchRequest"}}
  root@localhost:~/zengjf/bae/vendor/dueros/bot-sdk/samples/personal_income_tax# 
  ```
* 到这里基本上确定不是软件运行问题，基本上将问题集中在：`xargs: curl: No such file or directory`，需要安装`curl`：`apt-get install curl`；
* 运行结果如下：
  ```
  root@localhost:~/zengjf/bae/vendor/dueros/bot-sdk/samples/personal_income_tax# ./post-part.sh part/launch.php 
  PHP Notice:  Undefined index: request in /root/zengjf/bae/vendor/dueros/bot-sdk/tools/genUsData.php on line 106
  PHP Notice:  Undefined index: query in /root/zengjf/bae/vendor/dueros/bot-sdk/tools/genUsData.php on line 110
  PHP Notice:  Undefined index: bot_name in /root/zengjf/bae/vendor/dueros/bot-sdk/tools/genUsData.php on line 114
  PHP Notice:  Undefined index: intent in /root/zengjf/bae/vendor/dueros/bot-sdk/tools/genUsData.php on line 118
  PHP Notice:  Undefined index: session in /root/zengjf/bae/vendor/dueros/bot-sdk/tools/genUsData.php on line 142
  {"version":"2.0","context":{},"session":{"attributes":{}},"response":{"directives":[],"shouldEndSession":false,"card":{"type":"list","list":[{"title":"title","content":"content","url":"http:\/\/www","image":"http:\/\/www.png"}]},"resource":null,"outputSpeech":{"type":"PlainText","text":"鎵€寰楃◣涓烘偍鏈嶅姟"},"reprompt":null}}root@localhost:~/zengjf/bae/vendor/dueros/bot-sdk/samples/personal_income_tax# 
  ```

