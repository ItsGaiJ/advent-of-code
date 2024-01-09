<?php
$file = file('input.txt');
$hailstones = [];
// $area = [7, 27];
$area = [200000000000000, 400000000000000];

foreach ($file as $line_num => $line) {
    [$temp_coords, $temp_velocity] = array_map('trim', explode('@', trim($line)));
    $coords = array_map('trim', explode(',', $temp_coords));
    $velocity = array_map('trim', explode(',', $temp_velocity));
    $coords = new Coordinates(...$coords);
    $velocity = new Velocity(...$velocity);
    $hailstones[] = new Hailstone($coords, $velocity);
}

$intersection_area = 0;
for ($i = 0; $i < count($hailstones); $i++) {
    for ($j = $i + 1; $j < count($hailstones); $j++) {
        $path_intersection = $hailstones[$i]->path_intersection($hailstones[$j]);
        if ($path_intersection !== false) {
            if (intersection_in_area($path_intersection, $area) && $path_intersection['t'] > 1) {
                $intersection_area++;
            }
        }
    }
}

var_dump($intersection_area);

function intersection_in_area(array $array, array $area): bool
{
    if (($area[0] <= $array['x'] && $array['x'] <= $area[1])
        && ($area[0] <= $array['y'] && $array['y'] <= $area[1])
    ) {
        return true;
    } else {
        return false;
    }
}

class Hailstone
{
    function __construct(
        public Coordinates $coordinates,
        public Velocity $velocity,
    ) {
    }

    public function path_intersection(Hailstone $hailstone2): array|false
    {
        $slope1 = $this->velocity->y / $this->velocity->x;
        $slope2 = $hailstone2->velocity->y / $hailstone2->velocity->x;

        $intercept1 = $this->coordinates->y - $slope1 * $this->coordinates->x;
        $intercept2 = $hailstone2->coordinates->y - $slope2 * $hailstone2->coordinates->x;

        if ($slope1 == $slope2) return false;

        $x = ($intercept2 - $intercept1) / ($slope1 - $slope2);

        $y = ($slope1 * $x) + $intercept1;

        $t1 = ($x - $this->coordinates->x) / $this->velocity->x;
        $t2 = ($x - $hailstone2->coordinates->x) / $hailstone2->velocity->x;
        if ($t1 < 0 || $t2 < 0) return false;
        return ['x' => $x, 'y' => $y, 't' => $t1];
    }
}

class Coordinates
{
    function __construct(
        public int $x,
        public int $y,
        public int $z
    ) {
    }
}

class Velocity
{
    function __construct(
        public int $x,
        public int $y,
        public int $z
    ) {
    }
}
