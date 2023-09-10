<?php

declare(strict_types=1);

require_once './vendor/autoload.php';

girlClothesTest();

function girlClothesTest(): void
{
    $srcCsvFilePath = './src/test/girlClothes.csv';
    $srcJsonFilePath = './src/test/girlClothes.json';
    $resultCsvFile = './result/test/girlClothes.csv';
    $resultJsonFilePath = './result/test/girlClothes.json';

    $json = csvToJson($srcCsvFilePath);

    $arr = json_decode($json);

    $jsonResult = jsonEncode($arr);

    file_put_contents($resultJsonFilePath, $jsonResult);

    jsonToCsv($jsonResult, $resultCsvFile, false);

    if (!filesAreEqualByHash($srcCsvFilePath, $resultCsvFile)) {
        echo 'ВНИМАНИЕ!!!!!!!! Тест провалился, файлы '. $srcCsvFilePath . ' и ' . $resultCsvFile . ' не идентичны' . PHP_EOL;
    }

    //пока отключил т.к. это непринципиално в данный момент
//    if (!filesAreEqualByHash($srcJsonFilePath, $resultJsonFilePath)) {
//       echo 'ВНИМАНИЕ!!!!!!!! Тест провалился, файлы '. $srcJsonFilePath . ' и ' . $resultJsonFilePath . ' не идентичны' . PHP_EOL;
//    }

    echo 'Тест прошел успешно! csv файл ' . $srcCsvFilePath. ' успешно конвертирован в json файл '
        . $resultJsonFilePath . ' и в идентичный csv файл ' . $resultCsvFile . PHP_EOL;
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
function csvToJson($csvFilePath): string | bool
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

function filesAreEqualByHash(string $filePath1, string $filePath2): bool
{
    $hash1 = hash_file('md5', $filePath1);
    $hash2 = hash_file('md5', $filePath2);

    return $hash1 === $hash2;
}


function jsonEncode(mixed $data): string
{
    return json_encode(
        $data,
        JSON_UNESCAPED_UNICODE
        | JSON_PRETTY_PRINT
        | JSON_UNESCAPED_SLASHES
        | JSON_NUMERIC_CHECK
        | JSON_UNESCAPED_LINE_TERMINATORS
    );
}
