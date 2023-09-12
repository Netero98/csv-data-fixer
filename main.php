<?php

require_once './vendor/autoload.php';
require_once 'V2.php';

// main();

class Fixer 
{
    const CATEGORY_GIRL_CLOTHES = 'Женская одежда';
    const CATEGORY_GIRL_BOOTS = 'Женская обувь';
    const CATEGORY_MAN_CLOTHES = 'Мужская одежда';
    const CATEGORY_MAN_BOOTS = 'Мужская обувь';

    public static function getOldCategory()
    {
        return __DIR__ . '/src/tempfile';
    }

    public function main($srcFilePath, $category): void
    {
        if ($category === self::CATEGORY_GIRL_BOOTS || $category === self::CATEGORY_GIRL_CLOTHES) {
            $oldCategory = 'Женское';
        } else {
            $oldCategory = 'Мужское';
        }

        createFixedCloneNoChunk(
            $srcFilePath,
            $srcFilePath,
            $oldCategory,
            $category
        );
    }
}

function createFixedCloneNoChunk(string $srcCsvPath, string $resultCsvPath, string $oldCategory, string $newCategory): void
{
    $js = csvToJson($srcCsvPath);

    $arr = json_decode($js, true);

    $counter = 0;

    foreach ($arr as $key => &$object) {
        if (!empty($object['Price'])) {
            $object['Price'] = floor($object['Price'] * 1.5);
        }

        if ($object['Category'] === $oldCategory) {
            $object['Category'] = $newCategory;

            $object['Text'] = addInlineStylesToAttributes($object['Text']) . getDeliveryHtml() . getPaymentHtml();

            $object['Description'] = '';

            ++$counter;
        }
    }

    echo $newCategory . '_' . $counter . PHP_EOL;

    $jsResult = json_encode($arr);

    jsonToCsv($jsResult, $resultCsvPath);
}

//function createFixedClone(string $srcCsvPath, string $resultCsvPath, string $oldCategory, string $newCategory): void
//{
//    $js = csvToJson($srcCsvPath);
//
//    $arr = json_decode($js, true);
//
//    $counter = 0;
//
//    foreach ($arr as $key => &$object) {
//        if (!empty($object['Price'])) {
//            $object['Price'] = floor($object['Price'] * 1.5);
//        }
//
//        if ($object['Category'] === $oldCategory) {
//            $object['Category'] = $newCategory;
//
//            $object['Text'] = addInlineStylesToAttributes($object['Text']) . getDeliveryHtml() . getPaymentHtml();
//
//            if (($counter + 1) % 200 === 0) {
//                $jsResult = json_encode(array_slice($arr, $counter - 200, 200));
//
//                jsonToCsv($jsResult, $resultCsvPath . '_' . $counter - 198 . '-' . $counter + 1 . '.csv', false);
//            }
//
//            ++$counter;
//        }
//    }
//
//    $jsResult = json_encode(array_slice($arr, $counter - ($counter % 200)));
//
//    jsonToCsv($jsResult, $resultCsvPath . '_' . $counter - ($counter % 200) + 1 . '-' . $counter + 1 . '.csv');
//
//}


function getPaymentHtml(): string
{
    return "
        <h3>Оплата</h3>
        <table style='width: 100%; margin-bottom: 20px; border: 1px solid #dddddd; border-collapse: collapse; '>
            <tr>
               <td style='border: 1px solid #dddddd; padding: 5px;'>на сайте с помощью банковской карты</td>
            </tr>
            <tr>
                <td style='border: 1px solid #dddddd; padding: 5px;'>в отделении СДЭК при получении</td>
            </tr>
            <tr>
                <td style='border: 1px solid #dddddd; padding: 5px;'>в почтовом отделении при получении наложенным платежом</td>
            </tr>
            <tr>
                <td style='border: 1px solid #dddddd; padding: 5px;'>курьеру СДЭК при заказе доставки до двери</td>
            </tr>
        </table>
    ";
}

function getDeliveryHtml(): string
{
    return "
            <br>
            <h3>Доставка</h3>
            <div>
                <table style='width: 100%; margin-bottom: 20px; border: 1px solid #dddddd; border-collapse: collapse; '>
                    <tr>
                        <th style='font-weight: bold; padding: 5px; background: #efefef; border: 1px solid #dddddd;'>Способ</th>
                        <th style='font-weight: bold; padding: 5px; background: #efefef; border: 1px solid #dddddd;'>Срок</th>
                        <th style='font-weight: bold; padding: 5px; background: #efefef; border: 1px solid #dddddd;'>Стоимость</th>
                    </tr>
                    <tr>
                        <td style='border: 1px solid #dddddd; padding: 5px;'>почтой РФ через почтовое отделение</td>
                        <td style='border: 1px solid #dddddd; padding: 5px;'>8-12 дней</td>
                        <td style='border: 1px solid #dddddd; padding: 5px;'>350 руб.</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid #dddddd; padding: 5px;'>СДЭК курьером до двери</td>
                        <td style='border: 1px solid #dddddd; padding: 5px;'>3 дня</td>
                        <td style='border: 1px solid #dddddd; padding: 5px;'>650 руб.</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid #dddddd; padding: 5px;'>СДЭК в пункте выдачи</td>
                        <td style='border: 1px solid #dddddd; padding: 5px;'>3 дня</td>
                        <td style='border: 1px solid #dddddd; padding: 5px;'>500 руб.</td>
                    </tr>
                    <tr>
                        <td style='border: 1px solid #dddddd; padding: 5px;'>почтой РФ через почтамат</td>
                        <td style='border: 1px solid #dddddd; padding: 5px;'></td>
                        <td style='border: 1px solid #dddddd; padding: 5px;'>0 руб.</td>
                    </tr>
                </table>
            </div>
            ";
}

function addInlineStylesToAttributes(string $html): string
{
    $html = preg_replace('/<table\b[^>]*>/', '<table style="width: 100%; margin-bottom: 20px; border: 1px solid #dddddd; border-collapse: collapse;">', $html);

    $html = preg_replace('/<th\b[^>]*>/', '<th style="font-weight: bold; padding: 5px; background: #efefef; border: 1px solid #dddddd;">', $html);

    $html = preg_replace('/<td\b[^>]*>/', '<td style="border: 1px solid #dddddd; padding: 5px;">', $html);

    return $html;
}

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

function jsonToCsv(string $jsonString, string $csvFilePath, bool $doubleQuotes = true): bool
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

    if (!$doubleQuotes) {
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
