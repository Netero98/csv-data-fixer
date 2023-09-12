<?php

use League\Csv\Reader;
use League\Csv\Writer;

function createFixedCloneNew(string $srcCsvPath, string $resultCsvPath, string $oldCategory, string $newCategory): void
{
    $csv = Reader::createFromPath($srcCsvPath, 'r');

    $counter = 0;

    $limit = 200;

    while (true) {
        $stmt = \League\Csv\Statement::create();
        $stmt->offset($counter)->limit($limit);

        $records = $stmt->process($csv);

        if ($records->count() < 1) {
            break;
        }

        foreach ($records as $record) {
            if (!empty($record['Price'])) {
                $record['Price'] = floor($record['Price'] * 1.5);
            }

            if ($record['Category'] === $oldCategory) {
                $record['Category'] = $newCategory;

                $record['Text'] = $record['Text'] . getDeliveryHtml() . getPaymentHtml() ;

                echo $newCategory . '_' . $counter . PHP_EOL;

                ++$counter;
            }
        }

        $writer = Writer::createFromPath($resultCsvPath . '_' . $counter . '-' . $limit, 'w');
        $writer->insertAll($records);
    }
}