<?php
$file = file('input.txt');
$seeds = [];
$seeds2 = [];
$on_going = false;
$maps = [];
$map_name_temp = "";
$temp_array = [];
$convertion_tables = [];
$keys = [];

foreach ($file as $line_num => $line) {
    $line = trim($line);
    if (strpos($line, "seeds:") !== false) {
        $temp_seeds = array_map("trim", array_values(array_filter(explode(" ", array_values(array_filter(explode("seeds:", $line)))[0]))));
        $seeds2 = array_map("trim", array_values(array_filter(explode(" ", array_values(array_filter(explode("seeds:", $line)))[0]))));
        $keys = array_fill(0, count($seeds2), false);

        foreach ($temp_seeds as $seed) {
            $seeds[] = ["seed" => $seed, "location" => null];
        }
        continue;
    }

    if (strpos($line, "map:") !== false) {
        $map_name_temp = array_map("trim", array_values(array_filter(explode("map:", $line))))[0];
        $on_going = true;
        $keys = array_fill(0, count($seeds2), false);
        continue;
    }

    if (
        $on_going
        && $map_name_temp !== ""
        && $line !== ""
    ) {
        foreach ($seeds2 as $key => $seed) {
            [$destination_start, $source_start, $range] = explode(" ", $line);
            if ($seed >= $source_start && $seed <= ($source_start + $range - 1) && !$keys[$key]) {
                $seeds2[$key] = $destination_start + abs($source_start - $seed);
                $keys[$key] = true;
            }
        }
        continue;
    }

    if (($on_going && $line === "")
        || ($line_num === count($file) - 1)
    ) {
        if ($map_name_temp !== "") {
            $maps[$map_name_temp] = $temp_array;
            $temp_array = [];
            $on_going = false;
            $map_name_temp = "";
            $keys = array_fill(0, count($seeds2), false);
        }
        continue;
    }
}

$lowest_location = min($seeds2);
var_dump($lowest_location);
