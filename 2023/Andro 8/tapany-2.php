<?php
$file = file('input.txt');
$directions = [];
$map = [];
$actuals = [];

$done = false;

foreach ($file as $line_num => $line) {
    $line = trim($line);
    if ($line_num == 0) {
        $directions = str_split($line);
        continue;
    }

    if ($line !== "") {
        [$name, $dirs] = array_map('trim', explode("=", $line));
        $dirs = array_map(fn ($value): string => trim($value, '() '), explode(",", $dirs));

        $map[$name] = [
            'L' => $dirs[0],
            'R' => $dirs[1],
        ];
    }
}

foreach (array_keys($map) as $name) {
    if ($name[strlen($name) - 1] === 'A') {
        $actuals[] = $name;
    }
}
$array = [];

foreach ($actuals as $key => $actual) {
    $steps = 0;
    $actu = $actual;
    $x = "";
    while ($x !== 'Z') {
        foreach ($directions as $direction) {
            $actu = $map[$actu][$direction];
            $steps++;

            $x = $actu[strlen($actuals[$key]) - 1];
            if ($x === 'Z') {
                $array[] = $steps;
                break;
            }
        }
    }
}

var_dump(lcmArray($array));

function lcmArray($numbers): int
{
    $lcm = gmp_init(1);  // Initialize with GMP 1
    foreach ($numbers as $number) {
        $lcm = gmp_lcm($lcm, $number);
    }
    return (int) gmp_strval($lcm);  // Convert GMP resource to string
}
