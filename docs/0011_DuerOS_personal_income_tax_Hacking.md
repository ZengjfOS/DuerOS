# DuerOS personal income tax Hacking

如下分析中可知整体软件设计框架，大体程序设计流程：
* `index.php`负责整体软件框架的运行流程调度，程序运作比较清晰；
  * 加载`Bot.php`；
  * 设置PHP返回数据类型；
  * 记录程序处理时间；
  * 将程序运行过程中的Log日志保存到日志文件中；
* `Bot.php`负责处理具体的事务；
  * 设置Log日志级别，存放位置；
  * 设置启动、关闭监听；
  * 设置槽监听处理；

## Source Code

https://github.com/dueros/bot-sdk/tree/master/samples/personal_income_tax

## Hacking Index.php

```PHP
...
require "Bot.php";                                      // 加载Bot.php
$tax = new Bot();                                       // 直接new出Bot类对象，PHP包名用\表示
if($_SERVER['REQUEST_METHOD'] == 'HEAD'){
    header('HTTP/1.1 204 No Content');
}
header("Content-Type: application/json");               // 返回值类型为json数据类型
//记录整体执行时间
/**
 *  public function markStart($key){
 *      if(!$key) {
 *          return; 
 *      }
 *      $this->timeSt[$key] = microtime(1);
 *  }
 */
$tax->log->markStart('all_t');
$ret = $tax->run();                                                 // 目前对这个$ret变量还是有点迷糊
/**
 *  public function markEnd($key){
 *      if(!isset($this->timeSt[$key])) {
 *          return; 
 *      }
 *      $start = $this->timeSt[$key];
 *      unset($this->timeSt[$key]);
 *      $this->data[$key] = intval(1000*(microtime(1) - $start));
 *  }
 */
$tax->log->markEnd('all_t');
//打印日志
//or 在register_shutdown_function增加一个执行函数
$tax->log->notice($tax->log->getField('url_t'));        // 将程序运行过程中记录的数据写入日志文件中
$tax->log->notice();
print $ret;
```

## Hacking Bot.php

```PHP
<?php
...
// 自动加载
require '../../../../../vendor/autoload.php';
// 导入包名
use \Baidu\Duer\Botsdk\Card\TextCard;
use \Baidu\Duer\Botsdk\Card\StandardCard;
use \Baidu\Duer\Botsdk\Card\ListCard;
use \Baidu\Duer\Botsdk\Card\ListCardItem;

// 继承类
class Bot extends \Baidu\Duer\Botsdk\Bot {
    // 计算个税的URL
    private static $url = "https://sp0.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?ie=utf-8&resource_id=28259&req_from=app&query=个税计算器";
    // 支持的个税查询种类
    private static $inquiry_type = array(
        '全部缴纳项目' => 'all',
        '养老' => 'yanglaoxian',
        '医疗' => 'yiliaoxian',
        '失业' => 'shiyexian',
        '工伤' => 'gongshangxian',
        '生育' => 'shengyuxian',
        '公积金' => 'gongjijin',
        '个税' => 'geshui',
    );
    // 目前阿拉丁支持96个城市
    private static $city = array(
        '北京','长春','成都','儋州','广安','贵阳','合肥','滨州','昌江黎族自治县','池州',
        '大同','广元','邯郸','衡阳','亳州','长沙','滁州','德州','广州','杭州',
        '黄山','嘉兴','荆门','晋中','昆明','临沧','洛阳','眉山','攀枝花','萍乡',
        '吉林','金华','九江','莱芜','临沂','马鞍山','牡丹江','平顶山','济南','济宁',
        '酒泉','兰州','六安','茂名','南充','平凉','青岛','琼中黎族苗族自治县','三亚','汕头',
        '石家庄','天津','威海','芜湖','清远','曲靖','上海','韶关','十堰','铜陵',
        '文昌','琼海','衢州','上饶','深圳','泰安','潍坊','温州','厦门','咸宁',
        '宣城','宜昌','乐山','云浮','漳州','重庆','珠海','西安','邢台','许昌',
        '鹰潭','岳阳','枣庄','肇庆','周口','驻马店','湘西土家苗族自治州','宿州','烟台','永州',
        '运城','张掖','郑州','舟山','淄博','葫芦岛',
    );
    /**
     * @param null
     * @return null
     * */
    public function __construct($postData = []) {
        //parent::__construct($postData, file_get_contents(dirname(__file__).'/../../src/privkey.pem'));
        //parent::__construct(file_get_contents(dirname(__file__).'/../../src/privkey.pem'));
        parent::__construct();
        // log对象
        $this->log = new \Baidu\Duer\Botsdk\Log([
            // 日志存储路径
            'path' => 'log/',
            // 日志打印最低输出级别
            'level' => \Baidu\Duer\Botsdk\Log::NOTICE,
        ]);
        // 记录这次请求的query
        $this->log->setField('query', $this->request->getQuery());
        //$this->addIntercept(new \Baidu\Duer\Botsdk\Plugins\DuerSessionIntercept());   // 不使用拦截器
        // 添加打开程序运行的监听处理函数
        $this->addLaunchHandler(function(){
            $card = new ListCard();
            $item = new ListCardItem();
            $item->setTitle('title')
                ->setContent('content')
                ->setUrl('http://www')
                ->setImage('http://www.png');
            $card->addItem($item);
            $this->waitAnswer();
            return [
                'card' => $card,
                //'outputSpeech' => '<speak>欢迎光临</speak>' 
                'outputSpeech' => '所得税为您服务',
            ];
        });

        // 添加关闭程序运行的监听处理函数
        $this->addSessionEndedHandler(function(){
            return null; 
        });

        // 在匹配到intent的情况下，首先询问月薪
        $this->addIntentHandler('personal_income_tax.inquiry', function() {
            if(!$this->getSlot('monthlysalary')) {
                $this->nlu->ask('monthlysalary');
                $card = new TextCard('您的税前工资是多少呢？');
                $card->addCueWords(['20000','10000']);
                return [
                    'card' => $card,
                    'reprompt' => '您的税前工资是多少呢？',
                    'resource' => [
                        'type' => 1,
                    ],
                ];
            }else if(!$this->getSlot('location')) {
                // 在存在monthlysalary槽位的情况下，首先验证monthlysalary槽位值是否合法，然后询问location槽位
                $ret = $this->checkMonthlysalary();
                if ($ret != null) {
                    return $ret;
                }
                $this->nlu->ask('location');
                $card = new StandardCard();
                $card->setTitle('title');
                $card->setContent('content');
                $card->setImage('http://www...');
                $card->setAnchor('http://www.baidu.com');
                return [
                    //'card' => new TextCard('您所在城市是哪里呢？'),
                    'card' => $card,
                    'outputSpeech' => '您所在城市是哪里呢？',
                ];
            }else if(!$this->getSlot('compute_type')) {
                // 在存在location槽位的情况下，首先验证location槽位是否在支持的城市列表中，然后询问compute_type槽位
                $ret = $this->checkLocation();
                if ($ret != null) {
                    return $ret;
                }
                $this->nlu->ask('compute_type');
                return [
                    'card' => new TextCard('请选择您要查询的个税种类')
                ];
            }else {
                // 都正常的情况下，就开始计算
                return $this->compute(); 
            }
        });
    }
    /**
     * @desc 工资合法性检查,非int类型以及小于等于0的值均不合法
     * @param null
     * @return null
     * */
    public function checkMonthlysalary() {
        $monthlysalary = $this->getSlot('monthlysalary');
        $value = intval($monthlysalary);
        if ($value <= 0) {
            $this->nlu->ask('monthlysalary');
            return [
                'card' => new TextCard('输入的工资不正确，请重新输入：')
            ];
        }
    }
    /**
     * @desc 城市合法性检查
     * @param null
     * @return null
     * */
    public function checkLocation() {
        // 判断是否在支持的城市列表中
        $location = $this->getSlot('location');
        if (!in_array($location, self::$city)) {
            $this->nlu->ask('location');
            return [
                'card' => new TextCard("该城市不存在，请重新选择城市：")
            ];
        }
    }
    /**
     * @desc 计算个税结果,在满足三槽位的情况下依次验证三槽位是否合法
     * @parma null
     * @return null
     * */
    public function compute() {
        // 验证月薪是否符合格式
        $ret = $this->checkMonthlysalary();
        if ($ret != null) {
            return $ret;
        }
        // location槽位存在的情况下，判断该城市是否存在
        $ret = $this->checkLocation();
        if ($ret != null) {
            return $ret;
        }
        // compute_type槽位存在的情况下，判断计算类型是否存在
        $compute_type = $this->getSlot('compute_type');
        if (!isset(self::$inquiry_type[$compute_type])) {
            $this->nlu->ask('compute_type');
            return [
                'card' => new TextCard("请重新选择查询的个税种类：")
            ];
        }
        $monthlysalary = intval($this->getSlot('monthlysalary'));
        $location = $this->getSlot('location');

        // 构造请求的url
        $url = self::$url 
            . '&monthlysalary=' . $monthlysalary 
            . '&location=' . $location 
            . '&compute_type=' . self::$inquiry_type[$compute_type];

        // 访问URL并记录时间
        $this->log->markStart('url_t');
        $res = file_get_contents($url);
        //$res = Utils::curlGet($url, 2000);
        $this->log->markEnd('url_t');

        // 解析json返回数据
        $data = json_decode($res, true);
        $pay_details = $data[data][0][resultData][tplData][pay_details];
        $views = '';
        if ($compute_type !== "个税" && $compute_type !== "全部缴纳项目" ) {
            foreach($pay_details as $pay_detail) {
                $views = $pay_detail[col1] 
                    . "：个人缴纳" . $pay_detail[col2_input] . "%=" . $pay_detail[col2_value] 
                    . "，单位缴纳" . $pay_detail[col3_input] . "%=" . $pay_detail[col3_value];
            }
        } else if ($compute_type == "个税") {
            $num = count($pay_details);
            $obj = $pay_details[$num - 1];
            $views .= $obj[col1] . ": " . $obj[col2_value];
        } else if ($compute_type == "全部缴纳项目") {
            $num = count($pay_details);
            for ($i = 0; $i < $num - 1; $i++) {
                $obj = $pay_details[$i];
                $views .= $obj[col1]
                    . "：个人缴纳" . $obj[col2_input] . "%=" . $obj[col2_value] 
                    . "，单位缴纳" . $obj[col3_input] . "%=" . $obj[col3_value]
                    . "\n";
            }
            $obj = $pay_details[$num - 1];
            $views .= $obj[col1] . ": " . $obj[col2_value];
        }
        return [
            'card' => new TextCard($views)
        ];
    }
}
```
