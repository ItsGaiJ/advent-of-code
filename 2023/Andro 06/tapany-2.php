<?php
$file = file('input.txt');
$races = [];

foreach ($file as $line_num => $line) {
    $line = trim($line);
    if (strpos($line, "Time:") !== false) {
        $races['time'] = implode(array_values(array_filter(explode(" ", trim(explode("Time:", $line)[1])))));
    }
    if (strpos($line, "Distance:") !== false) {
        $races['distance'] = implode("", array_values(array_filter(explode(" ", trim(explode("Distance:", $line)[1])))));
    }
}
$races['possibilities'] = 0;

for ($hold = 0; $hold < $races['time']; $hold++) {
    $remaining_time = $races['time'] - $hold;
    $distance = $remaining_time * $hold;
    if ($distance > $races['distance']) {
        $races['possibilities']++;
    }
}
var_dump($races['possibilities']);
