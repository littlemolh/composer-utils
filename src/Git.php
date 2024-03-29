<?php

// +----------------------------------------------------------------------
// | Little Mo - Tool [ WE CAN DO IT JUST TIDY UP IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2021 http://ggui.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: littlemo <25362583@qq.com>
// +----------------------------------------------------------------------

namespace littlemo\utils;

use littlemo\utils\core\LUtilsException;

class Git
{

    private static $check = false;

    /**
     * 构造函数
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-07-06
     * @version 2021-07-06
     * @param string $token
     */
    public function __construct($token = '')
    {
        if ($token) {
            $this->check($token);
        }
    }

    /**
     * 鉴权
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-04-16
     * @version 2021-04-16
     * @param string $token 本地设置的token
     * @return boolean
     */
    public function check($token = '')
    {
        if (self::$check == true) {
            return;
        }
        if (empty($token)) {
            throw new LUtilsException('请先设置token');
        }

        //获取请求者携带token
        $requestToken = $_GET['token'] ?? $_SERVER['HTTP_X_GITEE_TOKEN'] ?? $_SERVER['HTTP_X_GITLAB_TOKEN'] ?? 'k';

        if ($token != $requestToken) {
            throw new LUtilsException('token匹配不成功');
        }
        self::$check = true;
        return $this;
    }

    /**
     * 拉取代码
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-04-16
     * @version 2021-04-16
     * @param string $path
     * @param string $exec
     * @return void
     */
    public function pull($path = '..', $exec = 'git pull origin master')
    {
        $this->check();
        $exec = "cd $path && git config core.filemode false &&  $exec";
        return $this->doAction($exec);
    }
    /**
     * 一键推送
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-04-16
     * @version 2021-04-16
     * @param string $path      需要推送的文件或文件夹路径
     * @param string $commit    版本说明
     * @return void
     */
    public function push($path = '..', $commit = '')
    {
        // 1. 添加变更文件
        // 2. 添加版本说明
        // 3. 开始推送
        // 4. 记录推送反馈信息
        self::printOut('暂未开发');
    }


    /**
     * 执行脚本
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2022-01-10
     * @version 2022-01-10
     * @param string $action    脚本内容
     * @return boolean
     */
    private function doAction($action)
    {
        $this->check();

        exec($action, $out, $res);
        // self::setMessage($out);
        self::printOut($out);
        return true;
    }

    /**
     * 输出日志
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2022-01-10
     * @version 2022-01-10
     * @param string|array $out
     * @return 
     */
    public static function printOut($out)
    {
        // $logFile = "./log/" . date('Y-m-d') . ".txt";
        echo "<pre>\n";
        if (is_array($out)) {
            foreach ($out as $o) {
                // file_put_contents($logFile, "{$o}\n", FILE_APPEND);
                echo $o;
                echo "\n";
            }
        } else {
            echo $out;
            echo "\n";
        }
        echo "\n";
        echo "时间：" . date('Y-m-d H:i:s');
        echo "\n";
        echo "------------------------------------------------------------------------------------\n";
        echo "</pre>";
    }
}
