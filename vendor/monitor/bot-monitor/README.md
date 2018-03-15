## FAQ
### 这是什么？
这是BotMonitor SDK，它可以帮助您收集和分析您开发的bot运行中产生的数据，帮助您实时查看应用运行状态，及时发现应用中存在的问题，提升用户体验。目前，BotMonitor提供应用性能分析、用户行为统计。使用BotMonitor，您可以方便的在自己的DBP平台查看Bot的用户量、会话量、请求量、QPS以及Session的相关统计数据指标。

### 我该怎么使用？

#### 准备工作

##### 公钥上传
为了保证Bot的数据安全，我们后端会验证Bot的身份。所以在使用BotMonitor提供的数据统计功能之前，你需要在本地使用openssl生成RSA公钥和私钥对，然后把公钥上传到DBP平台上和你的Bot关联起来。步骤如下:

1. 打开终端，执行`openssl genrsa -out rsa_private_key.pem 1024`命令生成私钥
2. 执行`openssl rsa -in rsa_private_key.pem -pubout -out rsa_public_key.pem`生成和私钥匹配的公钥。
3. 使用编辑器打开公钥文件，将内容上传到DBP平台对应的Bot空间。
4. 保存私钥文件，我们下面会用到。

注意，如果不进行公钥上传或者公钥版本配置的操作，你仍可以正常的开发和使用Bot，但是无法使用Bot的数据统计和Bot回调DuerOS的功能。

#### 在DuerOS Bot SDK里使用BotMonitor
DuerOS Bot SDK是一个帮助开发Bot的SDK，SDK里封装DuerOS协议，自动集成了BotMonitor对Bot运行中的数据统计功能。我们强烈建议你使用DuerOS Bot SDK开发度秘的Bot。使用DuerOS Bot SDK开发Bot有以下好处：

1. DuerOS Bot SDK会和DuerOS的协议一起升级，避免DuerOS对Bot的协议升级对你开发的Bot造成影响
2. DuerOS Bot SDK自动集成了BotMonitor，你无需在手动引入BotMonitor就能使用基础的数据统计功能。

关于如何使用DuerOS Bot SDK，请查看[DBP SDK安装及技能创建](https://developer.dueros.baidu.com/doc/dueros-bot-platform/dbp-sdk/Installation_php_markdown)。

如果你使用DuerOS Bot SDK开发的bot，你需要在你的Bot里配置环境信息,这里我们假设你把上面生成的rsa_public_key.pem文件内容赋值给$privateKey变量，假设你的Bot是在测试环境：

```
//$privateKey为私钥内容,0代表你的Bot在DBP平台debug环境，1或者其他整数代表online环境
$this->botMonitor->setEnvironmentInfo($privateKey, 0);
```

环境信息配置完成后，你需要打开BotMonitor数据采集上报开关(默认是开启的,你可以根据自己需求打开或者关闭),true代表打开，false代表关闭：

```
$this->botMonitor->setMonitorEnabled(true);
```

基础的数据统计功能已在DuerOS Bot SDK里帮你做好，你只需要关心以下两个函数，在自己关心的操作前后打点：

```
//自定义操作的打点统计，参数名是这个操作的唯一标识字符串，为了方便在平台上展示查看，请尽量使用有意义的名字。假如你的Bot里有个获取天气的函数public function weatherInfo($city='');，你想了解这个操作的性能，你可以把这个操作命名为get_weatherinfo_task:
//在获取天气的操作之前设置开始计时
$botMonitor->setOprationTic('get_weatherinfo_task')
//$this->weatherInfo('beijing');
//在获取天气的操作结束之后结束计时
$botMonitor->setOprationToc('get_weatherinfo_task');
```
这样你就能方便的看到基础的数据统计和你打点的事件性能统计。

#### 直接使用BotMonitor
如果你没有使用DuerOS Bot SDK开发bot，你需要在bot中手动引入BotMonitor，使用BotMonitor提供的接口在Bot里打点，打点的方法如下：
	
	1. 初始化BotMonitor信息。使用request初始化一个BotMonitor,request是array类型，是bot协议里的结构体。

    ```
    $botMonitor = new BotMonitor($request);
    
    ```
    
    2.初始化环境信息，打开数据采集上报开关。这里我们假设你把上面生成的rsa_public_key.pem文件内容赋值给$privateKey变量，你的Bot是在测试环境：
    ```
	//$privateKey为私钥内容,0代表你的Bot在DBP平台debug环境，1或者其他整数代表online环境
	$this->botMonitor->setEnvironmentInfo($privateKey, 0);
	//true打开数据采集上报开(默认是打开的,你也可以手动关闭)
	$this->botMonitor->setMonitorEnabled(true);
	```
    
	 3. 对bot中的系统事件进行打点统计
		下面不同事件的打点调用了不同的函数，方便你在开发者平台上区分自己打点的不同事件。
	
	```
	//intent是bot协议里的意图，你需要在处理intent的回调函数前后打点
	//intent回调函数处理开始前调用
	$botMonitor->setEventStart();
	//do something
	//intent回调函数处理结束调用，注意：如果你的回调函数里有多个return的地方，需要在每一个return操作之前调用事件结束函数，保证在每个请求结束都能调到setEventEnd函数
	$botMonitor->setEventEnd();
	
	//如果你订阅了端上触发的事件，比如端上设置闹铃成功、端上音乐播放，对端上事件打点统计，你就可以在自己的端上事件处理的回调里前后打点
	//端上事件callback处理开始调用
	$botMonitor->setDeviceEventStart();
	//do something
	//端上事件callback处理结束调用
	$botMonitor->setDeviceEventStart();
	```
	
	
	4. 对自定义操作打点统计

	```
	//自定义操作的打点统计，参数名是这个操作的唯一标识字符串，为了方便在平台上展示查看，请尽量使用有意义的名字。假如你的Bot里有个获取天气的函数public function weatherInfo($city='');你想了解这个操作的性能，你可以把这个操作命名为get_weatherinfo_task:
	//在获取天气的操作之前设置开始计时
	$botMonitor->setOprationTic('get_weatherinfo_task')
	//$this->weatherInfo('beijing');
	//在获取天气的操作结束之后结束计时
	$botMonitor->setOprationToc('get_weatherinfo_task');
	```
	
	5. 设置Bot返回数据并上报相关统计数据

	```
	//在请求返回时，您需要设置返回的数据responseData，responseData是一个数组，是返回给dueros的应答，假如定义这个请求返回的变量为$responseData,我们可以通过下面代码设置返回数据:
	$botMonitor->setResponseData($responseData);
	```
	6. 把数据上报到后端
	
	```
	$botMonitor->uploadData();
	```


#### 预留的功能

这部分的统计是预留功能，目前在DBP平台暂时还不能看到相关数据。如果你对相关的统计结果比较感兴趣。建议你在Bot里手动加入相关的打点：	
如果你的Bot使用了打开APP的指令，你可以对打开的APP打点记录：

	```
	//设置打开的ApplicationName、packagename、deeplink
	$botMonitor->setAppName("手机百度");
	$botMonitor->setPackageName("com.baidu.test");
	$botMonitor->setDeepLink("http://www.baidu.com/");
	```
如果你的Bot使用了打开音频的指令，你可以对打开的音频进行记录：

	```
	//设置打开的音频链接
	$botMonitor->setAudioUrl("https://www.baidu.com/link?url=UuDVofVvZ78dcyLAB7wi47bp9OCXYXAblirMkd3wAKZZZSonnVOfAKu6OlqzDUVNcHkL2uNAbRI0IQjldD-3R_&wd=&eqid=bdb97034000031ca0000000259f17f91");	
	```

我们后续会根据大家打点统计的情况来决定是否在平台上增加展示对应的统计数据。
	

### 使用它会影响我的bot性能吗？
对性能的影响非常小，下面详细分析下原因。
BotMonitor中有两个频繁调用的函数：

1. 每次打点都需要调用的getMillisecond函数。

	getMillisecond函数获取时间戳是通过调用PHP函数microtime(底层调用gettimeofday)来实现的,这个函数返回结果精度在微秒级
在x86_64机器下，gettimeofday通过vsyscall(or vdso)直接读取时间(不用发送中断)，速度很快,理论上速度大概在几微秒
在i386体系的机器上，会使用system call，会处理软中断，加上PHP语言本身的影响，经过测试，getMillisecond函数执行时间在X86_64,ubuntu16.04,php7.1.7下不会超过5us(1s=1000 * 1000us)。

2. 上报数据接口postWithoutWait

	因为PHP语言本身不支持异步，所以这里采用write数据完成后立即返回，不等待server端响应来提高性能。
	
3. 压测结果

    经过我们对线上环境的Bot的压力测试，结论是BotMonitor会有小于100ms(代码计算耗时在10ms之内，大部分是网络写入耗时)左右的时间消耗。