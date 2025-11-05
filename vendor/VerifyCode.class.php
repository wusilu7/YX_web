<?php

//验证码
class VerifyCode
{
    private function makeCode($len = 4)
    {
        // 限制验证码的长度在3-6之间
        $len = ($len > 6) ? 6 : $len;
        $len = ($len < 3) ? 3 : $len;

        // 创建真彩色空画布
        $img = imagecreatetruecolor(100, 40);

        // 随机分配一个背景亮色
        $bgColor = imagecolorallocate($img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));

        // 填充背景颜色
        imagefill($img, 0, 0, $bgColor);

        // 字体库
        $str = '23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ';
        // 字体最大索引下标
        $maxIndex = strlen($str) - 1;

        $flag = 0;
        session_start();
        $_SESSION['vcode'] = '';
        while ($flag < $len) {
            // 给字体分配随机颜色
            $color = imagecolorallocate($img, mt_rand(0, 180), mt_rand(0, 180), mt_rand(0, 180));
            // 给验证码里面写字
            $index = mt_rand(0, $maxIndex);

            $_SESSION['vcode'] .= $str{$index};
            // 水平坐标
            $x = (105 - 25 * $len) / 2 + $flag * 25;

            // imagestring($img, 5, $x, 10, $str{$index}, $color);
            imagettftext($img, 25, mt_rand(-10, 10), $x, 30, $color, "", $str{$index});
            $flag++;
        }

        // 生成100个像素点
        for ($i = 0; $i < 100; $i++) {
            $color = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
            imagesetpixel($img, mt_rand(0, 100), mt_rand(0, 40), $color);
        }

        // 告诉浏览器以图像的形式打开
        header('Content-type:image/jpeg');

        // 输出
        imagejpeg($img);

        // 销毁
        imagedestroy($img);
    }

    public function getCode()
    {
        $this->makeCode();
    }
}

