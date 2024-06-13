<?php

namespace littlemo\utils\rsa;

use littlemo\utils\core\LUtilsException;

/**
 * 私钥
 * @description
 * @example
 * @author LittleMo 25362583@qq.com
 * @since 2024-06-13
 * @version 2024-06-13
 */
class PrivateKey
{

    /**
     * OpenSSLAsymmetricKey 实例
     */
    protected $privateKey = null;

    public function __construct(string $privateKeyString, string $password = null)
    {

        $this->privateKey = $password ? openssl_pkey_get_private($privateKeyString, $password) : openssl_pkey_get_private($privateKeyString);

        if ($this->privateKey === false) {
            throw LUtilsException::InvalidPrivateKey();
        }
    }

    /**
     * 通过字符串解析私钥
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2024-06-13
     * @version 2024-06-13
     * @param string $privateKeyString
     * @param string|null $password
     * @return self
     */
    public static function fromString(string $privateKeyString, string $password = null): self
    {
        return new static($privateKeyString, $password);
    }
    /**
     * 通过文件串解析私钥
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2024-06-13
     * @version 2024-06-13
     * @param string $pathToPrivateKey
     * @param string|null $password
     * @return self
     */
    public static function fromFile(string $pathToPrivateKey, string $password = null): self
    {
        if (!file_exists($pathToPrivateKey)) {
            throw LUtilsException::FileDoesNotExist($pathToPrivateKey);
        }

        $privateKeyString = file_get_contents($pathToPrivateKey);

        return new static($privateKeyString, $password);
    }

    /**
     * 使用私钥加密数据
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2024-06-13
     * @version 2024-06-13
     * @param string $data          需要加密的数据
     * @param int $padding          加密类型( OPENSSL_PKCS1_PADDING,OPENSSL_NO_PADDING)
     * @return string
     */
    public function encrypt(string $data, int $padding = OPENSSL_PKCS1_PADDING): string
    {
        openssl_private_encrypt($data, $decrypted, $this->privateKey, $padding);

        return $decrypted;
    }

    /**
     * 使用私钥解密数据
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2024-06-13
     * @version 2024-06-13
     * @param string $data
     * @return bool
     */
    public function canDecrypt(string $data): bool
    {
        try {
            $this->decrypt($data);
        } catch (LUtilsException $e) {
            return false;
        }

        return true;
    }

    /**
     * 使用私钥解密数据
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2024-06-13
     * @version 2024-06-13
     * @param string $data
     * @param int $padding
     * @return string
     */
    public function decrypt(string $data, int $padding = OPENSSL_PKCS1_PADDING): string
    {
        openssl_private_decrypt($data, $decrypted, $this->privateKey, $padding);

        if (is_null($decrypted)) {
            throw LUtilsException::CouldNotDecryptData();
        }

        return $decrypted;
    }

    /**
     * 返回包含密钥详情的数组
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2024-06-13
     * @version 2024-06-13
     * @return array
     */
    public function details(): array
    {
        return openssl_pkey_get_details($this->privateKey);
    }

    /**
     * 使用私钥签名
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2023-10-08
     * @version 2023-10-08
     * @param string $data
     * @return string
     */
    public function sign(string $data, int $algorithm = OPENSSL_ALGO_SHA1): string
    {
        openssl_sign($data, $signature, $this->privateKey, $algorithm);

        return base64_encode($signature);
    }
}
