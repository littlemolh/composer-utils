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

class Tools
{

    /**
     * 制作随机字符串
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-09-15
     * @version 2021-09-15
     * @param int       $length     随机字符串长度
     * @param array     $enum       字符串类型选择:0=0-9，a=a-z,A=A-Z    
     * @param string    $dict       初始字符库(定义字符串)       
     * @return string
     */
    public static function createNonceStr($length = 32, $enum = ['0', 'a', 'A'],  $dict = '')
    {
        $base = [
            '0' => '0123456789',
            'a' => 'qwertyuiopasdfghjklzxcvbnm',
            'A' => 'QWERTYUIOPASDFGHJKLZXCVBNM',
        ];

        foreach ($enum as $val) {
            if (!empty($base[$val])) {
                $dict .= $base[$val];
            }
        }

        $str = '';
        while (strlen($str) < $length) {
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
                return sha1($string); //40 字符长度的十六进制数
                break;
            case 'SHA1':
                return sha1($string, true); //以 20 字符长度的原始二进制格式返回
                break;
            case 'MD5':
                return md5($string, true); //以 16 字符长度的原始二进制格式返回
                break;
            case 'md5':
            default:
                return md5($string); //以 32 字符的十六进制数形式返回散列值
        }
    }

    /**
     * 获取请求者真实IP地址
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-12-14
     * @version 2021-12-14
     * @return void
     */
    public static function getRealIp()
    {
        $ip = false;
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
            if ($ip) {
                array_unshift($ips, $ip);
                $ip = FALSE;
            }
            for ($i = 0; $i < count($ips); $i++) {
                if (!preg_match("^(10│172.16│192.168).", $ips[$i])) {
                    $ip = $ips[$i];
                    break;
                }
            }
        }
        return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
    }

    /**
     * 过滤字段
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-12-14
     * @version 2021-12-14
     * @param array $data       需要操作的数组
     * @param array $persist    需要保留的字段,null：忽略操作
     * @param array $unpersist  需要遗弃的字段,null：忽略操作
     * @return void
     */
    public static function filterField(&$data, $persist = null, $unpersist =  null)
    {
        if (is_object($data)) {
            $data = $data->toArray();
        }

        if (empty($data)) {
            return $data;
        }

        if ($persist !== null) {
            $data = array_intersect_key($data, array_flip($persist));
        }

        if ($unpersist !== null) {
            foreach ($unpersist as $val) {
                if (array_key_exists($val,  $data)) {
                    unset($data[$val]);
                }
            }
        }

        return $data;
    }

    /**
     * Undocumented function
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2022-03-11
     * @version 2022-03-11
     * @param array $pool 奖池概率
     * @param int $number 抽几次
     * @return array
     */
    public static function randomField(array $pool, $number = 1, &$d = [])
    {
        $l = 1;
        foreach ($pool as $val) {
            $temp =  strlen(substr(strrchr($val, "."), 1));
            if ($temp > $l) {
                $l = $temp;
            }
        }

        $multiplier = 1;
        for ($i = 0; $i < $l; $i++) {
            $multiplier *= 10;
        }

        $max = 0;
        foreach ($pool as $val) {
            $max += $val * $multiplier;
        }

        $d = [];
        while ($number > 0) {
            $hit = rand(1, $max);
            $temp = 0;
            foreach ($pool as $key => $val) {
                $temp += $val * $multiplier;
                if ($hit <= $temp) {
                    $d[] = $key;
                    break;
                }
            }
            --$number;
        }
        return $d;
    }

    /**
     * 比较两个版本号大小，
     * @description $version和$compare比较,结果：‘>’、‘<’或‘=’
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2022-05-24
     * @version 2022-05-24
     * @param string $version   当前版本名称
     * @param string $compare   需要比较的版本名称
     * @return string
     */
    public static function version($version, $compare)
    {
        $version_arr =  explode('.', $version);
        $compare_arr =  explode('.', $compare);
        foreach ($version_arr as $key => $val) {
            if ($val == ($compare_arr[$key] ?? 0)) {
                continue;
            } elseif ($val  > ($compare_arr[$key] ?? 0)) {
                return '>';
            } else {
                return '<';
            }
        }
        if (count($version_arr) < count($compare_arr)) {
            for ($i = count($version_arr); $i < count($compare_arr); ++$i) {
                if ($compare_arr[$i] > 0) {
                    return '<';
                }
            }
        }
        return '=';
    }
}
