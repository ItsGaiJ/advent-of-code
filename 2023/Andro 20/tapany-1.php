<?php
$file = file('input.txt');
$modules = [];
$button_press = 1000;

foreach ($file as $line_num => $line) {
    [$module, $destinations] = explode(' -> ', trim($line));
    $destinations = array_map('trim', explode(',', $destinations));
    if ((strpos($module, '%') !== false) || (strpos($module, '&') !== false)) {
        $module_type = $module[0];
        $module = substr($module, 1);
    }
    $modules[$module] = [
        'name' => $module,
        'type' => $module_type ?? null,
        'destinations' => $destinations,
        'on' => (($module_type ?? null) === '%') ? false : null,
        'recent_pulse' => [],
    ];
}

foreach ($modules as $key => $module) {
    if ($module['type'] === '&') {
        foreach ($modules as $ff_module) {
            if ($ff_module['type'] === '%' && in_array($key, $ff_module['destinations'])) {
                $modules[$key]['recent_pulse'][$ff_module['name']] = false;
            }
        }
    }
}

$pulses = [
    'low' => 0,
    'high' => 0,
];

for ($p = 1; $p <= $button_press; $p++) {
    $temp_pulses = pulse_propagation($modules);

    $pulses['low'] += $temp_pulses['low'];
    $pulses['high'] += $temp_pulses['high'];
}
var_dump(array_product($pulses));

function pulse_propagation(array &$modules): array
{
    $low_pulses = 1;
    $high_pulses = 0;
    $queue = [];
    $starting_module = $modules['broadcaster'];
    $starting_module['pulse'] = false;
    array_unshift($queue, $starting_module);

    while (!empty($queue)) {
        $current_module = array_shift($queue);
        foreach ($modules[$current_module['name']]['destinations'] as $dest) {
            if ($current_module['pulse'] === true) $high_pulses++;
            else $low_pulses++;

            if (isset($modules[$dest]) && $modules[$dest]['type'] === '%') {
                if ($current_module['pulse'] !== null) {
                    if ($current_module['pulse'] === false) {
                        if ($modules[$dest]['on']) {
                            $modules[$dest]['pulse'] = false;
                        } else {
                            $modules[$dest]['pulse'] = true;
                        }
                        $modules[$dest]['on'] = !$modules[$dest]['on'];

                        array_push($queue, $modules[$dest]);
                    }
                }
            } else if (isset($modules[$dest]) && $modules[$dest]['type'] === '&') {
                $modules[$dest]['recent_pulse'][$current_module['name']] = $current_module['pulse'];

                $is_all_high = true;
                foreach ($modules[$dest]['recent_pulse'] as $pulse) {
                    if ($pulse === false) $is_all_high = false;
                }

                if ($is_all_high) {
                    $modules[$dest]['pulse'] = false;
                } else {
                    $modules[$dest]['pulse'] = true;
                }
                array_push($queue, $modules[$dest]);
            }
        }
    }
    return ['high' => $high_pulses, 'low' => $low_pulses];
}
