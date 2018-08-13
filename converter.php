#!/usr/bin/php
<?php

require 'vendor/autoload.php';

$faker = Faker\Factory::create();
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

/**
 * Проверка переданных параметров
 */
try {
    $options = detectParams($argv, $argc, $help);
    [$input_path, $config_path, $output_path, $delimiter, $skip] = $options;

    if (!fileException($input_path) || !fileException($config_path) || !fileException($output_path, true)) {
        echo "Must be three files:" . PHP_EOL .
            " 1. Input file in .csv format with readable rights;" . PHP_EOL .
            " 2. Output file in .csv format with writable rights;" . PHP_EOL .
            " 3. Configuration file in .php format." . PHP_EOL;
        throw new Exception("Incorrect paths!");
    }
    if ($input_path === $output_path) {
        throw new Exception("Input file should not be output file");
    }
    if (substr($input_path, -3) !== 'csv') {
        throw new Exception("Input file should be in .csv format");
    }
    if (!configException($config_path)) {
        echo 'Data in wrong format!' . PHP_EOL;
        exit(1);
    }
    /**
     * Изменение файла
     */
    $config = include($config_path);
    run($input_path, $config, $output_path, $delimiter, $skip, $faker);
} catch (Exception $e) {
    echo 'Error: ', $e->getMessage(), PHP_EOL;
    exit(1);
}
