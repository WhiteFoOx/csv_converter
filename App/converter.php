#!/usr/bin/php
<?php

require_once __DIR__ . '/../vendor/fzaninotto/faker/src/autoload.php';
require_once 'run.php';
require_once 'checkoutFile.php';

$faker = Faker\Factory::create();

/**
 * Проверка переданных параметров
 */

$options = checkParams($argv, $argc);
[$input_path, $config_path, $output_path, $delimiter, $skip] = $options;

if (!checkoutFile($input_path) || !checkoutFile($config_path) || !checkoutFile($output_path, true)) {
    echo 'Incorrect paths!' . PHP_EOL .
        "Must be three files:" . PHP_EOL .
        " 1. Input file in .csv format with readable rights;" . PHP_EOL .
        " 2. Output file in .csv format with writable rights;" . PHP_EOL .
        " 3. Configuration file in .php format." . PHP_EOL;
    exit(1);
}
if ($input_path === $output_path) {
    echo 'Input file should not be output file' . PHP_EOL;
    exit(1);
}
if (substr($input_path, -3) !== 'csv') {
    echo 'Input file should be in .csv format' . PHP_EOL;
    exit(1);
}
/**
 * Изменение файла
 */
run($input_path, $config_path, $output_path, $delimiter, $skip, $faker);
