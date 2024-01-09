<?php
$file = file('input.txt');
$tiles = [];

foreach ($file as $line_num => $line) {
    $tiles[] = str_split(trim($line));
}

$energized_number = [];

for ($yy = 0; $yy < count($tiles); $yy++) {
    for ($xx = 0; $xx < count($tiles[0]); $xx++) {
        if ($xx === 0 || $yy === 0 || $yy === (count($tiles) - 1) || $xx === (count($tiles[0]) - 1)) {
            $directions = [];

            if ($xx === 0 && $yy === 0) {
                $directions[] = ['right', 'down'];
            } else if ($xx === (count($tiles[0]) - 1) && $yy === 0) {
                $directions[] = ['left', 'down'];
            } else if ($xx === 0 && $yy === (count($tiles) - 1)) {
                $directions[] = ['left', 'up'];
            } else if ($xx === (count($tiles[0]) - 1) && $yy === (count($tiles) - 1)) {
                $directions[] = ['right', 'up'];
            } else {
                if ($xx === 0) $directions[] = 'left';
                if ($xx === (count($tiles[0]) - 1)) $directions[] = 'right';
                if ($yy === 0) $directions[] = 'down';
                if ($yy === (count($tiles) - 1)) $directions[] = 'up';
            }

            foreach ($directions as $direction) {
                $beams = [['direction' => $direction, 'position' => [$yy, $xx]]];

                $energized = [];
                $active_beams = true;
                while ($active_beams) {
                    foreach ($beams as $beam_key => $beam) {
                        $y = $beam['position'][0];
                        $x = $beam['position'][1];
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
                        if (isset($energized[$y][$x])) $tiles_energized++;
                    }
                }
                $energized_number[] = [[$yy, $xx], $tiles_energized];
            }
        }
    }
}

usort($energized_number, function($a, $b) {
    return $b[1] - $a[1];
});
var_dump($energized_number[0][1]);
