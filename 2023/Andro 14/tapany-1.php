<?php
$file = file('input.txt');
$platform = [];
$moved = true;
$load = 0;

foreach ($file as $line_num => $line) {
    $platform[] = str_split(trim($line));
}

while ($moved) {
    $moved = false;
    for ($y = 1; $y < count($platform); $y++) {
        for ($x = 0; $x < count($platform[0]); $x++) {
            $current = $platform[$y][$x];

            if ($current === 'O') {
                if ($platform[$y - 1][$x] === '.') {
                    $platform[$y][$x] = '.';
                    $platform[$y - 1][$x] = 'O';
                    $moved = true;
                }
            } else {
                continue;
            }
        }
    }
}

$platform = array_reverse($platform);
for ($i = count($platform) - 1; $i >= 0; $i--) {
    $counts = array_count_values($platform[$i]);
    if (isset($counts['O'])) {
        $load += $counts['O'] * ($i + 1);
    }
}

var_dump($load);
