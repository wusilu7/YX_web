<?php

namespace JIN\Core;
class IniConfig
{
    // 存储所有的配置重载项
    public static $configItems = [
        ['key' => 6, 'fileName' => 'CurrencyTypes.xlsx'],       // 补单（充值档位）
        ['key' => 27, 'fileName' => 'rechargeTiers.xls'],       // 补单（充值档位）
        ['key' => 10, 'fileName' => 'Currency_Behavior.xlsx'],  // 货币行为
        ['key' => 20, 'fileName' => 'Source_Behavior.xlsx'],    // 来源行为
    ];

    //加载配置文件
    public static function i()
    {
        global $config;
        global $configA;
        $config = include_once(CONFIG . 'config.php');
        $configA = include_once CONFIG . 'config1.php';

        // 依赖注入Excel类实例
        $excel = new Excel();
        // 在加载配置后，调用 reloadConfig 来进行配置重载
        self::reloadConfig(self::$configItems, $excel);
    }

    /**
     * @param Excel $excel Excel处理器
     * @param array $configItems 配置项数组
     * @author  Sun
     * @description 重载配置文件信息
     */
    public static function reloadConfig($configItems, $excel)
    {
        global $configA;
        foreach ($configItems as $item) {
            $key = $item['key'];
            $fileName = $item['fileName'];
            $filePath = self::buildFilePath($fileName);

            if (!self::validateFile($filePath, $key, $configA)) {
                continue;
            }
            // 文件后缀
            $suffix = pathinfo($fileName, PATHINFO_EXTENSION);
            // 根据key执行不同数据处理
            switch ($key) {
                case 6:
                    if (!$fileData = $excel->readWithCustomHeaderRow($filePath, $suffix, false, true, 1)) {
                        continue;
                    }
                    $fileData = array_filter($fileData, function ($item) {
                        return $item['disuse'] != 1;
                    });
                    $configA[$key] = $fileData;
                    break;
                case 27:
                    if (!$fileData = $excel->readWithCustomHeaderRow($filePath, $suffix, false, true, 1)) {
                        continue;
                    }
                    $configA[$key] = $fileData;
                    break;
                case 10:
                    if (!$fileData = $excel->readWithCustomHeaderRow($filePath, $suffix, false, true, 1)) {
                        continue;
                    }
                    $configA[$key] = array_column($fileData, 'value', 'key');
                    break;
                case 20:
                    if (!$fileData = $excel->readWithCustomHeaderRow($filePath, $suffix, false, true, 1)) {
                        continue;
                    }
                    $configA[$key] = array_column($fileData, 'value', 'key');
                    break;
                default:
                    ;
            }
        }
    }

    private static function buildFilePath($fileName)
    {
        return "config" . DIRECTORY_SEPARATOR . $fileName;
    }

    private static function validateFile($filePath, $key, $configA)
    {
        return file_exists($filePath) && isset($configA[$key]);
    }
}