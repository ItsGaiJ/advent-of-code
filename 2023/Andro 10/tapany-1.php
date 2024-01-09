<?php
$file = file('input.txt');
$tiles = [];
$steps = 1;
$paths = [];

foreach ($file as $line_num => $line) {
    $row = str_split(trim($line));
    $pos = array_search('S', $row);
    if ($pos !== false) {
        $start = [$line_num, $pos];
    }
    $tiles[] = $row;
}

$paths_positions = get_paths_positions($tiles, $start[0], $start[1]);
foreach ($paths_positions as $key => $position) {
    $paths[$key][] = [$position[0], $position[1]];
}

$done = false;
while (!$done) {
    foreach ($paths_positions as $key => $position) {
        $pos = get_next($tiles, $position[0], $position[1], $paths[$key]);
        $paths_positions[$key] = [$pos[0], $pos[1]];
        $paths[$key][] = [$pos[0], $pos[1]];
    }
    $steps++;
    if ($paths_positions[0] === $paths_positions[1]) {
        $done = true;
    }
}

$counts = 0;
$merged_paths = array_merge($paths[0], $paths[1]);
array_unshift($merged_paths, $start);

var_dump($steps);

function get_next(array $map, int $y, int $x, array &$paths): array
{
    $current = $map[$y][$x];
    $to_left = $map[$y][$x - 1] ?? null;
    $to_right = $map[$y][$x + 1] ?? null;
    $to_up = $map[$y - 1][$x] ?? null;
    $to_down = $map[$y + 1][$x] ?? null;

    if ( // To the left
        in_array($current, ['-', 'J', '7'])
        && in_array($to_left, ['-', 'L', 'F'])
        && !in_array([$y, $x - 1], $paths)
    ) {
        return [$y, $x - 1];
    } else if ( // To the right
        in_array($current, ['-', 'L', 'F'])
        && in_array($to_right, ['-', 'J', '7'])
        && !in_array([$y, $x + 1], $paths)
    ) {
        return [$y, $x + 1];
    } else if ( // To the top
        in_array($current, ['|', 'L', 'J'])
        && in_array($to_up, ['|', '7', 'F'])
        && !in_array([$y - 1, $x], $paths)
    ) {
        return [$y - 1, $x];
    } else if ( // To the bottom
        in_array($current, ['|', '7', 'F'])
        && in_array($to_down, ['|', 'L', 'J'])
        && !in_array([$y + 1, $x], $paths)
    ) {
        return [$y + 1, $x];
    }

    return null;
}

function get_paths_positions(array $map, int $y, int $x): array
{
    $positions = [];
    $to_left = $map[$y][$x - 1] ?? null;
    $to_right = $map[$y][$x + 1] ?? null;
    $to_up = $map[$y - 1][$x] ?? null;
    $to_down = $map[$y + 1][$x] ?? null;

    if (
        in_array($to_left,  ['-', 'L', 'F'])
    ) { // To the left
        $positions[] = [$y, $x - 1];
    }

    if (
        in_array($to_right,  ['-', '7', 'J'])
    ) { // To the right
        $positions[] = [$y, $x + 1];
    }

    if (
        in_array($to_up,  ['|', '7', 'F'])
    ) { // To the top
        $positions[] = [$y - 1, $x];
    }

    if (
        in_array($to_down,  ['|', 'L', 'J'])
    ) { // To the bottom
        $positions[] = [$y + 1, $x];
    }

    return $positions;
}
