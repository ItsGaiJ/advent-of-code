<?php
$file = file_get_contents('input.txt');
$blocks = explode(PHP_EOL . PHP_EOL, $file);
$summarize = 0;

foreach ($blocks as $key => $block) {
    $block = array_map('str_split', array_map('trim', explode("\n", $block)));
    $summarize += get_reflection($block);
}

var_dump($summarize);

function get_reflection(array $block): int
{
    $vertical_size = sizeof($block);
    $horizontal_size = sizeof($block[0]);

    $vertical_part = intval($horizontal_size / 2);
    $horizontal_part = intval($vertical_size / 2);

    $vertical = false;
    $horizontal = false;

    while ($vertical === false && $vertical_part > 0) {
        $vertical = check($vertical_part, $block, 'vertical');
        $vertical_part--;
    }
    while ($horizontal === false && $horizontal_part > 0) {
        $horizontal = check($horizontal_part, $block, 'horizontal');
        $horizontal_part--;
    }

    if ($vertical !== false) {
        return $vertical;
    }

    if ($horizontal !== false) {
        return $horizontal * 100;
    }
}

function check(int $part_size, array $block, string $direction): int|false
{
    $limit = 0;
    if ($direction === 'vertical') $limit = count($block[0]) - ($part_size * 2);
    if ($direction === 'horizontal') $limit = count($block) - ($part_size * 2);

    for ($i = 0; $i < $limit + 1; $i++) {
        $current = $mirror = [];
        for ($x = 0; $x < $part_size; $x++) {
            if ($direction === 'horizontal') {
                $current[] = implode('', $block[$i + $x]);
                $mirror[] = implode('', $block[$i + $x + $part_size]);
            }

            if ($direction === 'vertical') {
                $current[] = implode('', array_column($block, $i + $x));
                $mirror[] = implode('', array_column($block, $i + $x + $part_size));
            }
        }
        $mirror = array_reverse($mirror);

        $current = implode('', $current);
        $mirror = implode('', $mirror);
        if (
            strlen($current) === strlen($mirror)
            && check_differences_string($current, $mirror) === 1
            && ($i === 0 || $i === $limit)
        ) {
            return ($i + $part_size);
        }
    }
    return false;
}

function check_differences_string(string $string1, string $string2)
{
    $differences = 0;

    for ($i = 0; $i < strlen($string1); $i++) {
        if ($string1[$i] !== $string2[$i]) {
            $differences++;
        }
    }

    return $differences;
}
