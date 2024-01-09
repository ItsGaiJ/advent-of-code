<?php
$file = file('input_test.txt');
$bricks = [];

foreach ($file as $line_num => $line) {
    [$end1, $end2] = explode('~', trim($line));
    $end1 = array_map('intval', explode(',', $end1));
    $end2 = array_map('intval', explode(',', $end2));
    $end1 = new BrickEnd($end1[0], $end1[1], $end1[2]);
    $end2 = new BrickEnd($end2[0], $end2[1], $end2[2]);
    $bricks[] = new Brick($end1, $end2);
}

usort($bricks, function ($brick1, $brick2) {
    return $brick1->end2->z - $brick2->end2->z;
});
var_dump($bricks);

function check_under(array $brick, array &$bricks): bool
{
    $under_free = true;

    return $under_free;
}

class Brick
{
    function __construct(
        public BrickEnd $end1,
        public BrickEnd $end2,
    ) {
    }

}

class BrickEnd
{
    function __construct(
        public int $x,
        public int $y,
        public int $z
    ) {
    }
}
