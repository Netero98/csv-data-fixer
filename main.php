<?php

declare(strict_types=1);

require_once './vendor/autoload.php';

convertJsonTest();
convertCsvTest();

function convertJsonTest(): void
{
    $jsonPath = './src/test/import_example_update.json';
    $resultCsvPath = './result/test/import_example_update.csv';

    $json = file_get_contents($jsonPath);

    jsonToCsv($json, $resultCsvPath);

    echo 'Json файл ' . $jsonPath . ' успешно конвертирован в csv файл ' . $resultCsvPath . PHP_EOL;
}

function convertCsvTest(): void
{
    $csvFilePath = './src/test/import_example_update.csv';
    $resultJsonPath = './result/test/import_example_update.json';

    $jsonData = csvToJson($csvFilePath);

    if ($jsonData === false) {
        throw new Exception('Ошибка при конвертации csv в json');
    }

    file_put_contents($resultJsonPath, $jsonData);

    echo 'csv файл ' . $csvFilePath. ' успешно конвертирован в json файл ' . $resultJsonPath . PHP_EOL;
}

function girlClothes(): void
{
    $inputFileName = './src/girlClothes/girlClothes.json';
    $resultJsonFile = './result/girlClothes/girlClothes.json';

    $js = file_get_contents($inputFileName);

    $arr = json_decode($js);

    foreach ($arr as $key => $object) {
        if (!empty($object->Price)) {
            $object->Price = floor($object->Price * 1.5);
        }

        if ($object->Category === 'Женское') {
            $object->Category = 'Женская одежда';
        }
    }

    $jsResult = json_encode($arr);

    file_put_contents($resultJsonFile, $jsResult);
}

// Функция для преобразования CSV в JSON
function csvToJson($csvFilePath)
{
    $csvFile = fopen($csvFilePath, 'r');
    if ($csvFile === false) {
        return false;
    }

    $headers = fgetcsv($csvFile, 0, ';');
    $jsonArray = array();

    while (($row = fgetcsv($csvFile, 0, ';')) !== false) {
        $item = array();
        foreach ($headers as $i => $header) {
            $item[trim($header)] = trim($row[$i]);
        }
        $jsonArray[] = $item;
    }

    fclose($csvFile);

    return json_encode(
        $jsonArray,
        JSON_UNESCAPED_UNICODE
        | JSON_PRETTY_PRINT
        | JSON_UNESCAPED_SLASHES
        | JSON_NUMERIC_CHECK
        | JSON_UNESCAPED_LINE_TERMINATORS
    );
}

function jsonToCsv($jsonString, $csvFilePath, bool $noDoubleQuotes = true): bool
{
    $jsonData = json_decode($jsonString, true);

    if ($jsonData === null) {
        return false;
    }

    $csvFile = fopen($csvFilePath, 'w');

    if ($csvFile === false) {
        return false;
    }

    $headersWritten = false;

    foreach ($jsonData as $row) {
        // Iterate through the row and remove double quotes from values with spaces

        if (!$headersWritten) {
            fputcsv($csvFile, array_keys($row), ';',);
            $headersWritten = true;
        }

        fputcsv($csvFile, $row, ';',);
    }

    fclose($csvFile);

    if ($noDoubleQuotes) {
        $csvAfter = file_get_contents($csvFilePath);

        $csvCharsArr = str_split($csvAfter);

        foreach ($csvCharsArr as &$char) {
            if ($char === '"') {
                $char = '';
            }
        }

        $csvResult = implode('', $csvCharsArr);

        file_put_contents($csvFilePath, $csvResult);
    }

    return true;
}
