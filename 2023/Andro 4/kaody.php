<?php
$file = file('input.txt');
$array = [];
$total_points = 0;
$copies = array_fill(0, count($file), 1);

foreach ($file as $line_num => $line) {
    $exp = explode(":", $line);
    $card_number = $exp[0];
    $exp = explode("|", $exp[1]);

    $winning_numbers = array_filter(array_map('trim', explode(" ", $exp[0])));
    $numbers = array_filter(array_map('trim', explode(" ", $exp[1])));

    $points = 0;
    $matches = 0;
    foreach ($numbers as $num) {
        if (in_array($num, $winning_numbers)) {
            $matches++;
            if ($points === 0) {
                $points++;
            } else {
                $points *= 2;
            }
        }
    }
    $total_points += $points;

    for ($j = $line_num + 1, $i = 0; $i < $matches; $j++, $i++) {
        $copies[$j] += $copies[$line_num];
    }
}

var_dump($total_points); // Part 1
var_dump(array_sum($copies)); // Part 2 
