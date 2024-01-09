<?php
$file = file('input.txt');
$array = [];

foreach ($file as $line_num => $line) {
    $l = str_split(trim($line));
    $array[] = $l;
}

$numbers = [];
$gear_positions = [];
$number_positions = [];

for ($y = 0; $y < sizeof($array); $y++) {
    for ($x = 0; $x < sizeof($array[$y]); $x++) {
        if ($array[$y][$x] === "*") {
            $gear_positions[] = [$y, $x];
        }
    }
}

foreach ($gear_positions as $pos) {
    // echo("[".implode(", ", $pos)."]\n");
    for ($i = -1; $i <= 1; $i++) {
        for ($j = -1; $j <= 1; $j++) {
            $dot = $array[$pos[0] + $i][$pos[1] + $j];
            if ($dot === "." || $dot === "*") {
                continue;
            } else {
                $number_positions[] = [
                    "gear" => [$pos[0], $pos[1]],
                    "number" => [$pos[0] + $i, $pos[1] + $j]
                ];
            }
        }
    }
}
$gear_ratios = [];
foreach ($gear_positions as $pos) {
    $gear_number = [];
    foreach ($number_positions as $n_pos) {
        if ($n_pos["gear"] === $pos) {
            $y = $yy = $n_pos['number'][0];
            $x = $xx = $n_pos['number'][1];

            $num = [];

            //droite
            while (isset($array[$yy][$xx]) && ctype_digit($array[$yy][$xx]) === true) {
                array_push($num, $array[$yy][$xx]);
                $xx++;
            }

            //gauche
            $xx = $x - 1;
            while (isset($array[$yy][$xx]) && ctype_digit($array[$yy][$xx]) === true) {
                array_unshift($num, $array[$yy][$xx]);
                $xx--;
            }
            $gear_number[] = implode("", $num);
            // echo("num = ". implode("", $num) ."\n");
            // echo ("[" . implode(", ", $n_pos["number"]) . "]\n");
        }
    }
    $gear_number = array_unique($gear_number);
    if(sizeof($gear_number) > 1) {
        $gear_ratios[] = array_product($gear_number);
    }
}

var_dump(array_sum($gear_ratios));
