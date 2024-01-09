<?php
$file = file('input.txt');
$array = [];

foreach ($file as $line_num => $line) {
    $l = str_split(trim($line));
    $array[] = $l;
}

$numbers = [];
$still_number = false;
$temp_number = "";
$temp_diagonal = false;
$is_gear = false;

for ($y = 0; $y < sizeof($array); $y++) {
    for ($x = 0; $x < sizeof($array[$y]); $x++) {
        if (ctype_digit($array[$y][$x])) {
            $still_number = true;
            $temp_number .= $array[$y][$x];
            for ($i = -1; $i <= 1; $i++) {
                for ($j = -1; $j <= 1; $j++) {
                    if (
                        isset($array[$y + $i][$x + $j])
                        && !ctype_digit($array[$y + $i][$x + $j])
                        && $array[$y + $i][$x + $j] !== "."
                    ) {
                        $temp_diagonal = true;
                        if ($array[$y + $i][$x + $j] === "*") {
                            $is_gear = true;
                        } else {
                            $is_gear = false;
                        }
                    }
                }
            }
        } else {
            if ($temp_diagonal) {
                $numbers[] = $temp_number;
            }
            $still_number = false;
            $temp_number = "";
            $temp_diagonal = false;
        }
    }

    if ($temp_diagonal) {
        $numbers[] = $temp_number;
    }
    $still_number = false;
    $temp_number = "";
    $temp_diagonal = false;
}

var_dump(array_sum($numbers));