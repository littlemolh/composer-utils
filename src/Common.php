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

class Common
{

    /**
     * 制作随机字符串
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-09-15
     * @version 2021-09-15
     * @param int   $length     随机字符串长度
     * @param array $enum       字符串类型选择:0=0-9，a=a-z,A=A-Z    
     * @param array $dict       初始字符库       
     * @return string
     */
    public static function createNonceStr($length = 32, $enum = ['0', 'a', 'A'],  $dict = '')
    {
        $base = [
            '0' => '0123456789',
            'a' => 'qwertyuiopasdfghjklzxcvbnm',
            'A' => 'QWERTYUIOPASDFGHJKLZXCVBNM',
        ];

        $dict = '';
        foreach ($enum as $val) {
            if (!empty($base[$val])) {
                $dict .= $base[$val];
            }
        }

        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($dict, rand(0, (strlen($dict) - 1)), 1);
        }

        return $str;
    }

    /**
     * 制作签名
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-09-15
     * @version 2021-09-15
     * @param array $params             需要按照字段名的ASCII 码从小到大排序（字典序）的参数
     * @param array $params_disorder    无需排序操作的参数
     * @param array $type               加密方式
     * @return string
     */
    public static function createSign($params, $params_disorder = [], $type = 'md5')
    {
        ksort($params);
        $string = '';
        $signature = '';
        foreach ($params as $key => $val) {
            if (!empty($val)) {
                $string .= (!empty($string) ? '&' : '') . $key . '=' . $val;
            }
        }
        foreach ($params_disorder as $key => $val) {
            if (!empty($val)) {
                $string .= (!empty($string) ? '&' : '') . $key . '=' . $val;
            }
        }

        switch ($type) {
            case 'sha1':
                $signature =  sha1($string);
                break;
            case 'md5':
            case 'MD5':
            default:
                $signature =  MD5($string);
        }
        return $signature;
    }
}
