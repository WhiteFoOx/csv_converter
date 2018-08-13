<?php

function detectParams($argv, $argc, $help)
{
    $delimiter = ',';
    $input_path = '';
    $output_path = '';
    $config_path = '';
    $skip = false;
    $strict = true;
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
    foundExceptions($input_path, $config_path, $output_path, $delimiter, $strict);
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

function columnsCount($input_path, $delimiter)
{
    $handle = fopen($input_path, "r");
    $data = fgetcsv($handle, 1000, $delimiter);
    return count($data);
}

function checkEncoding($input_path)
{
    return mb_check_encoding(file_get_contents($input_path), 'UTF-8') ? 'UTF-8' : 'Windows-1251';
}
