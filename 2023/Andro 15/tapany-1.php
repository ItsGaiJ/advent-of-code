<?php
$file = file('input.txt');
$results = [];
$steps = [];

foreach ($file as $line_num => $line) {
    $steps = explode(',', trim($line));
}

foreach ($steps as $step) {
    $current_value = 0;
    foreach (str_split($step) as $char) {
        $current_value += ord($char);
        $current_value = $current_value * 17;
        $current_value = $current_value % 256;
    }
    $results[] = $current_value;
}
var_dump(array_sum($results));
