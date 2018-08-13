<?php

namespace Test;

use PHPUnit\Framework\TestCase;

final class OutputTest extends TestCase
{
    private $filePath;

    protected function setUp()
    {
        $this->filePath = '../converter.php';
    }

    /**
     * @dataProvider additionProvider
     */
    public function testOutputNumbers($conf)
    {
        $fp = $this->filePath;
        $input = "files/goodCommaCSV.csv";
        $output = "files/OutputCSV.csv";
        exec("php " . $fp . " -i $input -c $conf -o $output", $output_res, $return_res);
        if (fileException($input) && fileException($output)) {
            $handle = fopen($input, "r");
                $data_input = fgetcsv($handle);
                fclose($handle);
            $handle2 = fopen($output, "r");
                $data_output = fgetcsv($handle2);
                fclose($handle2);
        }
        $this->assertFalse(is_numeric($data_output[0]));
        $this->assertTrue($data_output[1] == $data_input[1]);
        $this->assertFalse(is_numeric($data_output[2]));
        $this->assertTrue(is_numeric($data_output[3]));
    }

    public function additionProvider()
    {
        return [
            ["files/OutputConf.php"],
        ];
    }
}
