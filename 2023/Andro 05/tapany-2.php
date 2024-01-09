<?php
$file = file('input.txt');
$seeds = [];
$maps = [];
$temp_map_array = [];
$map_name_temp = "";

$on_going = false;

foreach ($file as $line_num => $line) {
    $line = trim($line);
    if (strpos($line, "seeds:") !== false) {
        $temp_seeds = array_map("trim", array_values(array_filter(explode(" ", array_values(array_filter(explode("seeds:", $line)))[0]))));
        for ($i = 0; $i < count($temp_seeds); $i += 2) {
            $seeds[] = [$temp_seeds[$i], $temp_seeds[$i] + $temp_seeds[$i + 1] - 1];
        }
        continue;
    }

    if (strpos($line, "map:") !== false) {
        $map_name_temp = array_map("trim", array_values(array_filter(explode("map:", $line))))[0];
        $on_going = true;
        continue;
    }

    if (
        $on_going
        && $map_name_temp !== ""
        && $line !== ""
    ) {
        [$destination_start, $source_start, $range] = explode(" ", $line);
        $maps[$map_name_temp][] = [
            "source" => $source_start,
            "destination" => $destination_start,
            "range" => $range,
        ];
        continue;
    }

    if (($on_going && $line === "")
        || ($line_num === count($file) - 1)
    ) {
        if ($map_name_temp !== "") {
            $on_going = false;
            $map_name_temp = "";
        }
        continue;
    }
}

$seeds_dup = $seeds;

foreach ($maps as $map_name => $map) {
    $next = [];
    $map_dup = $map;
    usort($map_dup, function ($a, $b) {
        return $b['source'] - $a['source'];
    });

    foreach ($seeds_dup as $seed) {
        $seed_start = $seed[0];
        $seed_end = $seed[1];

        foreach ($map_dup as $row) {
            $source = $row['source'];
            $source_end = $source + $row['range'] - 1;
            $destination = $row['destination'];
            $offset = $destination - $source;

            if ( // Ao daholo
                ($seed_start >= $source && $seed_start <= $source_end)
                && ($seed_end >= $source && $seed_end <= $source_end)
            ) {
                array_push($next, [$seed_start + $offset, $seed_end + $offset]);
                $seed_start = null;
                $seed_end = null;

                break;
            } else if ( // Ao ny lohany
                ($seed_start >= $source && $seed_start <= $source_end)
                && !($seed_end >= $source && $seed_end <= $source_end)
            ) {
                array_push($next, [$seed_start + $offset, $source_end + $offset]);
                $seed_start = $source_end + 1;
            } else if ( // Ao ny rambony
                ($seed_end >= $source && $seed_end <= $source_end)
                && !($seed_start >= $source && $seed_start <= $source_end)
            ) {
                array_push($next, [$source + $offset, $seed_end + $offset]);
                $seed_end = $source - 1;
            } else { // Tsy misy ao mihitsy
            }
        }
        if ($seed_start !== null && $seed_end !== null) {
            array_push($next, [$seed_start, $seed_end]);
        }
    }

    $seeds_dup = $next;
}

usort($seeds_dup, function ($a, $b) {
    return $a[0] - $b[0];
});

var_dump($seeds_dup[0][0]);
