# DuerOS Understand SDK API

* https://dueros.baidu.com/didp/doc/dueros-bot-platform/dbp-sdk/Common_Functions_php_markdown
* https://github.com/dueros/bot-sdk

## API汇总

* 打开技能：`addLaunchHandler`
* 关闭技能：`addSessionEndedHandler`
* NLU交互功能：`addIntentHandler`
  * 回问：`$this->nlu->ask('xxx')`
  * 获取指定槽位，用于检查槽位是否正常：`getSlot`
  * 确认指定槽位：`setConfirmSlot`
  * 确认意图：`setConfirmIntent`
  * 代理意图、槽位：`setDelegate`
  * 重复信息：`reprompt`
* 文本卡片：`TextCard`
* 标准卡片：`StandardCard`
* 列表卡片：`ListCard`、`ListCard`
* 图片卡片：`ImageCard`
* 音频：`Play`、`Stop`
* 拦截器：`addIntercept`
  * 预处理：`preprocess`
  * 尾处理：`postprocess`
