<?php

function run($input_path, $config_path, $output_path, $delimiter, $skip, $faker)
{
    require_once 'checkoutFile.php';

    $row = 0;
    $columns = columnsCount($input_path, $delimiter);
    $eol = detectEOL($input_path);
    if (!checkConfig($config_path)) {
        echo 'Data in wrong format!' . PHP_EOL;
        exit(1);
    }
    $config_file = include($config_path);
    $handle = fopen($input_path, "r");
    $new_handle = fopen($output_path, "w+");
    $encoding = checkEncoding($input_path);
    while (!feof($handle)) {
        $data = fgetcsv($handle, 1000, $delimiter);
        if ($skip) {
            $skip = false;
            fputcsv_eol($new_handle, $data, $eol, $delimiter);
            continue;
        }
        if ($data[0] == null) {
            continue;
        }
        if (count($data) !== $columns) {
            echo 'Data is not a correct' . PHP_EOL;
            exit(1);
        }
        foreach ($config_file as $key => $value) {
            $data[$key] = mb_convert_encoding(is_callable($value) ? $value($data[$key], $data, $row, $faker)
                : ($value ? $faker->$value() : $value), $encoding);
        }
        fputcsv_eol($new_handle, $data, $eol, $delimiter);
        $row++;
    }
    fclose($handle);
    fclose($new_handle);
}
