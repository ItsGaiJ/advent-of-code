<?php
$file = file('input.txt');
$dig_plan = [];
$current = [0, 0];
$corners = [];
$directions = ['R', 'D', 'L', 'U'];
$digged = 0;
$perimeter = 0;

foreach ($file as $line_num => $line) {
    [$direction, $length, $color] = explode(" ", trim($line));
    $color = trim(trim(trim($color, '('), ')'), '#');
    $direction = $directions[intval($color[strlen($color) - 1])];
    $length = hexdec(substr($color, 0, strlen($color) - 1));

    $dig_plan[] = [
        'direction' => $direction,
        'length' => $length
    ];
}

foreach ($dig_plan as $dig) {
    $y = $current[0];
    $x = $current[1];

    $length = $dig['length'];
    $perimeter += $length;

    if ($dig['direction'] === 'R') $x += $length;
    else if ($dig['direction'] === 'L') $x -= $length;
    else if ($dig['direction'] === 'U') $y -= $length;
    else if ($dig['direction'] === 'D') $y += $length;

    $current = [$y, $x];
    $corners[] = $current;
}

$insides = polygon_area($corners) - ($perimeter / 2) + 1; // Pick's Theorem
$digged = $perimeter + $insides;

var_dump($digged);

function polygon_area($polygon)
{
    $count = count($polygon);
    $sum1 = $sum2 = 0;
    for ($i = 0, $j = $count - 1; $i < $count; $j = $i++) {
        $a['y'] = $polygon[$i][0];
        $a['x'] = $polygon[$i][1];
        $b['y'] = $polygon[$j][0];
        $b['x'] = $polygon[$j][1];

        $sum1 += ($b['x'] * $a['y']);
        $sum2 += ($b['y'] * $a['x']);
    }
    $area = abs($sum1 - $sum2) / 2;
    return $area;
}
