<?php

namespace littlemo\utils\core;


/**
 * 微信支付异常抛出
 * @description
 * @example
 * @author LittleMo 25362583@qq.com
 * @since 2022-04-29
 * @version 2022-04-29
 */
class LUtilsException extends \Exception
{

    private $data = null;
    /**
     * DbException constructor.
     * @param string    $message
     * @param array     $config
     * @param string    $sql
     * @param int       $code
     */
    public function __construct($message = "", $code = 0, $data = [])
    {
        parent::__construct($message, (int)$code);

        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }
}
