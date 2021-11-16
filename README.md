
littlemo utils
===============

[![Total Downloads](https://poser.pugx.org/littlemo/utils/downloads)](https://packagist.org/packages/littlemo/utils)
[![Latest Stable Version](https://poser.pugx.org/littlemo/utils/v/stable)](https://packagist.org/packages/littlemo/utils)
[![Latest Unstable Version](https://poser.pugx.org/littlemo/utils/v/unstable)](https://packagist.org/packages/littlemo/utils)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D7.0-8892BF.svg)](http://www.php.net/)
[![License](https://poser.pugx.org/littlemo/utils/license)](https://packagist.org/packages/littlemo/utils)

### 介绍
php常用工具库

#### 软件架构
基于ThinkPHP


### 安装教程

composer.json
```json
{
    "require": {
        "littlemo/utils": "~1.0.0"
    }
}
```

### 使用说明

#### 统计单位时间内同一个IP请求次数

>需要安装`redis`扩展,并启动 `redis` 服务

##### 示例代码


```php
use littlemo\utils\RequestRate;
$config=[
    'prefix'=>'ip',//缓存前缀
    'time'=>'60',//单位时间（s）
    'maxCount'=>'30',//单位时间最大请求次数
    'cache'=>[
        'type' => 'redis',//缓存类型，目前仅支持redis
        'host' => '127.0.0.1',//缓存服务连接地址
        'port' => '6379',//缓存服务端口
        'select' => 0,//redis库号，一般取值范围（0-15）
    ]
]

//实例化对象
$requestRate = new RequestRate($config);

//获取错误信息
$error = $requestRate->$getMessage();

//初始化缓存服务,实例化对象时回自动初始化缓存服务
$requestRate->setCacheObj();

//验证器
$result = $requestRate->check();
if($result === false){
    echo $requestRate->$getMessage();
}else{
    echo '未达到请求次数上限';
}

```

#### 自动更新Git

> 仅支持gitee

##### 示例代码


```php
use littlemo\utils\Git;

$token = 'XXXXXXX';

//实例化对象
$Git = new Git($token);

//验证器
$error = $Git->check($token);

/**
* 拉取代码
* @param string $path   执行脚本相对路径；默认：'..'
* @param string $exec   执行脚本；默认：'git pull origin master'
*/
$Git->pull($path, $exec);

```
- 拉取代码的日志会直接在页面输出

#### 下载文件

##### 示例代码


```php
use littlemo\utils\Download;

/**
* 下载文件
* @param string $file      文件路径（文件所在磁盘的绝对路径）
* @param string $filename  带后缀的(自定义)文件名称
*/

Download::file($file, $filename);

```

#### Common

```php
use littlemo\utils\Common;

```

#### 生成随字符串

##### 示例代码


```php

$string = Common::createNonceStr($length , $enum ,  $dict);

```

参数说明
| 参数   | 类型   | 默认          | 说明                                 |
| :----- | :----- | :------------ | :----------------------------------- |
| length | int    | 32            | 制作随机字符串长度                   |
| enum   | Array  | ['0','a','A'] | 字符串类型选择（0=0-9，a=a-z,A=A-Z） |
| dict   | string |               | 初始字符库（自定义字符库）           |


#### 制作签名

##### 示例代码


```php

$string = Common::createSign($params, $params_disorder, $type);

```

参数说明
| 参数            | 类型   | 必填 | 默认 | 说明                                                 |
| :-------------- | :----- | :--- | :--- | :--------------------------------------------------- |
| params          | Array  | Y    |      | 需要按照字段名的ASCII 码从小到大排序（字典序）的参数 |
| params_disorder | Array  | N    | []   | 无需排序操作的参数                                   |
| type            | string | N    | md5  | 加密方式(可选：md5,sha1)                             |



### 参与贡献

1.  littlemo


### 特技

- 统一、精简
