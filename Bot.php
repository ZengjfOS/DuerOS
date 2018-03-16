<?php
/**
 * @desc 
 **/

namespace Bot;
use \Baidu\Duer\Botsdk\Card\TextCard;
use \Baidu\Duer\Botsdk\Directive\AudioPlayer\Play;
use \LibMQTT\Client;

class Bot extends \Baidu\Duer\Botsdk\Bot{
    /**
     * @param null
     * @return null
     **/
    public function __construct($postData = []) {
        parent::__construct($postData);

        $this->addHandler('LaunchRequest', function(){
            $this->waitAnswer();
            $card = new TextCard('Welcome To WebColor.');
            return [
                'card' => $card,
                'outputSpeech' => 'Welcome To WebColor.',
            ];

        });

        $this->addIntentHandler('displayColor', function(){
            if($this->request->isDialogStateCompleted()) {

                $server="zengjf.mqtt.iot.gz.baidubce.com";                   // change if necessary
                $port = 1883;                                                // change if necessary
                $username = "zengjf/sz_monitor_room";                        // set your username
                $password = "QE0BHFvFnIkBRIaJtPYzo3m/63Esv5fzzMr9tYVOsHo=";  // set your password
                $client_id = "DeviceIdr55";                                  // make sure this is unique for connecting to sever - you could use uniqid()
                $mqtt = new Client($server, $port, $client_id);
                $mqtt->setAuthDetails($username, $password);
                $result = $mqtt->connect(true);
                if ($result) {
                    $mqtt->publish("test-iot-sub", $this->getSlot('color'), 0);
                    $mqtt->close();
                }

                $card = new TextCard('OK');
                $this->endDialog();

                return [
                    'card' => $card,
                    'outputSpeech' => 'OK',
                ];
            }

            if(!$this->getSlot('color')) {
                $card = new TextCard('which color are you want?');
                $this->nlu->ask('color');
                return [
                    'card' => $card,
                    'reprompt' => 'which color are you want?',
                    'outputSpeech' => 'which color are you want?',
                ];
            }else{
                $this->nlu && $this->nlu->setDelegate();
            }
        });
    }
}
