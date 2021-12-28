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

class File
{

    /**
     * 获取真实路径并去除'./'和'../'
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-07-26
     * @version 2021-07-26
     * @param [type] $filename
     * @param string $split
     * @return string
     */
    public static function getRealPath($filename = '', $split = '/')
    {
        while (true) {
            if (FALSE === strpos($filename, $split . '.')) {
                break;
            }

            $filename = explode($split, $filename);

            foreach ($filename as $k => $f) {
                if (($k && $f == '') || $f == '.') {
                    unset($filename[$k]);

                    break;
                } elseif ($f == '..') {
                    unset($filename[$k]);

                    if (isset($filename[$k - 1]))

                        unset($filename[$k - 1]);

                    break;
                }
            }

            $filename = implode($split, $filename);
        }

        return $filename;
    }

    /**
     * 递归获取文件夹和文件列表
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-07-26
     * @version 2021-07-26
     * @param string $path  根目录路径
     * @param boolean $isManyDimensions 返回数据是否是多为数组
     * @return array
     */
    public static function scandirFolder($path, $isManyDimensions = true)
    {
        $list = [];
        $temp_list = scandir($path);
        foreach ($temp_list as $file) {
            if ($file != ".." && $file != ".") {
                if (is_dir($path . "/" . $file)) {
                    //子文件夹，进行递归
                    if ($isManyDimensions == true) {
                        $list[][$file] = self::scandirFolder($path . "/" . $file, $isManyDimensions);
                    } else {
                        $temp = self::scandirFolder($path . "/" . $file, $isManyDimensions);
                        foreach ($temp as $val) {
                            $list[] =  $val;
                        }
                    }
                } else {
                    //根目录下的文件
                    if ($isManyDimensions == true) {
                        $list[] = $file;
                    } else {
                        $list[] = $path . '/' . $file;
                    }
                }
            }
        }
        return $list;
    }

    /**
     * 下载远程文件
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-12-28
     * @version 2021-12-28
     * @param string $url       远程文件地址
     * @param string $filename  自定义文件名称
     * @return void
     */
    public static function grab($url, $filename = "")
    {
        if ($url == "") {
            return false;
        };
        if ($filename == "") {
            $ext = strrchr($url, ".");
            if (!in_array($ext, ['.gif', '.jpg', '.png'])) {
                $ext = '.jpg';
            }
            $filename = date("YmdHis") . $ext;
        }
        ob_start();
        readfile($url);
        $img = ob_get_contents();
        ob_end_clean();
        $size = strlen($img);
        $fp2 = @fopen($filename, "a");
        fwrite($fp2, $img);
        fclose($fp2);
        return [
            'path' => $filename,
            'size' => $size,
            'url' => $url,
            'ext' => $ext,
        ];
    }
}
