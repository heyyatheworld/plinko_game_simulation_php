<?php

require_once 'RandomSequence.php';
require_once 'Multipliers.php';

function move_one_level_down($level, $index, $number_from_sequence)
{
    if ($number_from_sequence < 0) {
        // Двигаемся влево
        $new_index = max($index, 0);  // Убедимся, что индекс не выходит за пределы
        $level++;
        $index = $new_index;
    } elseif ($number_from_sequence > 0) {
        // Двигаемся вправо
        $new_index = $index + 1;
        $level++;
        $index = $new_index;
    }
    return array($level, $index);
}

class Game
{
    /** Симуляция игры */

    private $level;
    private $multipliers;
    private $random_sequence;

    public function __construct($level, $multipliers, $random_sequence)
    {
        $this->level = $level;
        $this->multipliers = $multipliers;
        $this->random_sequence = $random_sequence;
    }

    public function play_one_game($bet)
    {
        $level = 0;
        $index = 0;
        for ($i = 0; $i < $this->level; $i++) {
            list($level, $index) = move_one_level_down($level, $index, $this->random_sequence[$i]);
        }
        $result = $bet * $this->multipliers[$index];
        return $result;
    }
}