<?php
$file = file('input.txt');
$races = [];
$race_number = 0;

foreach ($file as $line_num => $line) {
    $line = trim($line);
    if (strpos($line, "Time:") !== false) {
        $races['time'] = array_values(array_filter(explode(" ", trim(explode("Time:", $line)[1]))));
        $race_number = count($races['time']);
    }
    if (strpos($line, "Distance:") !== false) {
        $races['distance'] = array_values(array_filter(explode(" ", trim(explode("Distance:", $line)[1]))));
        $race_number = count($races['distance']);
    }
}
$races['possibilities'] = array_fill(0, $race_number, 0);

for ($i = 0; $i < $race_number; $i++) {
    for ($hold = 0; $hold < $races['time'][$i]; $hold++) {
        $remaining_time = $races['time'][$i] - $hold;
        $distance = $remaining_time * $hold;
        if ($distance > $races['distance'][$i]) {
            $races['possibilities'][$i]++;
        }
    }
}
var_dump(array_product($races['possibilities']));
