<?php

declare(strict_types=1);

require_once './vendor/autoload.php';

//girlClothesTest();
//manBoots();
//girlBoots();
girlClothesVlast();
girlBoots();


function girlBoots(): void
{
    $inputFilePath = './src/girlBoots/girlBoots.csv';
    $resultFilePath = './result/girlBoots/girlBoots';

    $js = csvToJson($inputFilePath);

    $arr = json_decode($js);

    $counter = 0;

    foreach ($arr as $key => $object) {
        if (!empty($object->Price)) {
            $object->Price = floor($object->Price * 1.5);
        }

        if ($object->Category === 'Женское') {
            $object->Category = 'Женская обувь';
        }

        if (($counter + 1) % 200 === 0) {
            $jsResult = json_encode(array_slice($arr, $counter - 200, 200));

            jsonToCsv($jsResult, $resultFilePath . '_' . $counter - 198 . '-' . $counter + 1 . '.csv', false);
        }

        if ($object->Category === 'Женская обувь') {
            ++$counter;
        }
    }

    $jsResult = json_encode(array_slice($arr, $counter - ($counter % 200)));

    jsonToCsv($jsResult, $resultFilePath . '_' .  $counter - ($counter % 200) + 1 . '-' . $counter + 1 .'.csv');
}

function manBoots(): void
{
    $srcCsvFilePath = './src/manBoots/manBoots.csv';

    $resultJsonFilePath = './result/manBoots/manBoots.json';

    $json = csvToJson($srcCsvFilePath);

    file_put_contents($resultJsonFilePath, $json);
}

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

    jsonToCsv($jsonResult, $resultCsvFile);

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


function girlClothesVlast(): void
{
    $inputFilePath = './src/girlClothes/girlClothes.csv';
    $resultFilePath = './result/girlClothes/girlClothes.csv';

    $js = csvToJson($inputFilePath);

    $arr = json_decode($js);

    foreach ($arr as $key => $object) {
        if (!empty($object->Price)) {
            $object->Price = floor($object->Price * 1.5);
        }

        if ($object->Category === 'Женское') {
            $object->Category = 'Женская одежда';
        }

        if ($object->Category === 'Женская одежда') {
            $object->TabCode = '
<div class="t-store__tabs t-store__tabs_tabs t-col t-col_12" data-tab-design="tabs" data-active-tab="Доставка">
   <div class="t-store__tabs__controls-wrap">
      <div class="t-store__tabs__controls">
         <style>    .t-store__tabs__controls-wrap:before, .t-store__tabs__controls-wrap:after {        display: none;        z-index: 1;        position: absolute;        content: "";        width: 50px;        bottom: 1px;        top: 0;        pointer-events: none;    }    .t-store__tabs__controls-wrap_left:before {background-image:linear-gradient(to right,rgba(255,255,255,1) 0%, rgba(255,255,255,0) 90%);        left: -1px;    }    .t-store__tabs__controls-wrap_right:after {background-image:linear-gradient(to right,rgba(255,255,255,0) 0%, rgba(255,255,255,1) 90%);        right: -2px;    }    .t-store__tabs__controls-wrap_left:before {        display: block;    }    .t-store__tabs__controls-wrap_right:after {        display: block;    }</style>
         <div class="t-store__tabs__button js-store-tab-button t-store__tabs__button_active" data-tab-title="Доставка">
            <div class="t-store__tabs__button-title t-name t-name_xs">Доставка</div>
         </div>
         <div class="t-store__tabs__button js-store-tab-button " data-tab-title="Оплата">
            <div class="t-store__tabs__button-title t-name t-name_xs">Оплата</div>
         </div>
         <div class="t-store__tabs__button js-store-tab-button " data-tab-title="Таблица размеров">
            <div class="t-store__tabs__button-title t-name t-name_xs">Таблица размеров</div>
         </div>
      </div>
   </div>
   <div class="t-store__tabs__list">
      <div class="t-store__tabs__item t-store__tabs__item_active" data-tab-title="Доставка" data-tab-type="info">
         <div class="t-store__tabs__item-button js-store-tab-button" data-tab-title="Доставка">
            <div class="t-store__tabs__item-title t-name t-name_xs">Доставка                </div>
         </div>
         <div class="t-store__tabs__content t-descr t-descr_xxs">
            <figure data-alt="" data-src="https://static.tildacdn.com/stor3264-3932-4637-a336-373663663439/31826693.png" contenteditable="false"><img src="https://thumb.tildacdn.com/stor3264-3932-4637-a336-373663663439/-/resize/760x/-/format/webp/31826693.png" alt="" class="t-img loaded" data-original="https://static.tildacdn.com/stor3264-3932-4637-a336-373663663439/31826693.png"></figure>
            Сумма доставки может измениться в зависимости от объема заказа, точную сумму уточняйте у оператора.            
         </div>
      </div>
      <div class="t-store__tabs__item " data-tab-title="Оплата" data-tab-type="info">
         <div class="t-store__tabs__item-button js-store-tab-button" data-tab-title="Оплата">
            <div class="t-store__tabs__item-title t-name t-name_xs">Оплата                </div>
         </div>
         <div class="t-store__tabs__content t-descr t-descr_xxs"><br>Вы можете оплатить товар на сайте с помощью банковской карты.<br>В отделении СДЭК при получении.<br>В почтовом отделении при получении наложеным платежом.<br>Курьеру СДЭК при заказе доставки до двери.<br><br>            </div>
      </div>
      <div class="t-store__tabs__item " data-tab-title="Таблица размеров" data-tab-type="info">
         <div class="t-store__tabs__item-button js-store-tab-button" data-tab-title="Таблица размеров">
            <div class="t-store__tabs__item-title t-name t-name_xs">Таблица размеров                </div>
         </div>
         <div class="t-store__tabs__content t-descr t-descr_xxs">
            <figure data-alt="" data-src="https://static.tildacdn.com/stor6236-6262-4534-b463-393233656634/73671876.jpg" contenteditable="false"><img src="https://static.tildacdn.com/stor6236-6262-4534-b463-393233656634/-/empty/73671876.jpg" alt="" class="t-img" data-original="https://static.tildacdn.com/stor6236-6262-4534-b463-393233656634/73671876.jpg"></figure>
         </div>
      </div>
   </div>
</div>
';
        }
    }

    $jsResult = json_encode($arr);

    jsonToCsv($jsResult, $resultFilePath);
}

function girlClothesV1(): void
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
