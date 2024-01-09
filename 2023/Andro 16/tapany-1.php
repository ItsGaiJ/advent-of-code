<?php
$file = file('input.txt');
$tiles = [];
$beams = [
    [
        'direction' => 'right',
        'position' => [0, 0],
    ]
];
$energized = [];

foreach ($file as $line_num => $line) {
    $tiles[] = str_split(trim($line));
}

$active_beams = true;
while ($active_beams) {
    foreach ($beams as $beam_key => $beam) {
        $y = $beam['position'][0];
        $x = $beam['position'][1];

        // echo ('beam = ' . $beam_key . ' x = ' . $x . ' y = ' . $y . ' dir = ' .  $beam['direction'] . PHP_EOL);
        if (!isset($energized[$y][$x])) {
            $energized[$y][$x] = $beam['direction'];
        } else {
            if ($beam['direction'] === $energized[$y][$x]) {
                unset($beams[$beam_key]);
                break;
            }
        }

        if (
            $tiles[$y][$x] === '|'
            && ($beam['direction'] === 'right' || $beam['direction'] === 'left')
        ) {
            $beams[] = ['direction' => 'up', 'position' => [$y, $x]];
            $beams[] = ['direction' => 'down', 'position' => [$y, $x]];

            unset($beams[$beam_key]);
            break;
        } else if (
            $tiles[$y][$x] === '-'
            && ($beam['direction'] === 'up' || $beam['direction'] === 'down')
        ) {
            $beams[] = ['direction' => 'left', 'position' => [$y, $x]];
            $beams[] = ['direction' => 'right', 'position' => [$y, $x]];
            unset($beams[$beam_key]);
            break;
        } else if ($tiles[$y][$x] === '\\') {
            if ($beam['direction'] === 'right') $beams[$beam_key]['direction'] = 'down';
            else if ($beam['direction'] === 'left') $beams[$beam_key]['direction'] = 'up';
            else if ($beam['direction'] === 'up') $beams[$beam_key]['direction'] = 'left';
            else if ($beam['direction'] === 'down') $beams[$beam_key]['direction'] = 'right';
        } else if ($tiles[$y][$x] === '/') {
            if ($beam['direction'] === 'right') $beams[$beam_key]['direction'] = 'up';
            else if ($beam['direction'] === 'left') $beams[$beam_key]['direction'] = 'down';
            else if ($beam['direction'] === 'up') $beams[$beam_key]['direction'] = 'right';
            else if ($beam['direction'] === 'down') $beams[$beam_key]['direction'] = 'left';
        }

        if ($beams[$beam_key]['direction'] === 'right') {
            if (isset($tiles[$y][$x + 1])) {
                $beams[$beam_key]['position'] = [$y, $x + 1];
            } else {
                unset($beams[$beam_key]);
                break;
            }
        } else if ($beams[$beam_key]['direction'] === 'left') {
            if (isset($tiles[$y][$x - 1])) $beams[$beam_key]['position'] = [$y, $x - 1];
            else {
                unset($beams[$beam_key]);
                break;
            }
        } else if ($beams[$beam_key]['direction'] === 'up') {
            if (isset($tiles[$y - 1][$x])) $beams[$beam_key]['position'] = [$y - 1, $x];
            else {
                unset($beams[$beam_key]);
                break;
            }
        } else if ($beams[$beam_key]['direction'] === 'down') {
            if (isset($tiles[$y + 1][$x])) $beams[$beam_key]['position'] = [$y + 1, $x];
            else {
                unset($beams[$beam_key]);
                break;
            }
        }
    }

    if (count($beams) === 0) {
        $active_beams = false;
    }
}


$tiles_energized = 0;
for ($y = 0; $y < count($tiles); $y++) {
    for ($x = 0; $x < count($tiles[0]); $x++) {
        if (isset($energized[$y][$x])) {
            $tiles_energized++;
        }
    }
}
var_dump($tiles_energized);
