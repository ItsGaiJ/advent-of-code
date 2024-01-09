<?php
$file = file('input.txt');
$td = [];

foreach ($file as $line_num => $line) {
    $digits_temp = [];

    $positions = [];
    foreach ($s = str_split(trim($line)) as $key => $l) {
        if (!ctype_alpha($l)) {
            $digits_temp[] = $l;
        }
    }

    $digits_temp = $digits_temp[0] . "" . $digits_temp[count($digits_temp) - 1];
    $td[] = $digits_temp;
}

var_dump(array_sum($td));
