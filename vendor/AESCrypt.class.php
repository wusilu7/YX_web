<?php

/**
 * PHP7.1 以后AES算法类文件
 * 修改于:2017-06-16 22:24:03
 * 发布者:nEtFox
 */

//namespace AESCrypt2017;


class AESCrypt
{
    const VI = "00000000000000000000000000000000";//偏移向量
    const OPENSSL = OPENSSL_RAW_DATA;
    protected $bit;//加密位数
    protected $aesKey;
    protected $defaultKey = "QxUCyYc46xcLjP8";
    protected $aesMethod;


    function __construct(int $bit = 256)
    {
        $this->bit = $bit;
        self::setAESKey();

    }

    /**
     * 根据位数产生算法密匙
     * @return string
     */
    protected function setAESKey(): string
    {
        if ($this->bit == 256) {
            $this->aesKey = hash('sha256', $this->defaultKey, true);//256使用hash
            $this->aesMethod = "AES-256-CBC";
        } elseif ($this->bit = 128) {
            $this->aesKey = md5($this->defaultKey, true);//128使用md5
            $this->aesMethod = "AES-128-CBC";
        } elseif ($this->bit = 192) {
            $this->aesKey = md5($this->defaultKey, true);//128使用md5
            $this->aesMethod = "AES-192-CBC";
        }
        return $this->aesKey;
    }

    /**
     * @param string $enString
     * @return string
     */
    public function encrypt(string $enString): string
    {
        return base64_encode(openssl_encrypt($enString, $this->aesMethod, $this->aesKey, self::OPENSSL,
            self::hexIv(self::VI)));
    }

    /**
     * @param string $deString
     * @return string
     */
    public function decrypt(string $deString): string
    {
        return openssl_decrypt(base64_decode($deString), $this->aesMethod, $this->aesKey, self::OPENSSL, self::hexIv(self::VI));
    }

    /**
     * @param string $iv
     * @return string
     */
    protected function hexIv(string $iv): string
    {
        return self::hexToStr($iv);
    }

    /**
     * @param $hex
     * @return string
     */
    protected function hexToStr(string $hex): string
    {
        $string = '';
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
        return $string;
    }

}
