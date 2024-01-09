<?php
$file = file('input.txt');
$histories = [];
$extrapolated = 0;

foreach ($file as $line_num => $line) {
    $histories[$line_num] = explode(" ", trim($line));
}

foreach ($histories as $history) {
    $next_arrays = [];
    $next_arrays[] = $last = $history;
    $x = 0;

    while (!empty($last)) {
        $temp_array = [];
        for ($i = 0; $i < count($next_arrays[$x]) - 1; $i++) {
            $temp_array[] = $next_arrays[$x][$i + 1] - $next_arrays[$x][$i];
        }
        $next_arrays[] = $temp_array;
        $last = array_filter($temp_array);
        $x++;
    }

    for ($i = count($next_arrays) - 1; $i >= 0; $i--) {
        if ($i == count($next_arrays) - 1) {
            $next_arrays[$i][] = 0;
        } else {
            $next_arrays[$i][] = end($next_arrays[$i]) + end($next_arrays[$i + 1]);
        }
    }

    $extrapolated += end($next_arrays[0]);
}

var_dump($extrapolated);
