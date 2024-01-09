<?php
$file = file_get_contents('input.txt');
$workflows = [];
$parts = [];
[$workflows_temp, $parts_temp] = explode(PHP_EOL . PHP_EOL, $file);
$workflows_temp = array_filter(explode(PHP_EOL, $workflows_temp));
$parts_temp = array_filter(explode(PHP_EOL, $parts_temp));
$starting_workflow = 'in';

foreach ($workflows_temp as $wt) {
    [$name, $rules] = explode('{', $wt);
    $rules = explode(',', substr($rules, 0, strlen($rules) - 1));

    foreach ($rules as $key => $rule) {
        $to_check = $operator = $value = $redirection = null;
        if (strpos($rule, ':')) {
            [$rule, $redirection] = explode(':', $rule);
            $to_check = $rule[0];
            $operator = $rule[1];
            $value = substr($rule, 2);
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

foreach ($parts_temp as $pt) {
    $pt = explode(',', str_replace('}', '', str_replace('{', '', trim($pt))));
    $part_temp = [];
    foreach ($pt as $part) {
        [$a, $val] = explode('=', $part);
        $part_temp[$a] = $val;
    }
    $part_temp['status'] = null;
    $parts[] = $part_temp;
}
unset($parts_temp, $part_temp, $a, $val);

foreach($parts as $key => $part) {
    $current_workflow = $starting_workflow;
    while($parts[$key]['status'] === null) {
        $redir = null;
        foreach ($workflows[$current_workflow] as $rule) {
            if($rule['to_check'] !== null) {
                if($rule['operator'] === '>') {
                    if($part[$rule['to_check']] > $rule['value']) $redir = $rule['redirection'];
                }
                if($rule['operator'] === '<') {
                    if($part[$rule['to_check']] < $rule['value']) $redir = $rule['redirection'];
                }
            } else {
                $redir = $rule['redirection'];
            }
            if($redir !== null) break;
        }

        if($redir === 'A' || $redir === 'R') $parts[$key]['status'] = $redir;
        else $current_workflow = $redir;
    }
}

$accepted_parts = array_filter($parts, function($value) {
    if($value['status'] === 'A') return $value;
});

$total = 0;
foreach ($accepted_parts as $part) {
    $total += array_sum($part);
}
var_dump($total);
