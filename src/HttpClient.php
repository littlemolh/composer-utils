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
 * Http Client
 */

class HttpClient
{

    /**
     * http 报文设置
     */
    static $headers = [];

    /**
     * cURL允许执行的最长秒数
     */
    static $connectTimeout = 60;

    /**
     * cURL允许响应的最长秒数
     */
    static $socketTimeout = 60;
    /**
     * headers
     */
    static $conf = [];

    /**
     * HttpClient
     * @param array $headers HTTP header
     */
    public function __construct($headers = [], $connectTimeout = 60000, $socketTimeout = 60000, $conf = [])
    {
        self::$headers = self::buildHeaders($headers);
        self::$connectTimeout = $connectTimeout;
        self::$socketTimeout = $socketTimeout;
        self::$conf = $conf;
    }

    /**
     * 连接超时
     * @param int $s 秒
     */
    public function setConnectionTimeoutInMillis($s)
    {
        self::$connectTimeout = $s;
    }

    /**
     * 响应超时
     * @param int $s 秒
     */
    public function setSocketTimeoutInMillis($s)
    {
        self::$socketTimeout = $s;
    }

    /**
     * 配置
     * @param array $conf
     */
    public function setConf($conf)
    {
        self::$conf = $conf;
    }

    /**
     * 请求预处理
     * @param resource $ch
     */
    public function prepare($ch)
    {
        foreach (self::$conf as $key => $value) {
            curl_setopt($ch, $key, $value);
        }
    }

    /**
     * @param  string $url
     * @param  array $body HTTP POST BODY
     * @param  array $param HTTP URL
     * @param  array $headers HTTP header
     * @return array
     */
    public function post($url, $body = [], $params = [], $headers = [], $cert = [])
    {
        return $this->request($url, $body, $params, $headers, 'POST', $cert);
    }

    /**
     * @param  string $url
     * @param  array $bodys HTTP POST BODY
     * @param  array $param HTTP URL
     * @param  array $headers HTTP header
     * @return array
     */
    public function multi_post($url, $bodys = [], $params = [], $headers = [])
    {
        $url = $this->buildUrl($url, $params);
        $headers = array_merge(self::$headers, self::buildHeaders($headers));

        $chs = [];
        $result = [];
        $mh = curl_multi_init();
        foreach ($bodys as $body) {
            $ch = curl_init();
            $chs[] = $ch;
            $this->prepare($ch);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($body) ? http_build_query($body) : $body);
            curl_setopt($ch, CURLOPT_TIMEOUT, self::$socketTimeout);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::$connectTimeout);
            curl_multi_add_handle($mh, $ch);
        }

        $running = null;
        do {
            curl_multi_exec($mh, $running);
            usleep(100);
        } while ($running);

        foreach ($chs as $ch) {
            $content = curl_multi_getcontent($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $result[] = array(
                'code' => $code,
                'content' => $content,
            );
            curl_multi_remove_handle($mh, $ch);
        }
        curl_multi_close($mh);

        return $result;
    }

    /**
     * @param  string $url
     * @param  array $params HTTP URL
     * @param  array $headers HTTP header
     * @return array
     */
    public function get($url, $params = [], $headers = [])
    {
        return $this->request($url, [], $params, $headers, 'GET');
    }

    /**
     * 发起请求，自动识别是post还是get
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-09-29
     * @version 2021-09-29
     * @param [type] $url
     * @param array $body
     * @param array $params
     * @param array $headers
     * @return void
     */
    public function request($url, $body = [], $params = [], $headers = [], $type = 'GET', $cert = [])
    {
        $url = $this->buildUrl($url, $params);
        $headers = array_merge(self::$headers, self::buildHeaders($headers));

        $ch = curl_init();
        $this->prepare($ch);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ($type == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($body) ? http_build_query($body) : $body);
        }

        if (!empty($cert)) {
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            //证书文件请放入服务器的非web目录下
            curl_setopt($ch, CURLOPT_SSLCERTTYPE, $cert['cert_type']);
            curl_setopt($ch, CURLOPT_SSLCERT, $cert['cert']);
            curl_setopt($ch, CURLOPT_SSLKEYTYPE, $cert['key_type']);
            curl_setopt($ch, CURLOPT_SSLKEY, $cert['key']);
        }

        curl_setopt($ch, CURLOPT_TIMEOUT, self::$socketTimeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::$connectTimeout);
        $content = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $error_des = curl_error($ch);

        curl_close($ch);
        return array(
            'code' => $code,
            'content' => $content,
            'error_des' => $error_des,
        );
    }

    /**
     * 构造 header
     * @param  array $headers
     * @return array
     */
    private static function buildHeaders($headers)
    {
        $result = [];
        foreach ($headers as $k => $v) {
            $result[] = sprintf('%s:%s', $k, $v);
        }
        return $result;
    }

    /**
     * 
     * @param  string $url
     * @param  array $params 参数
     * @return string
     */
    public function buildUrl($url, $params)
    {
        if (!empty($params)) {
            $str = http_build_query($params);
            return $url . (strpos($url, '?') === false ? '?' : '&') . $str;
        } else {
            return $url;
        }
    }

    /**
     * 数组转XML
     *
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2021-09-15
     * @version 2021-09-15
     * @param array $data
     * @return string
     */
    public static function array_to_xml($data)
    {
        $xml = '<xml>';
        foreach ($data as $key => $val) {
            is_numeric($key) && $key = "item id=\"$key\"";
            $xml    .=  "<$key>";
            $xml    .=  (is_array($val) || is_object($val)) ? static::array_to_xml($val) : $val;
            list($key,) = explode(' ', $key);
            $xml    .=  "</$key>";
        }
        $xml .= '</xml>';
        return $xml;
    }
}
