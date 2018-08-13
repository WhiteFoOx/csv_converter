<?php

namespace Test;

use PHPUnit\Framework\TestCase;

class ParamsTest extends TestCase
{
    private $filePath;

    protected function setUp()
    {
        $this->filePath = "../converter.php";
    }

    /**
     * @dataProvider additionProvider
     */
    public function testParam($expected, $arrayParams)
    {
        $fp = $this->filePath;
        $output = [];
        $return_var = "";
        exec("php " . $fp . " " . implode(" ", $arrayParams), $output, $return_var);
        $this->assertEquals($expected, $return_var == 0);
    }

    public function additionProvider()
    {
        $inputFileComma = "files/goodCommaCSV.csv";
        $goodConf = "files/goodConf.php";
        $outputFile = "files/OutputCSV.csv";
        $inputFileSkip = "files/skipCSV.csv";
        $inputFileDel = "files/goodNCCsv.csv";
        $notRdb = "files/notRdb.csv";

        return [
            //empty params
            [false, []],
            [false, ["-i"]],
            [false, ["-c"]],
            [false, ["-o"]],
            [false, ["--input"]],
            [false, ["--config"]],
            [false, ["--output"]],
            [false, ["-i fff", "-c zzz"]],
            [false, ["-c rrr", "-o ggg"]],
            [false, ["-i sss", "-o ggg"]],
            [false, ["-i fff", "-o sss", "-c eee"]],
            //empty output
            [false, ["-i $inputFileComma", "-c $goodConf", "--strict"]],
            //wrong delimiter
            [false, ["-i $inputFileComma", "-c $goodConf", "-o $outputFile", '-d "asdf"']],
            //check not readable
            [false, ["-i $notRdb", "-c $goodConf", "-o $outputFile"]],
            //check not writable output
            [false, ["-i $inputFileComma", "-c $goodConf", "-o $notRdb"]],
            //check another delimiter
            [true, ["-i $inputFileDel", "-c $goodConf", "-o $outputFile", '-d ";"']],
            //skip first string
            [true, ["-i $inputFileSkip", "-c $goodConf", "-o $outputFile", "--skip-first"]],
            //check strict
            [true, ["-i $inputFileComma", "-c $goodConf", "-o $outputFile", "--strict"]],
            //default test
            [true, ["-i $inputFileComma", "-c $goodConf", "-o $outputFile"]],
            //check -h
            [true, ["-h"]],
            //check --help
            [true, ["--help"]]
        ];
    }
}