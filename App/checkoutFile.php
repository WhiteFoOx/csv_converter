<?php

function checkoutFile($filePath, $default = false)
{
    $result = false;
    if (is_file($filePath)) {
        if (is_readable($filePath)) {
            $result = true;
        }
    } elseif (is_dir(__DIR__) && $default == true) {
        if (is_writable(__DIR__)) {
            return true;
        } else {
            return false;
        }
    }
    if ($default == true) {
        $result = is_writable($filePath) ? true : false;
    }
    return $result;
}

function checkStrict($input_path, $config_path, $delimiter)
{
    $input_file = fgetcsv(fopen($input_path, "r"), 1000, $delimiter);
    $config_file = include($config_path);
    if (count($input_file) < max(array_keys($config_file))) {
        return false;
    } else {
        return true;
    }
}

function checkEncoding($input_path)
{

    return mb_check_encoding(file_get_contents($input_path), 'UTF-8') ? 'UTF-8' : 'Windows-1251';
}

function checkParams($argv, $argc)
{
    $delimiter = ',';
    $input_path = '';
    $output_path = '';
    $config_path = '';
    $skip = false;
    $strict = true;
    $help = 'CSV_converter is a program which convert .csv file to a new file using configuration.'
        . PHP_EOL . 'All you need to use this program is:'
        . PHP_EOL . '1. Input file in .csv format with readable rights;'
        . PHP_EOL . '2. Configuration file in php format with readable rights;'
        . PHP_EOL . '3. Output path with writable rights.'
        . PHP_EOL . 'Available parameters:'
        . PHP_EOL . "-i|--input       < input path >  - path to changeable file,"
        . PHP_EOL . '-c|--config      < config path > - path to configuration file,'
        . PHP_EOL . '-o|--output      < output path > - path to result file,'
        . PHP_EOL . '-d|--delimiter   < argument >    - delimiter, default = ","'
        . PHP_EOL . '-h|--help                        - show help string,'
        . PHP_EOL . '--skip-first                     - miss first string in input file,'
        . PHP_EOL . '--strict                         - check equals between columns in input and config files'
        . PHP_EOL;
    for ($i = 1; $i < $argc; $i++) {
        switch ($argv[$i]) {
            case '-h':
                echo $help;
                exit(0);
            case '--help':
                echo $help;
                exit(0);
            case '-o':
                $output_path = $argv[$i + 1];
                $i++;
                break;
            case '--output':
                $output_path = $argv[$i + 1];
                $i++;
                break;
            case '-i':
                $input_path = $argv[$i + 1];
                $i++;
                break;
            case '--input':
                $input_path = $argv[$i + 1];
                $i++;
                break;
            case '-d':
                $delimiter = $argv[$i + 1];
                $i++;
                break;
            case '--delimiter':
                $delimiter = $argv[$i + 1];
                $i++;
                break;
            case '-c':
                $config_path = $argv[$i + 1];
                $i++;
                break;
            case '--config':
                $config_path = $argv[$i + 1];
                $i++;
                break;
            case '--skip-first':
                $skip = true;
                break;
            case '--strict':
                $strict = false;
                break;
            default:
                echo 'Undefined parameter ' . $argv[$i] . PHP_EOL;
                break;
        }
    }
    if ($delimiter == '') {
        echo 'Delimiter should contains at least one symbol';
        exit(1);
    }
    if ($input_path == '' || $config_path == '' || $output_path == '') {
        echo "Must be three files:" . PHP_EOL .
            " 1. Input file in .csv format with readable rights;" . PHP_EOL .
            " 2. Output path with writable rights;" . PHP_EOL .
            " 3. Configuration file in .php format." . PHP_EOL;
        exit(1);
    }
    if (!$strict) {
        $strict = checkStrict($input_path, $config_path, $delimiter);
        if (!$strict) {
            echo 'Numbers of columns in configuration file should be equal or less than columns in input file.'
                . PHP_EOL;
            exit(1);
        }
    }
    if ($delimiter == false || strlen($delimiter) > 1) {
        echo 'Delimiter should contains one symbol' . PHP_EOL;
        exit(1);
    }
    return [$input_path, $config_path, $output_path, $delimiter, $skip];
}

function detectEOL($input)
{
    if (($fo = fopen($input, "r")) !== false) {
        $row = fgets($fo);
        $eol = substr($row, -2);
        if ($eol == "\r\n") {
            return "\r\n";
        } elseif ($eol == "\n\r") {
            return "\n\r";
        } elseif (substr($eol, -1) == "\n") {
            return "\n";
        } elseif (substr($eol, -1) == "\r") {
            return "\r";
        }
    }
}

function fputcsv_eol($fp, $array, $eol, $delimiter = ',', $enclosure = '"', $escape_char = "\\")
{
    fputcsv($fp, $array, $delimiter, $enclosure, $escape_char);
    if ("\n" != $eol && 0 === fseek($fp, -1, SEEK_CUR)) {
        fwrite($fp, $eol);
    }
}

function checkConfig($config_path)
{
    ob_start();
    $conf = include($config_path);
    ob_end_clean();
    if (!is_array($conf)) {
        return false;
    }
    return true;
}

function columnsCount($input_path, $delimiter)
{
    $handle = fopen($input_path, "r");
    $data = fgetcsv($handle, 1000, $delimiter);
    return count($data);
}
