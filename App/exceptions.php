<?php

function foundExceptions($input_path, $config_path, $output_path, $delimiter, $strict)
{
    if ($delimiter == '') {
        throw new Exception("Delimiter should contains at least one symbol");
    }
    if ($input_path == '' || $config_path == '' || $output_path == '') {
        throw new Exception("Wrong paths to one of three files");
    }
    if (!$strict) {
        $strict = strictException($input_path, $config_path, $delimiter);
        if (!$strict) {
            throw new Exception('Numbers of columns in configuration file should be equal
            or less than columns in input file.');
        }
    }
    if ($delimiter == false || strlen($delimiter) > 1) {
        throw new Exception("Delimiter should contains one symbol");
    }
}

function fileException($filePath, $default = false)
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

function strictException($input_path, $config, $delimiter)
{
    $input_file = fgetcsv(fopen($input_path, "r"), 1000, $delimiter);
    $config_file = include($config);
    if (count($input_file) < max(array_keys($config_file))) {
        return false;
    } else {
        return true;
    }
}

function configException($config_path)
{
    ob_start();
    $conf = include($config_path);
    ob_end_clean();
    if (!is_array($conf)) {
        return false;
    }
    return true;
}
