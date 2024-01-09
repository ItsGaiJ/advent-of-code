<?php
$file = file('input.txt');

$pairs = [];
$galaxies = [];
$rows_no_galaxy = [];
$columns_no_galaxy = [];
$multiple = 1000000;
$steps = 0;

foreach ($file as $line_num => $line) {
    $line = str_split(trim($line));
    if (count(array_unique($line)) == 1) {
        $rows_no_galaxy[] = $line_num;
    }
    $map[] = $line;
}

for ($i = 0; $i < count($map[0]); $i++) {
    $column = array_column($map, $i);
    if (count(array_unique($column)) == 1) {
        $columns_no_galaxy[] = $i;
    }
}

for ($y = 0; $y < count($map); $y++) {
    for ($x = 0; $x < count($map[0]); $x++) {
        if ($map[$y][$x] == "#") {
            $galaxies[] = [$y, $x];
        }
    }
}

for ($i = 0; $i < count($galaxies); $i++) {
    for ($j = $i + 1; $j < count($galaxies); $j++) {
        $pairs[] = array($galaxies[$i], $galaxies[$j]);
    }
}

foreach ($pairs as $pair) {
    $a = $pair[0];
    $b = $pair[1];
    $temp_steps = abs($a[0] - $b[0]) + abs($a[1] - $b[1]);
    $empty_rows = 0;
    $empty_cols = 0;

    foreach ($rows_no_galaxy as $index) {
        if ($index > min($a[0], $b[0]) && $index < max($a[0], $b[0])) {
            $empty_rows++;
        }
    }
    
    foreach ($columns_no_galaxy as $index) {
        if ($index > min($a[1], $b[1]) && $index < max($a[1], $b[1])) {
            $empty_cols++;
        }
    }
    $temp_steps += ($empty_rows * ($multiple-1));
    $temp_steps += ($empty_cols * ($multiple-1));

    $steps += $temp_steps;
}

var_dump($steps);