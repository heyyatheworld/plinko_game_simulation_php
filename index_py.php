<?php
declare(strict_types=1);

require 'Multipliers.php';
require 'RandomSequence.php';
require 'Game.php';

$start_level = 7;
$finish_level = 18;
$top_limit_rtp = 77.00;
$bottom_limit_rtp = 75.00;
$bet = 1;

echo "\nPlinko\n";
for ($lvl = $start_level; $lvl <= $finish_level; $lvl++) {

    echo sprintf("Уровень: %2.0f\n", $lvl);
    echo "Множители  : ";
    $multipliers = new Multipliers($lvl, $top_limit_rtp);
    foreach ($multipliers->getMultipliersList() as $mult) {
        echo sprintf("[%5.2f]", $mult);
    }
    echo "\n";

    $random_sequence = new RandomSequence($lvl);

    echo sprintf("Целевой RTP: %5.2f   ", $top_limit_rtp);
    $total_bet = 0;
    $total_win = 0;
    $current_rtp = 0;
    for ($i = 0; $i < 3000; $i++) {
        $game_instance = new Game($lvl, $multipliers->getMultipliersList(), $random_sequence->get_sequence(), $bet);
        $total_win += $game_instance->play_one_game($bet);
        $total_bet += $bet;
        $current_rtp = ($total_win / $total_bet) * 100;
    }
    echo sprintf("Итоговый RTP: %6.2f/%6.2f = %6.2f\n", $total_bet, $total_win, $current_rtp);

    echo sprintf("Целевой RTP: %5.2f   ", $bottom_limit_rtp);
    $total_bet = 0;
    $total_win = 0;
    $current_rtp = 0;
    for ($i = 0; $i < 3000; $i++) {
        if ($current_rtp > $bottom_limit_rtp) {
            $game_instance = new Game($lvl, $multipliers->getMultipliersList(), $random_sequence->set_sp_sequence(), $bet);
            $total_win += $game_instance->play_one_game($bet);
            $total_bet += $bet;
        } else {
            $game_instance = new Game($lvl, $multipliers->getMultipliersList(), $random_sequence->get_sequence(), $bet);
            $total_win += $game_instance->play_one_game($bet);
            $total_bet += $bet;
        }

        $current_rtp = ($total_win / $total_bet) * 100;
    }
    echo sprintf("Итоговый RTP: %6.2f/%6.2f = %6.2f\n", $total_bet, $total_win, $current_rtp);
    echo "\n";
}