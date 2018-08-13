<?php

function convert($input_path, $config, $output_path, $delimiter, $skip, $faker)
{
    $row = 0;
    $columns = columnsCount($input_path, $delimiter);
    $eol = detectEOL($input_path);
    $handle = fopen($input_path, "r");
    $new_handle = fopen($output_path, "w+");
    $encoding = checkEncoding($input_path);
    while (!feof($handle)) {
        $data = fgetcsv($handle, 1000, $delimiter);
        if ($skip) {
            $skip = false;
            fputcsv_eol($new_handle, $data, $eol, $delimiter);
            $row++;
            continue;
        }
        if ($data[0] == null) {
            continue;
        }
        foreach ($config as $key => $value) {
            $data[$key] = mb_convert_encoding(is_callable($value) ? $value($data[$key], $data, $row, $faker)
                : ($value ? $faker->$value() : $value), $encoding);
        }
        if (count($data) !== $columns) {
            throw new Exception('Data is not a correct');
        }
        fputcsv_eol($new_handle, $data, $eol, $delimiter);
        $row++;
    }
    fclose($handle);
    fclose($new_handle);
}
