<?php

namespace littlemo\utils\rsa;

use littlemo\utils\core\LUtilsException;

/**
 * 公钥
 * @description
 * @example
 * @author LittleMo 25362583@qq.com
 * @since 2024-06-13
 * @version 2024-06-13
 */
class PublicKey
{

    /**
     * OpenSSLAsymmetricKey 实例
     */
    protected $publicKey = null;

    public function __construct(string $publicKeyString)
    {
        $this->publicKey = openssl_pkey_get_public($publicKeyString);

        if ($this->publicKey === false) {
            throw LUtilsException::InvalidPublicKey();
        }
    }

    /**
     * 通过字符串加载公钥
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2024-06-13
     * @version 2024-06-13
     * @param string $publicKeyString
     * @return self
     */
    public static function fromString(string $publicKeyString): self
    {
        return new static($publicKeyString);
    }
    /**
     * 通过文件加载公钥
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2024-06-13
     * @version 2024-06-13
     * @param string $pathToPublicKey
     * @return self
     */
    public static function fromFile(string $pathToPublicKey): self
    {
        if (!file_exists($pathToPublicKey)) {
            throw LUtilsException::FileDoesNotExist($pathToPublicKey);
        }
        $publicKeyString = file_get_contents($pathToPublicKey);

        return new static($publicKeyString);
    }


    /**
     * 使用公钥加密数据
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2024-06-13
     * @version 2024-06-13
     * @param string $data
     * @param int $padding
     * @return string
     */
    public function encrypt(string $data, int $padding = OPENSSL_PKCS1_PADDING): string
    {
        openssl_public_encrypt($data, $encrypted, $this->publicKey, $padding);

        return $encrypted ?? '';
    }

    /**
     * 使用公钥解密数据
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
     * 使用公钥解密数据
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
        openssl_public_decrypt($data, $decrypted, $this->publicKey, $padding);

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
        return openssl_pkey_get_details($this->publicKey);
    }

    /**
     * 验证签名
     * @description
     * @example
     * @author LittleMo 25362583@qq.com
     * @since 2024-06-13
     * @version 2024-06-13
     * @param string $data
     * @param string $signature
     * @param int $algorithm        枚举（https://www.php.net/manual/zh/openssl.signature-algos.php）
     * @return bool
     */
    public function verify(string $data, string $signature, $algorithm = OPENSSL_ALGO_SHA1): bool
    {
        return openssl_verify($data, base64_decode($signature), $this->publicKey, $algorithm);
    }
}
