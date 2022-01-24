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

class Time
{
    /**
     * 获取微秒时间戳
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2022-01-22
     * @version 2022-01-22
     * @return float
     */
    static public function microtime()
    {
        list($m, $s) = explode(' ', microtime());
        return $s + $m;
    }


    /**
     * 指定时间的当天开始和结束时间
     *
     * @description
     * @author LittleMo 25362583@qq.com
     * @version 2020-10-27
     * @param string|int $time   指定时间戳或日期
     * @return array 
     */
    static public function today($time = null, &$beginTime, &$endTime)
    {
        $time = $time ? (is_numeric($time) ? $time : strtotime($time)) : time();
        $beginTime = strtotime(date("Y-m-d 00:00:00", $time));
        $endTime = strtotime(date("Y-m-d 23:59:59", $time));
        return [$beginTime, $endTime];
    }
    /**
     * 指定时间的本周开始和结束时间
     *
     * @description
     * @author LittleMo 25362583@qq.com
     * @version 2020-10-27
     * @param string|int $time   指定时间戳或日期
     * @param int        $s      指定每周从那一天开始，0代表周日
     * @return array 
     */
    static public function thisWeek($time = null, $s = 1, &$beginTime = 0, &$endTime = 0)
    {
        $time = $time ? (is_numeric($time) ? $time : strtotime($time)) : time();
        $w = date('w', $time);
        $p = $w == 0 ? 6 : (($w == $s ? $s : $w) - $s);
        $beginTime = strtotime(date('Y-m-d', ($time - $p * 24 * 3600)));
        $endTime = $beginTime + 7 * 24 * 3600 - 1;
        return [$beginTime, $endTime];
    }

    /**
     * 指定时间的本月开始和结束时间
     *
     * @description
     * @author LittleMo 25362583@qq.com
     * @version 2020-10-27
     * @param string|int $time   指定时间戳或日期
     * @return array 
     */
    static public function thisMonth($time = null, &$beginTime = 0, &$endTime = 0)
    {
        $time = $time ? (is_numeric($time) ? $time : strtotime($time)) : time();
        $beginTime = strtotime(date("Y-m-01 00:00:00", $time));
        $endTime = strtotime(date("Y-m-01 00:00:00", strtotime("+1 month", $time))) - 1;
        return [$beginTime, $endTime];
    }
    /**
     * 指定时间的前一天开始和结束时间
     *
     * @description
     * @author LittleMo 25362583@qq.com
     * @version 2020-10-27
     * @param string|int $time   指定时间戳或日期
     * @return array 
     */
    static public function yesterday($time = null, &$beginTime = 0, &$endTime = 0)
    {
        $time = $time ? (is_numeric($time) ? $time : strtotime($time)) : time();
        $beginTime = strtotime(date("Y-m-d 00:00:00", strtotime("-1 day", $time)));
        $endTime = strtotime(date("Y-m-d 23:59:59", strtotime("-1 day", $time)));
        return [$beginTime, $endTime];
    }
    /**
     * 指定时间的前一周的当天开始和结束时间
     *
     * @description
     * @author LittleMo 25362583@qq.com
     * @version 2020-10-27
     * @param string|int $time   指定时间戳或日期
     * @return array 
     */
    static public function todayLastWeek($time = null, &$beginTime = 0, &$endTime = 0)
    {
        $time = $time ? (is_numeric($time) ? $time : strtotime($time)) : time();
        $beginTime = strtotime(date("Y-m-d 00:00:00", strtotime("last week", $time)));
        $endTime = strtotime(date("Y-m-d 00:00:00")) - 1;
        return [$beginTime, $endTime];
    }

    /**
     * 指定时间的前一周的开始和结束时间
     *
     * @description
     * @author LittleMo 25362583@qq.com
     * @version 2020-10-27
     * @param string|int $time   指定时间戳或日期
     * @param int        $s      指定每周从那一天开始，0代表周日
     * @return array 
     */
    static public function lastWeek($time = null, $s = 1, &$beginTime = 0, &$endTime = 0)
    {
        static::thisWeek($time, $s, $beginTime);
        $beginTime = strtotime(date("Y-m-d 00:00:00", strtotime("last week", $beginTime)));
        $endTime = $beginTime + 7 * 24 * 3600 - 1;
        return [$beginTime, $endTime];
    }
    /**
     * 指定时间的前一月的开始和结束时间
     *
     * @description
     * @author LittleMo 25362583@qq.com
     * @version 2020-10-27
     * @param string|int $time   指定时间戳或日期
     * @return array 
     */
    static public function lastMonth($time = null, &$beginTime = 0, &$endTime = 0)
    {
        $time = $time ? (is_numeric($time) ? $time : strtotime($time)) : time();
        $beginTime = strtotime(date("Y-m-01 00:00:00", strtotime("last month", $time)));
        $endTime = strtotime(date("Y-m-01 00:00:00")) - 1;
        return [$beginTime, $endTime];
    }
}