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
 * 制作海报
 *
 * @description
 * @example
 * @author LittleMo 25362583@qq.com
 * @since 2021-12-30
 * @version 2022-03-17
 */
class Banner
{

    /**
     * 图片宽度 
     */
    static $width = 0;

    /**
     * 图片高度
     */
    static $height = 0;

    /**
     * 图片资源
     */
    static $image = null;

    /**
     * 图片类型
     */
    static $type = null;

    /**
     * 错误信息
     */
    static $msg = [];
    static function create($width, $height, $type = 'png')
    {
        self::$width = $width;
        self::$height = $height;
        self::$type = $type;

        // self::$image = @imagecreate($width, $height) or self::$msg[] = '创建图像资源失败';
        self::$image = imagecreatetruecolor($width, $height);
        if (self::$type == 'png') {
            imagesavealpha(self::$image, true);
        }
    }

    /**
     * 设置背景颜色
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-12-30
     * @version 2021-12-30
     * @return void
     */
    static function setBgColor($red = 255, $green = 255, $blue = 255, $alpha = 127)
    {

        if (self::$type == 'png') {
            $color = imagecolorallocatealpha(self::$image,  $red, $green, $blue, $alpha); //设置为透明背景
        } else {
            $color = imagecolorallocate(self::$image, $red, $green, $blue); // 为真彩色画布创建白色背景，
        }
        imagefill(self::$image, 0, 0, $color);
        // imageColorTransparent(self::$image, $color);
    }
    /**
     * 设置颜色
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-12-30
     * @version 2021-12-30
     * @return void
     */
    static function setColor($red = 255, $green = 255, $blue = 255)
    {

        return   imagecolorallocate(self::$image, $red, $green, $blue);

        // self::$image = imagecreatetruecolor(self::$blueg_w, self::$blueg_h); // 背景图片 
        // $color = imagecolorallocate(self::$image, 202, 201, 201); // 为真彩色画布创建白色背景，再设置为透明 
        // imagefill(self::$image, 0, 0, $color);
        // imageColorTransparent(self::$image, $color);
    }


    /**
     * 添加文本
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-12-30
     * @version 2021-12-30
     * @param string    $text    UTF-8 编码的文本字符串。
     * @param float     $size         字体的尺寸，单位：点（磅）。
     * @param string    $xy         由 x，y 所表示的坐标定义了第一个字符的基本点（大概是字符的左下角）。这和 imagestring() 不同，其 x，y 定义了第一个字符的左上角。例如 "top left" 为 0, 0。
     * @param int       $color     颜色索引。使用负的颜色索引值具有关闭防锯齿的效果。见 imagecolorallocate()。
     * @param string    $fontfile  是想要使用的 TrueType 字体的路径。
     * @param float     $angle    角度制表示的角度，0 度为从左向右读的文本。更高数值表示逆时针旋转。例如 90 度表示从下向上读的文本。
     * @return void
     */
    static function addText($text, $size, $xy = null,  $color = null, $fontfile = __DIR__ . '/font/WeiRuanYaHei-1.ttf', $angle = 0)
    {
        if (!$color) {
            $color = self::setColor(0, 0, 0);
        }
        if ($xy == null) {
            $x = 0;
            $y = 0;
        } else {
            $arr = explode(',', $xy);
            $x = $arr[0];
            $y = $arr[1] ?? 0;
        }
        imagettftext(self::$image, $size, $angle, $x, $y + $size, $color, $fontfile, $text);
    }

    /**
     * 载入图片
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-12-31
     * @version 2021-12-31
     * @param string $src_im	需要载入的图片
     * @param string $dst_xy	设定需要载入的图片在新图中的x,y坐标
     * @param string $src_xy	设定载入图片要载入的区域x,y坐标
     * @param string $dst_wh	设定载入的原图的'宽,高'度（在此设置缩放）
     * @param string $src_wh	原图要载入的'宽,高'度
     * @param string $pct	    图像合并程度，取值 0-100 ，当 pct=0 时，实际上什么也没做，反之完全合并。
     * @param string $smooth	是否采用平滑（精细）算法函数[imagecopyresampled]
     * @return void
     */
    static function addImage($src_im, $dst_xy = null, $src_xy = null, $dst_wh = null, $src_wh = null,  $pct = 100, $smooth = true)
    {


        if (($imagegInfo = getimagesize($src_im)) === false) {
            self::$msg[] = '[addImage]图片类型读取失败';
        }
        switch ($imagegInfo['mime']) {
            case 'jpg':
            case 'jpeg':
            case 'image/jpeg':
                $imageage = imagecreatefromjpeg($src_im);
                break;
            case 'png':
            case 'image/png':
                $imageage = imagecreatefrompng($src_im);
                break;
        }
        if ($dst_xy == null) {
            $dst_x = 0;
            $dst_y = 0;
        } else {
            list($dst_x, $dst_y) = explode(',', $dst_xy);
        }
        if ($src_xy == null) {
            $src_x = 0;
            $src_y = 0;
        } else {
            list($src_x, $src_y) = explode(',', $src_xy);
        }
        if ($src_wh == null) {
            $src_w = imagesx($imageage);
            $src_h = imagesy($imageage);
        } else {
            list($src_w, $src_h) = explode(',', $src_wh);
        }
        if ($dst_wh == null) {
            $dst_w = $src_w;
            $dst_h = $src_h;
        } else {
            list($dst_w, $dst_h) = explode(',', $dst_wh);
        }
        if ($pct !== 100) {
            if (imagecopymerge(self::$image, $imageage, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct) === false) {
                self::$msg[] = '[addImage]图片合并失败';
            };
        } else {
            if ($smooth == false) {
                //函数在所有GD版本中有效，但其缩放图像的算法比较粗糙。
                if (imagecopyresized(self::$image, $imageage, $dst_x, $dst_y, $src_x, $src_y,  $dst_w, $dst_h, $src_w, $src_h) === false) {
                    self::$msg[] = '[addImage]图片合并失败';
                };
            } else {
                //其像素插值算法得到的图像边缘比较平滑，但该函数的速度比ImageCopyResized()慢。
                if (imagecopyresampled(self::$image, $imageage, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h) === false) {
                    self::$msg[] = '[addImage]图片合并失败';
                };
            }
        }
    }

    /**
     * 输出图像
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-12-31
     * @version 2021-12-31
     * @param integer $quality  范围从 0（最差质量，文件更小）到 100（最佳质量，文件最大）。默认为 IJG 默认的质量值（大约 75）。
     * @return void
     */
    static function output($quality = 75)
    {
        switch (self::$type) {
            case 'png': //以PNG格式输出图像
                header('Content-type:image/png');
                imagepng(self::$image);
                break;
            case 'jpeg': //以jpeg格式输出图像
            case 'jpg': //以jpg格式输出图像
                header('Content-type:image/jpeg');
                imagejpeg(self::$image, null, $quality);
                break;
        }

        imagedestroy(self::$image);
    }

    /**
     * 保存图像
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-12-31
     * @version 2021-12-31
     * @param integer $quality  范围从 0（最差质量，文件更小）到 100（最佳质量，文件最大）。默认为 IJG 默认的质量值（大约 75）。
     * @return void
     */
    static function save($path = '', $quality = 75)
    {
        switch (self::$type) {
            case 'png': //以PNG格式保存图像
                imagepng(self::$image, $path);
                break;
            case 'jpeg': //以jpeg格式保存图像
            case 'jpg': //以jpg格式保存图像
                imagejpeg(self::$image, $path, $quality);
                break;
            case 'gif': //以gif格式保存图像
                imagegif(self::$image, $path);
                break;
            case 'wbmp': //以wbmp格式保存图像
                imagewbmp(self::$image, $path);
                break;
        }

        imagedestroy(self::$image);
    }

    static function getMessage()
    {
        return self::$msg;
    }
}
