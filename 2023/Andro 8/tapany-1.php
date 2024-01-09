<?php
$file = file('input.txt');
$directions = [];
$map = [];
$actual = "AAA";
$steps = 0;

foreach ($file as $line_num => $line) {
    $line = trim($line);
    if ($line_num == 0) {
        $directions = str_split($line);
        continue;
    }

    if ($line !== "") {
        [$name, $dirs] = array_map('trim', explode("=", $line));
        $dirs = array_map(fn ($value): string => trim($value, '() '), explode(",", $dirs));

        $map[$name] = [
            'L' => $dirs[0],
            'R' => $dirs[1],
        ];
    }
}

while ($actual !== 'ZZZ') {
    foreach ($directions as $direction) {
        $actual = $map[$actual][$direction];
        $steps++;
    }
}
var_dump($steps);
