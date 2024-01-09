<?php
$file = file('input.txt');
$map = [];
$start = null;
$finish = null;
$paths = [];
$directions = [
    [0, 1, '>'],
    [0, -1, '<'],
    [1, 0, 'v'],
    [-1, 0, '^'],
];

foreach ($file as $line_num => $line) {
    if ($line_num === 0) $start = [$line_num, strpos(trim($line), '.')];
    if (!isset($file[$line_num + 1])) $finish = [$line_num, strpos(trim($line), '.')];

    $row = str_split(trim($line));
    $map[] = $row;
}

$slopes = get_slopes($map);
$slopes[] = $start;
$slopes[] = $finish;

$paths = get_slopes_neighbors($slopes, $map);

$max_steps = dfs($start, $finish, $paths);

var_dump($max_steps);

function dfs(array &$start, array &$end, array &$paths): int
{
    $max_steps = 0;
    $stack = [[$start, 0]];
    $visited = [];

    while (!empty($stack)) {
        [$current, $current_distance] = array_pop($stack);

        if (isset($visited[$current[0]][$current[1]])) continue;
        $visited[$current[0]][$current[1]] = true;

        if ($current === $end) {
            $max_steps = max($max_steps, $current_distance);
        }

        foreach ($paths[$current[0]][$current[1]] as [$slo_y, $slo_x, $slo_dist]) {
            if (!isset($visited[$slo_y][$slo_x])) {
                array_push($stack, [[$slo_y, $slo_x], $current_distance + $slo_dist]);
            }
        }
        unset($visited[$current[0]][$current[1]]);
    }

    return $max_steps;
}

function get_slopes_neighbors(array &$slopes, array &$map): array
{
    global $directions;
    $paths = [];

    foreach ($slopes as [$slo_y, $slo_x]) {
        $paths[$slo_y][$slo_x] = [];
        $queue = [[$slo_y, $slo_x, 0]];
        $visited = [];

        while (!empty($queue)) {
            [$cur_y, $cur_x, $cur_dist] = array_shift($queue);
            if (isset($visited[$cur_y][$cur_x]) && $visited[$cur_y][$cur_x]) continue;
            $visited[$cur_y][$cur_x] = true;

            if (
                in_array([$cur_y, $cur_x], $slopes)
                && [$cur_y, $cur_x] !== [$slo_y, $slo_x]
            ) {
                $paths[$slo_y][$slo_x][] = [$cur_y, $cur_x, $cur_dist];
                continue;
            }

            foreach ($directions as [$dir_y, $dir_x, $dir]) {
                if (isset($map[$cur_y + $dir_y][$cur_x + $dir_x]) && $map[$cur_y + $dir_y][$cur_x + $dir_x] !== '#') {
                    if (
                        in_array($map[$cur_y + $dir_y][$cur_x + $dir_x], ['<', '>', 'v', '^'])
                        && $map[$cur_y + $dir_y][$cur_x + $dir_x] !== $dir
                    ) {
                        continue;
                    } else {
                        array_push($queue, [$cur_y + $dir_y, $cur_x + $dir_x, $cur_dist + 1]);
                    }
                }
            }
        }
    }

    return $paths;
}

function get_slopes(array &$map): array
{
    global $directions;
    $slopes = [];

    for ($y = 0; $y < count($map); $y++) {
        for ($x = 0; $x < count($map[0]); $x++) {
            if (isset($map[$y][$x]) && $map[$y][$x] === '.') {
                $ways = 0;
                foreach ($directions as [$dir_y, $dir_x,]) {
                    if (
                        isset($map[$y + $dir_y][$x + $dir_x])
                        && in_array($map[$y + $dir_y][$x + $dir_x], ['<', '>', 'v', '^'])
                    ) {
                        $ways++;
                    }
                }

                if ($ways > 2) {
                    $slopes[] = [$y, $x];
                }
            }
        }
    }

    return $slopes;
}
