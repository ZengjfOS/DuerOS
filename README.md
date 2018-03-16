# DuerOS

* [官方网站](https://dueros.baidu.com/)
* [开发者平台](https://dueros.baidu.com/open)
* [官方视频](https://dueros.baidu.com/didp/news/technicalclass)

## Demo Show

![./docs/image/WebColor.gif](./docs/image/WebColor.gif)

## Docs

[DuerOS学习、分析、测试、操作记录文档](./docs/README.md)

## 自动pull、merge

主要是因为BAE是git仓库，如果重新创建了BAE服务器，就需要重新合成一次开发过的代码，所以制作了这个脚本自动拷贝：  
https://github.com/ZengjfOS/DuerOS/tree/AutoMerge

这里还是需要输入账户密码的，如果不想输入账户密码，可以考虑直接将账户密码放在URL链接中，不过注意账户密码的安全性。

## PHP基础开发环境

由于一直在尝试一些新的开发环境，以及开发过程中的遇到的一些问题，避免多次重复操作，所以用一个分支专门管理PHP基础开发环境：  
https://github.com/ZengjfOS/DuerOS/tree/WebColor

前面的AutoMerge脚本也就pull这个分支内容。
