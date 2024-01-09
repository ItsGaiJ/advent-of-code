<?php
$file = file('input.txt');
$galaxies = [];
$pairs = [];
$map = [];
$width = 0;
$distances = 0;

foreach ($file as $line_num => $line) {
    $line = str_split(trim($line));
    $width = count($line);

    if (count(array_unique($line)) == 1) {
        $map[] = $line;
        $map[] = $line;
    } else {
        $map[] = $line;
    }
}

$empty_cols = [];
for ($i = 0; $i < $width; $i++) {
    $column = array_column($map, $i);
    if (count(array_unique($column)) == 1) {
        $empty_cols[] = $i;
    }
}

foreach ($empty_cols as $key => $col) {
    $col_id = $key + $col;
    for ($i = 0; $i < count($map); $i++) {
        array_splice($map[$i], $col_id, 0, '.');
    }
}

$width = count($map[0]);
for ($y = 0; $y < count($map); $y++) {
    for ($x = 0; $x < $width; $x++) {
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
    $distances += count(get_paths($pair[1], $pair[0]));
}

var_dump($distances);

function get_paths($a, $b)
{
    $dx = abs($b[1] - $a[1]);
    $dy = abs($b[0] - $a[0]);
    $sx = ($a[1] < $b[1]) ? 1 : -1;
    $sy = ($a[0] < $b[0]) ? 1 : -1;
    $err = $dx - $dy;
    $points = [];

    while (true) {
        if (($a[1] === $b[1]) && ($a[0] === $b[0])) break;
        $e2 = 2 * $err;
        if ($e2 > -$dy) {
            $err -= $dy;
            $a[1] += $sx;
            $points[] = [$a[0], $a[1]];
        }
        if ($e2 < $dx) {
            $err += $dx;
            $a[0] += $sy;
            $points[] = [$a[0], $a[1]];
        }
    }
    return $points;
}
