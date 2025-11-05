<?php
//导出到excel
namespace JIN\core;
class Excel
{
    private $objPHPExcel;
    private $objWriter;

    function __construct()
    {
        // 准备EXCEL的包括文件
        include_once VENDOR . 'PHPExcel.php';
//        include_once VENDOR . 'PHPExcel/Writer/Excel5.php';
        // Error reporting
//        error_reporting(0);
        // 生成新的excel对象
        $this->objPHPExcel = new \PHPExcel();
        // 设置excel文档的属性
        $this->objPHPExcel->getProperties()
            ->setCreator("xuanqu")
            ->setLastModifiedBy("xuanqu")
            ->setTitle("xuanqu")
            ->setSubject("xuanqu")
            ->setDescription("xuanqu")
            ->setKeywords("xuanqu")
            ->setCategory("xuanqu");
    }

    function setTitle($title)
    {
        // 设置工作薄名称
        $this->objPHPExcel->getActiveSheet()->setTitle($title);
    }

    function setCellTitle($cell, $value)//设置Excel标题
    {
        //写表
        $this->objPHPExcel->getActiveSheet()->setCellValue($cell, $value);
        //设置粗体
        $this->objPHPExcel->getActiveSheet()->getStyle($cell)->getFont()->setBold(true);
    }

    function setCellValue($cell, $value)
    {
        //写表
        $this->objPHPExcel->getActiveSheet()->setCellValue($cell, $value);
    }

    function setBold($cell)
    {
        //设置粗体
        $this->objPHPExcel->getActiveSheet()->getStyle($cell)->getFont()->setBold(true);
    }

    function save($filename)
    {
        for ($i = 'A'; $i < 'Z'; $i++) {
            $this->objPHPExcel->getActiveSheet()->getColumnDimension($i)->setAutoSize(true);
        }
        return $this->saveToFile($filename);
    }

    function saveToFile($filename)
    {
        ob_end_clean();//清除缓冲区,避免乱码
        $this->objWriter = \PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
        // 从浏览器直接输出$filename
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type: application/vnd.ms-excel;");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header("Content-Disposition:attachment;filename=" . $filename . ".xlsx");
        header("Content-Transfer-Encoding:binary");
        $path = 'tmp/' . $filename . '.xlsx';
        $this->objWriter->save($path);
//        $this->objWriter->save("php://output");
        return $path;
    }


    public function read($filename)
    {
        $objReader = \PHPExcel_IOFactory::createReader('Excel5');
        $objPHPExcel = $objReader->load(CONFIG . $filename . '.xls');
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();//所有行数
        $highestColumn = $objWorksheet->getHighestColumn();//所有列数
        $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
        $data = [];
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($col = 1; $col < $highestColumnIndex; $col++) {
//                $data[$row - 2][] = (string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                $id = (string)$objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
                $data[$id][] = (string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            }
        }
        return $data;
    }

    public function read1($filename)
    {
        $objReader = \PHPExcel_IOFactory::createReader('Excel5');
        $objPHPExcel = $objReader->load(CONFIG . $filename . '.xls');
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();//所有行数
        $highestColumn = $objWorksheet->getHighestColumn();//所有列数
        $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
        $data = [];
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($col = 1; $col < $highestColumnIndex; $col++) {
                $data[$row - 2][] = (string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                $id = (string)$objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
                $data[$id] = (string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            }
        }
        return $data;
    }

    public function read3($filename,$suffix,$type=1)
    {
        $objReader = \PHPExcel_IOFactory::createReader($suffix);
        $objPHPExcel = $objReader->load($filename);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();//所有行数
        if($type){
            $highestColumn = $objWorksheet->getHighestColumn();//所有列数
        }else{
            $highestColumn = "Z";
        }
        $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
        $data = [];
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($col = 0; $col < $highestColumnIndex; $col++) {
                $data[$row-2][] = (string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            }
        }
        return $data;
    }

    public function read4($filename,$suffix)
    {
        $objReader = \PHPExcel_IOFactory::createReader($suffix);
        $objPHPExcel = $objReader->load($filename);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();//所有行数
        $highestColumn = $objWorksheet->getHighestColumn();//所有列数
        $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
        $data = [];
        for ($row = 4; $row <= $highestRow; $row++) {
            //排除第一列带#注释的
            $str =  (string)$objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
            if(strstr($str,"#")){
                continue;
            }
            if($str==''){
                continue;
            }
            for ($col = 0; $col < $highestColumnIndex; $col++) {
                //第二行的值做键
                $kk = (string)$objWorksheet->getCellByColumnAndRow($col, 2)->getValue();
                //第一列的键和值存入(为了后面入库)
                $kkk = (string)$objWorksheet->getCellByColumnAndRow(0, 2)->getValue();
                $vvv = (string)$objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
                $data[$row-4][0] = $kkk;
                $data[$row-4][1] = $vvv;
                $data[$row-4][$kk] = (string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            }
        }
        return $data;
    }
    public function read5($filename,$type,$suffix)
    {
        $objReader = \PHPExcel_IOFactory::createReader($suffix);
        $objPHPExcel = $objReader->load($filename);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();//所有行数
        $highestColumn = $objWorksheet->getHighestColumn();//所有列数
        $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
        $data = [];
        if($type==1){
            for ($row = 1; $row <= $highestRow; $row++) {
                for ($col = 0; $col < $highestColumnIndex; $col++) {
                    $data[$row-1][] = (string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                }
            }
        }else{
            for ($row = 1; $row <= 3; $row++) {
                for ($col = 0; $col < $highestColumnIndex; $col++) {
                    if(!empty((string)$objWorksheet->getCellByColumnAndRow($col, 1)->getValue())){
                        $data[$row-1][] = (string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                    }
                }
            }
        }

        return $data;
    }

    public function read6($filename,$suffix)
    {
        $objReader = \PHPExcel_IOFactory::createReader($suffix);
        $objPHPExcel = $objReader->load($filename);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();//所有行数
        $highestColumn = $objWorksheet->getHighestColumn();//所有列数
        $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
        $data = [];
        for ($row = 4; $row <= $highestRow; $row++) {
            //排除第一列带#注释的
            $str =  (string)$objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
            if(strstr($str,"#")){
                continue;
            }
            if($str==''){
                continue;
            }
            for ($col = 0; $col < $highestColumnIndex; $col++) {
                //第二行的值做键
                $kk = (string)$objWorksheet->getCellByColumnAndRow($col, 3)->getValue();
                $data[$row-4][$kk] = (string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            }
        }
        return $data;
    }

    public function read7($filename,$suffix)
    {
        $objReader = \PHPExcel_IOFactory::createReader($suffix);
        $objPHPExcel = $objReader->load($filename);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();//所有行数
        $highestColumn = $objWorksheet->getHighestColumn();//所有列数
        $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
        $data = [];
        for ($row = 3; $row <= $highestRow; $row++) {
            for ($col = 0; $col < $highestColumnIndex; $col++) {
                //第二行的值做键
                $kk = (string)$objWorksheet->getCellByColumnAndRow($col, 2)->getValue();
                $kkk = (string)$objWorksheet->getCellByColumnAndRow(0, 2)->getValue();
                $vvv = (string)$objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
                if($vvv==''){
                    continue; //第一列的值为空跳出循环
                }
                $data[$row-3][0] = $kkk;
                $data[$row-3][1] = $vvv;
                $data[$row-3][$kk] = (string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            }
        }
        return $data;
    }

    /**
     * @param string $filename 文件名（包括路径）
     * @param string $suffix 文件后缀
     * @param bool $includeHeader 是否包含表头数据，默认为false
     * @param bool $useHeaderAsKeys 是否使用对应的表头字段名作为数据下标，默认为false
     * @param int $headerRow 表头行号，默认为1
     * @param int|null $dataStartRow 数据开始行号，默认为表头行号的下一行
     * @return array 从表格中读取的数据
     * @author  Sun
     * @description 读取表格数据
     */
    public function readWithCustomHeaderRow($filename, $suffix, $includeHeader = false, $useHeaderAsKeys = false, $headerRow = 1, $dataStartRow = null)
    {
        $suffix = $suffix == 'xls' ? 'Excel5' : 'Excel2007';
        $objReader = \PHPExcel_IOFactory::createReader($suffix);  // 根据文件后缀选择读取器
        $objPHPExcel = $objReader->load($filename);  // 加载文件
        $objWorksheet = $objPHPExcel->getActiveSheet();  // 获取当前活动的工作表
        $highestRow = $objWorksheet->getHighestRow();  // 获取总行数
        $highestColumn = $objWorksheet->getHighestColumn();  // 获取总列数
        $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);  // 将列数转为数字索引

        $headers = [];
        $data = [];
        $validColumns = [];

        // 读取自定义的表头行作为键（header），并且过滤空键
        for ($col = 0; $col < $highestColumnIndex; $col++) {
            $headerValue = (string)$objWorksheet->getCellByColumnAndRow($col, $headerRow)->getValue();
            if (!empty($headerValue)) {
                $headers[$col] = $headerValue;
                $validColumns[] = $col;  // 只有有效的列才被添加到此数组
            } else {
                $headers[$col] = 'column' . ($col + 1);  // 如果表头为空，则使用默认键值
            }
        }
        // 如果没有指定$dataStartRow，则从表头行的下一行开始读取数据
        if ($dataStartRow === null) {
            $dataStartRow = $headerRow + 1;
        }
        // 读取表头行之后的所有行作为数据
        for ($row = $dataStartRow; $row <= $highestRow; $row++) {
            $rowData = [];
            foreach ($validColumns as $col) {
                $cellValue = (string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                if ($useHeaderAsKeys) {
                    $rowData[$headers[$col]] = $cellValue;
                } else {
                    $rowData[] = $cellValue;
                }
            }
            $data[] = $rowData;
        }
        // 如果不包含表头数据，则只返回数据行
        if (!$includeHeader) {
            return $data;
        }
        // 包含表头数据，则返回带有表头和数据的数组
        return ['headers' => array_intersect_key($headers, array_flip($validColumns)), 'data' => $data];
    }

    /**
     * @author  Sun
     * @description 强制将值设置为文本格式（处理大数字时的精度限制）
     */
    function setCellValueAsText($cell, $value)
    {
        // 强制将值设置为文本格式
        $this->objPHPExcel->getActiveSheet()->getCell($cell)->setValueExplicit($value, \PHPExcel_Cell_DataType::TYPE_STRING);
    }
}