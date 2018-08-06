#!/usr/bin/php
<?php

require_once '/opt/csv_converter/vendor/fzaninotto/faker/src/autoload.php';

$faker = Faker\Factory::create();
$shortOpts = "h::i:o:c:d::";
$longOpts = array(
    "help::",
    "input-file:",
    "output-file:",
    "config-file:",
    "delimiter:",
    "skip-first::",
    "strict::"
);
$row = 0;
$options = getopt($shortOpts, $longOpts);
$new_options = checkVals($options);

if ($new_options === null) {
    exit;
}

[$input_path, $config_path, $output_path, $delimiter, $skip] = $new_options;

$config_file = include($config_path);

if (($handle = fopen($input_path, "r")) !== false) {
    $new_handle = fopen($output_path, "w+");
    while (($data = fgetcsv($handle, 1000, $delimiter)) !== false) {
        foreach ($config_file as $key => $value) {
            if ($skip) {
                $skip = false;
                continue;
            }
            $data[$key] = is_callable($value) ? $value($data[$key], $data,
                $row, $faker) : ($value ? $faker->$value() : $value);
        }
        fputcsv($new_handle, $data, $delimiter);
        $row++;
    }
    fclose($handle);
}

function checkVals($options)
{
    $delimiter = ',';
    $input_path = '';
    $output_path = '';
    $config_path = '';
    $skip = false;
    foreach ($options as $key => $value) {
        switch ($key) {
            case 'h':
                echo 'this is help string' . PHP_EOL;
                return null;
            case 'help':
                echo 'this is help string' . PHP_EOL;
                return null;
            case 'o':
                $output_path = $value;
                break;
            case 'output-file':
                $output_path = $value;
                break;
            case 'i':
                $input_path = $value;
                break;
            case 'input-file':
                $input_path = $value;
                break;
            case 'd':
                $delimiter = $value;
                break;
            case 'delimiter':
                $delimiter = $value;
                break;
            case 'c':
                $config_path = $value;
                break;
            case 'config-file':
                $config_path = $value;
                break;
            case 'skip-first':
                $skip = true;
                break;
        }
    }
    if ($input_path == '' || $config_path == '' || $output_path == '') {
        echo 'wrong paths' . PHP_EOL;
        return null;
    }
    return [$input_path, $config_path, $output_path, $delimiter, $skip];
}