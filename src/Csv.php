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
 * 导出csv格式文件 或直接输出
 *
 * @icon fa fa-circle-o
 */
class Csv
{
    private $tileArray = [];
    private $dataArray = [];
    public function __construct(array $tileArray = [])
    {
        $this->tileArray = $tileArray;
    }

    /**
     * 表头数据
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2024-06-13
     * @version 2024-06-13
     * @param array $title  有序数组
     * @return self
     */
    public function title(array $title): self
    {
        $this->tileArray = $title;
        return $this;
    }
    /**
     * 主体内容
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2024-06-13
     * @version 2024-06-13
     * @param array $data      二维有序数组
     * @return self
     */
    public function data(array $data): self
    {
        $this->dataArray[] = $data;
        return $this;
    }
    /**
     * 写入文件
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2024-06-13
     * @version 2024-06-13
     * @param string $filePath  文件路径
     * @return void
     */
    public function save($filePath)
    {
        $file = fopen($filePath, 'w');

        fwrite($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // 转码 防止乱码(比如微信昵称)
        //   写入表头
        // dump($this->tileArray);
        fputcsv($file, $this->tileArray);
        // 关闭文件句柄

        // 写入主体内容
        foreach ($this->dataArray as $row) {
            fputcsv($file, $row);
        }
        // 关闭文件句柄
        fclose($file);
    }

    /**
     * 直接输出到浏览器下载
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2024-06-13
     * @version 2024-06-13
     * @param string $fileName  文件名称
     * @return void
     */
    public function flush($fileName = '')
    {
        $fileName = $fileName ?: date("YmdHi") . '.csv';
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 0);
        // ob_end_clean();
        ob_start();
        header("Content-Type: text/csv");
        header("Content-Disposition:attachment;filename=" . $fileName);
        $fp = fopen('php://output', 'w');
        fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF)); // 转码 防止乱码(比如微信昵称)
        fputcsv($fp, $this->tileArray);
        $index = 0;
        foreach ($this->dataArray as $item) {
            if ($index == 1000) {
                $index = 0;
                ob_flush();
                flush();
            }
            $index++;
            fputcsv($fp, $item);
        }
        ob_flush();
        flush();
        ob_end_clean();
    }

    /**
     * 表格值过滤
     * @param $value
     * @return string
     */
    public static function filterValue($value)
    {
        return "\t" . $value . "\t";
    }
}
