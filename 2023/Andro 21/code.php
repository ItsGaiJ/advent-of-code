<?php
$file = file('input_test.txt');
$map = [];
$steps = 6;
$start = [];
$visited = [];
$directions = [
    [0, 1],
    [0, -1],
    [1, 0],
    [-1, 0]
];

foreach ($file as $line_num => $line) {
    $map[] = str_split(trim($line));
    if (strpos(trim($line), 'S') !== false) $start = [$line_num, strpos(trim($line), 'S')];
}

$garden_plots = 0;
for ($i = 0; $i < $steps; $i++) {

}

function get_garden_plots($start): int
{
    $queue = [$start];
    $visited = [];
    $steps = 1;
    $possible_plots = 0;
    
    while (!empty($queue)) {
        $current_plot = array_shift($queue);
    }

    return 0;
}
