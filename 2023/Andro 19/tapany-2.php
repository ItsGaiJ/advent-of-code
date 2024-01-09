<?php
$file = file_get_contents('input.txt');
$workflows = [];
$parts = [];
[$workflows_temp, $parts_temp] = explode(PHP_EOL . PHP_EOL, $file);
$workflows_temp = array_filter(explode(PHP_EOL, $workflows_temp));
$parts_temp = array_filter(explode(PHP_EOL, $parts_temp));
$starting_workflow = 'in';

$ranges = [
    'x' => [1, 4000],
    'm' => [1, 4000],
    'a' => [1, 4000],
    's' => [1, 4000]
];

foreach ($workflows_temp as $wt) {
    [$name, $rules] = explode('{', $wt);
    $rules = explode(',', substr($rules, 0, strlen($rules) - 1));

    foreach ($rules as $key => $rule) {
        $to_check = $operator = $value = $redirection = null;
        if (strpos($rule, ':')) {
            [$rule, $redirection] = explode(':', $rule);
            $to_check = $rule[0];
            $operator = $rule[1];
            $value = intval(substr($rule, 2));
        } else $redirection = $rule;

        $workflows[$name][] = [
            'to_check' => $to_check,
            'operator' => $operator,
            'value' => $value,
            'redirection' => $redirection,
        ];
    }
}

unset($workflows_temp, $rules, $name, $to_check, $operator, $value, $redirection);

$combinations = possible_A_combinations("in", $workflows, $ranges);
var_dump($combinations);

function possible_A_combinations(string $workflow, array $workflows, array $range): int
{
    if ($workflow === 'R') {
        return 0;
    } else if ($workflow === 'A') {
        return ($range['x'][1] - $range['x'][0] + 1) *
            ($range['m'][1] - $range['m'][0] + 1) *
            ($range['a'][1] - $range['a'][0] + 1) *
            ($range['s'][1] - $range['s'][0] + 1);
    }

    $total = 0;

    foreach ($workflows[$workflow] as $rule) {
        if ($rule['to_check'] !== null) {
            $new_range = $range;

            if (
                $rule['value'] > $range[$rule['to_check']][0] // range min
                && $rule['value'] < $range[$rule['to_check']][1] // range max
            ) { 
                if ($rule['operator'] === '>') {
                    $range[$rule['to_check']][1] = $rule['value'];
                    $new_range[$rule['to_check']][0] = $rule['value'] + 1;
                } else if ($rule['operator'] === '<') {
                    $range[$rule['to_check']][0] = $rule['value'];
                    $new_range[$rule['to_check']][1] = $rule['value'] - 1;
                }
                $total += possible_A_combinations($rule['redirection'], $workflows, $new_range);
            }
        } else {
            $total += possible_A_combinations($rule['redirection'], $workflows, $range);
        }
    }

    return $total;
}
