<?php
class AES
{
    private $hex_iv = '00000000000000000000000000000000';
    protected $key = 'VzMcrP0U#7s2w';//secretKey

    /**
     * 初始化数据
     * AESCrypt constructor.
     *
     * @param int $bit 使用加密位数
     */
    function __construct($bit = 256)
    {
        if ($bit == 256) {
            $this->key = hash('sha256', $this->key, true);
        } elseif ($bit == 128) {
            $this->key = md5($this->key, true);
        }
    }

    /**
     * 加密文本
     *
     * @param $str
     *
     * @return string
     */
    function encrypt($str)
    {
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
        mcrypt_generic_init($td, $this->key, $this->hexToStr($this->hex_iv));
        $block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $pad   = $block - (strlen($str) % $block);
        $str .= str_repeat(chr($pad), $pad);
        $encrypted = mcrypt_generic($td, $str);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return base64_encode($encrypted);
    }

    /**
     * 解密文本
     *
     * @param $code
     *
     * @return bool|string
     */
    function decrypt($code)
    {
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
        mcrypt_generic_init($td, $this->key, $this->hexToStr($this->hex_iv));
        $str   = mdecrypt_generic($td, base64_decode($code));
        $block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return $this->strippadding($str);
    }

    /*
      For PKCS7 padding
     */
    private function addpadding($string, $blocksize = 16)
    {
        $len = strlen($string);
        $pad = $blocksize - ($len % $blocksize);
        $string .= str_repeat(chr($pad), $pad);

        return $string;
    }

    private function strippadding($string)
    {
        $slast  = ord(substr($string, -1));
        $slastc = chr($slast);
        $pcheck = substr($string, -$slast);
        if (preg_match("/$slastc{".$slast."}/", $string)) {
            $string = substr($string, 0, strlen($string) - $slast);

            return $string;
        } else {
            return false;
        }
    }

    function hexToStr($hex)
    {
        $string = '';
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i].$hex[$i + 1]));
        }

        return $string;
    }
}