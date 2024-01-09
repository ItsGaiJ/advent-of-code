<?php
$file = file('input.txt');
$result = 0;
$steps = [];
$lens = [];
$boxes = [];

foreach ($file as $line_num => $line) {
    $steps = explode(',', trim($line));
}

foreach ($steps as $step) {
    $len = [];
    if (strpos($step, '=') !== false) {
        [$len['label'], $len['focal_length']] = explode('=', $step);
        $len['operation'] = '=';
    }
    if (strpos($step, '-') !== false) {
        $len['operation'] = '-';
        $len['label'] = strtok($step, $len['operation']);
    }
    $len['box_num'] = hash_algorithm($len['label']);
    $lens[] = $len;
}
unset($steps);

foreach ($lens as $len) {
    if ($len['operation'] === '=') {
        if (isset($boxes[$len['box_num']][$len['label']])) {
            $boxes[$len['box_num']][$len['label']] = $len['focal_length'];
        } else {
            if (!isset($boxes[$len['box_num']])) $boxes[$len['box_num']] = [];
            $boxes[$len['box_num']][$len['label']] = $len['focal_length'];
        }
    }
    if ($len['operation'] === '-') {
        if (isset($boxes[$len['box_num']][$len['label']])) {
            unset($boxes[$len['box_num']][$len['label']]);
        }
    }
}

foreach ($boxes as $box_num => $box) {
    foreach (array_values($box) as $slot => $focal_length) {
        $result += ($box_num + 1) * ($slot + 1) * $focal_length;
    }
}

var_dump($result);

function hash_algorithm(string $string): int
{
    $current_value = 0;
    foreach (str_split($string) as $char) {
        $current_value += ord($char);
        $current_value = $current_value * 17;
        $current_value = $current_value % 256;
    }
    return $current_value;
}
