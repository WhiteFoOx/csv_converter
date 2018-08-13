<?php

namespace Test;

use PHPUnit\Framework\TestCase;

class EncodingTest extends TestCase
{
    private $filePath;

    protected function setUp()
    {
        $this->filePath = '../converter.php';
    }

    /**
     * @dataProvider additionProvider
     */
    public function testEncoding($input)
    {
        $fp = $this->filePath;
        $conf = "files/goodConf.php";
        $output = "files/OutputCSV.csv";

        exec("php " . $fp . " -i $input -c $conf -o $output", $output_res, $return_res);

        $fileContent1 = file_get_contents($input);
        $encod1 = mb_check_encoding($fileContent1, 'UTF-8') ? 'UTF-8' : 'Windows-1251';
        $fileContent2 = file_get_contents($output);
        $encod2 = mb_check_encoding($fileContent2, 'UTF-8') ? 'UTF-8' : 'Windows-1251';

        $this->assertEquals($encod1, $encod2);
    }

    public function additionProvider()
    {
        return [
            ["files/Utf8.csv"],
            ["files/W1251.csv"],
        ];
    }
}

