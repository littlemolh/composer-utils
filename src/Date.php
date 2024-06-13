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

/**
 * 日期相关
 * @description
 * @example
 * @author LittleMo 25362583@qq.com
 * @since 2024-06-13
 * @version 2024-06-13
 */
class Date
{

    /**
     * 获取指定年份的月份列表
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2024-06-13
     * @version 2024-06-13
     * @param string $year      年份（YYYY）
     * @param bool $overflow    是否返回未来日期
     * @return array
     */
    public static function monthsByYear($year = '', $overflow = false): array
    {
        $year = $year ?: date("Y");

        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $date = $year . '-' . sprintf("%02d", $i);
            $start_date = $date . '-01 00:00:00';
            if (strtotime($start_date) > time() && !$overflow) {
                break;
            }
            $end_date = date("Y-m-d H:i:s", strtotime('+1 month', strtotime($start_date)) - 1);
            $months[] = compact('date', 'start_date', 'end_date');
        }
        arsort($months);
        $months = array_values($months);
        return $months;
    }

    /**
     * 获取指定月份的日期列表
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2024-06-13
     * @version 2024-06-13
     * @param string $month 月份（YYYY-mm）
     * @param bool $overflow    是否返回未来日期
     * @return array
     */
    public static function daysByMonth($month = '', $overflow = false): array
    {
        $month = $month ?: date("Y-m");
        if (is_numeric($month)) {
            $month = date("Y-m", $month);
        }
        $day =  $month . '-01';
        $days = [];
        do {
            $date = $day;
            $start_date = $date . ' 00:00:00';
            $end_date = date("Y-m-d H:i:s", strtotime('+1 day', strtotime($start_date)) - 1);
            $days[] = compact('date', 'start_date', 'end_date');
            # code...
            $day = date("Y-m-d", strtotime('+1 day', strtotime($day)));
            if (strtotime($day) <= time() && !$overflow) {
                break;
            }
        } while (date("Y-m", strtotime($day)) == $month);
        arsort($days);
        $days = array_values($days);
        return $days;
    }
}
