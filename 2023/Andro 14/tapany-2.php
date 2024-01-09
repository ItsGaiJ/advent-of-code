<?php
$file = file('input.txt');
$platform = [];
$load = 0;
$memo = [];
$cycles = 1000000000;

foreach ($file as $line_num => $line) {
    $platform[] = str_split(trim($line));
}

cycle($cycles, $platform);

// foreach ($platform as $row) {
//     foreach ($row as $x) {
//         echo ($x);
//     }
//     echo (PHP_EOL);
// }

$platform = array_reverse($platform);
for ($i = count($platform) - 1; $i >= 0; $i--) {
    $counts = array_count_values($platform[$i]);
    if (isset($counts['O'])) {
        $load += $counts['O'] * ($i + 1);
    }
}

var_dump($load);

function cycle(int $number, array &$platform): void
{
    global $memo;
    $directions = ['north', 'west', 'south', 'east'];

    for ($i = 1; $i <= $number; $i++) {
        //echo ('cycle - ' . $i . PHP_EOL);
        foreach ($directions as $direction) {
            tilt($platform, $direction);
        }
        $state = serialize($platform);
        if (isset($memo[$state])) {
            //echo ($i . PHP_EOL);
            $cycle_start = $memo[$state];
            $cycle_length = $i - $cycle_start;
            $i += intval(($number - $i) / $cycle_length) * $cycle_length;
        }
        $memo[$state] = $i;
    }
}

function tilt(array &$platform, string $direction): void
{
    //echo('tilt - '. $direction.PHP_EOL);
    $moved = true;
    while ($moved) {
        $moved = false;
        $y_start = $x_start = 0;
        $y_end = count($platform);
        $x_end = count($platform[0]);
        if ($direction === 'north') $y_start = 1;
        if ($direction === 'south') $y_end--;
        if ($direction === 'west') $x_start = 1;
        if ($direction === 'east') $x_end--;

        for ($y = $y_start; $y < $y_end; $y++) {
            for ($x = $x_start; $x < $x_end; $x++) {
                $current = $platform[$y][$x];

                if ($current === 'O') {
                    if ($direction === 'north' && $platform[$y - 1][$x] === '.') {
                        $platform[$y - 1][$x] = 'O';
                        $platform[$y][$x] = '.';
                        $moved = true;
                    }
                    if ($direction === 'south' && $platform[$y + 1][$x] === '.') {
                        $platform[$y + 1][$x] = 'O';
                        $platform[$y][$x] = '.';
                        $moved = true;
                    }
                    if ($direction === 'west' && $platform[$y][$x - 1] === '.') {
                        $platform[$y][$x - 1] = 'O';
                        $platform[$y][$x] = '.';
                        $moved = true;
                    }
                    if ($direction === 'east' && $platform[$y][$x + 1] === '.') {
                        $platform[$y][$x + 1] = 'O';
                        $platform[$y][$x] = '.';
                        $moved = true;
                    }
                } else {
                    continue;
                }
            }
        }
    }
}