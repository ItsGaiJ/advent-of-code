<?php
$file = file('input.txt');
$config = [
    "red" => 12,
    "green" => 13,
    "blue" => 14,
];
$possible_games = [];
$games = [];
$sum_of_powers = [];
foreach ($file as $line_num => $line) {
    $exp = explode(":", $line);
    $game = intval(array_filter(explode("Game ", $exp[0]))[1]);
    $exp = explode(";", $exp[1]);
    $sets = [];
    foreach ($exp as $set) {
        $temp = array_map("trim", explode(",", $set));
        $sets_temp = [];
        foreach ($temp as $t) {
            $s = explode(" ", $t);
            $sets_temp[$s[1]] = $s[0];
        }
        $sets[] = $sets_temp;
    }
    $games[$game] = $sets;
}

foreach ($games as $game => $sets) {
    $minimum = [
        "red" => 0,
        "green" => 0,
        "blue" => 0,
    ];
    $possible = true;
    foreach ($sets as $set) {
        foreach (array_keys($config) as $color) {
            if (isset($set[$color]) && $set[$color] > $config[$color]) {
                $possible = false;
            }
        }
        foreach($set as $color => $number) {
            $minimum[$color] = ($minimum[$color] < $number) ? $number : $minimum[$color];
        }
    }
    if ($possible) {
        $possible_games[] = $game;
    }
    $sum_of_powers[] = array_product($minimum);
}


var_dump(array_sum($possible_games)); // Part 1
var_dump(array_sum($sum_of_powers)); // Part 2