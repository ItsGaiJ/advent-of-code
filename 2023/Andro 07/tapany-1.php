<?php
$file = file('input.txt');
$cards = ['A', 'K', 'Q', 'J', 'T', '9', '8', '7', '6', '5', '4', '3', '2'];
$cards = array_reverse($cards);
$ranks = [];
$hands = []; //1m56 72

foreach ($file as $line_num => $line) {
    $line = trim($line);
    $temp = explode(" ", $line);
    $hands[] = [
        'hand' => $temp[0],
        'bid' => $temp[1],
        'points' => 0,
    ];
}

foreach ($hands as $key => $hand) {
    $hand_cards = str_split($hand['hand']);
    $counts = array_count_values($hand_cards);
    $temp_counts = array_values($counts);

    switch (count($counts)) {
        case 1: // Five of a Kind
            $hands[$key]['points'] = 12;
            break;
        case 2:
            if ($temp_counts[0] === 4 || $temp_counts[1] === 4) { // Four of a Kind
                $hands[$key]['points'] = 10;
            } else if (($temp_counts[0] === 3 && $temp_counts[1] === 2)
                || ($temp_counts[0] === 2 && $temp_counts[1] === 3)
            ) { // Full House
                $hands[$key]['points'] = 8;
            }
            break;
        case 3:
            if ($temp_counts[0] === 3 || $temp_counts[1] === 3 || $temp_counts[2] === 3) { // Three of a Kind
                $hands[$key]['points'] = 6;
            } else if ($temp_counts[0] === 2 || $temp_counts[1] === 2 || $temp_counts[2] === 2) { // Two Pair
                $hands[$key]['points'] = 4;
            }
            break;
        case 4: // One Pair
            $hands[$key]['points'] = 2;

            break;
        case 5: // High Card
            $hands[$key]['points'] = 0;
            break;
        default:
            $hands[$key]['points'] = 0;
            $t = "Default";
            echo ("default\n");
            break;
    }
}


usort($hands, function ($a, $b) use ($cards) {
    if ($a['points'] !== $b['points']) {
        return $b['points'] - $a['points'];
    }

    for ($i = 0; $i < strlen($a['hand']); $i++) {
        $val1 = array_search($a['hand'][$i], $cards);
        $val2 = array_search($b['hand'][$i], $cards);

        if ($val1 !== $val2) {
            return $val2 - $val1;
        }
    }
    return 0;
});


$total_winnings = 0;
for ($i = count($hands), $j = 0; $i > 0; $i--, $j++) {
    $total_winnings += $hands[$j]['bid'] * $i;
}

var_dump($total_winnings);
