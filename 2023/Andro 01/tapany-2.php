<?php
$file = file('input.txt');
$td = [];
$digits_spelled = ['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];

foreach ($file as $line_num => $line) {
    $digits_temp = [];
    $positions = [];
    $ds_d = [];

    foreach ($digits_spelled as $ds) {
        $pos = false;
        while (($pos = strpos(trim($line), $ds, $pos)) !== false) {
            $ds_d[] = [$pos => (array_search($ds, $digits_spelled) + 1)];
            $pos = $pos + strlen($ds);
        }
    }

    foreach (str_split(trim($line)) as $key => $l) {
        if (!ctype_alpha($l)) {
            $ds_d[] = [$key => intval($l)];
        }
    }

    foreach ($ds_d as $value) {
        $positions[array_keys($value)[0]] = $value;
    }

    ksort($positions);

    foreach ($positions as $posi) {
        $digits_temp[] = $posi[array_keys($posi)[0]];
    }
    // var_dump("[" . implode(", ", $digits_temp) . "]");

    $digits_temp = $digits_temp[0] . "" . $digits_temp[count($digits_temp) - 1];
    $td[] = $digits_temp;
}
var_dump(array_sum($td));
