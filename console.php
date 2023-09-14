<?php

require_once 'Domain.php';

main();

function main() {
    $str = "Ботинки DELTA High All Black";
    $needle = 'ботинки';

    if (str_contains(mb_strtolower($str), $needle)) {
        echo 'Нашел!';
    } else {
        echo 'НЕТ!';
    }
}
