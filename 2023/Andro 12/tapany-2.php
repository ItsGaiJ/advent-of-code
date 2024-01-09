<?php
$file = file('input.txt');
$total_counts = 0;

foreach ($file as $line_num => $line) {
    $temp = explode(" ", trim($line));
    $conditions = str_repeat($temp[0] . '?', 5);
    $conditions = substr($conditions, 0, strlen($conditions) - 1);

    $damaged_springs = str_repeat($temp[1] . ',', 5);
    $damaged_springs = substr($damaged_springs, 0, strlen($damaged_springs) - 1);
    $damaged_springs = array_map('intval', explode(",", $damaged_springs));

    unset($positions);
    $positions[0] = 1;

    for ($i = 0; $i < count($damaged_springs); $i++) {
        $new_positions = [];
        foreach ($positions as $key => $value) {
            $right_damaged_springs = array_slice($damaged_springs, $i + 1);
            $limit = strlen($conditions) - array_sum($right_damaged_springs) + count($right_damaged_springs);
            for ($j = $key; $j < $limit; $j++) {
                if (
                    $j + $damaged_springs[$i] - 1 < strlen($conditions)
                    && strpos(substr($conditions, $j, $damaged_springs[$i]), '.') === false
                ) {
                    if ((($i === count($damaged_springs) - 1) && strpos(substr($conditions, $j + $damaged_springs[$i]), '#') === false)
                        || (($i < count($damaged_springs) - 1) && ($j + $damaged_springs[$i] < strlen($conditions)) && ($conditions[$j + $damaged_springs[$i]] !== '#'))
                    ) {
                        
                        $new_positions[$j + $damaged_springs[$i] + 1] = (array_key_exists($j + $damaged_springs[$i] + 1, $new_positions)) ? $new_positions[$j + $damaged_springs[$i] + 1] + $value : $value;
                    }
                }
                if ($conditions[$j] === '#') break;
            }
        }
        $positions = $new_positions;
    }
    $total_counts += array_sum(array_values($positions));
}

var_dump($total_counts);
