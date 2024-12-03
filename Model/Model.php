<?php
class Model {
    public int $level;
    public float $target_rtp;
    public float $actual_rtp;
    public int $bet;
    public float $result;
    public array $random_sequence;
    public array $multipliers;
    public int $number_of_games;
    public float $actual_rtime_rtp;

    public function __construct($target_rtp = 0, $level = 0, $number_of_games = 0)
    {
        $this->target_rtp = $target_rtp;
        $this->actual_rtp = 0;
        $this->level = $level;
        $this->number_of_games = $number_of_games;
        $this->result = 0;
        $this->bet = 1;
        $this->random_sequence = [];
        $this->multipliers = [];
        $this->actual_rtime_rtp = 0;
//      $this->number_of_random = 0;

    }
    public function calculate_round() {
        // Здесь можно добавить логику для вычисления результата
        // Например, сложение значений
        //$this->result = $this->return_to_player + $this->level + $this->number_of_games; // Пример
        $this->get_random_sequence();
        $this->get_multipliers();
        $this->result = $this->play_one_game($this->bet);
    }

    public function get_random_sequence() {
        $rs = new RandomSequence($this->level);
        $this->random_sequence = $rs->get_sequence();
    }

    public function get_multipliers() {
        $ms = new Multipliers($this->level, $this->target_rtp);
        $this->multipliers = $ms->get_multipliers();
    }

    public function move_one_level_down($level, $index, $number_from_sequence): array{
        if ($number_from_sequence < 0) {
            // Двигаемся влево
            $new_index = max($index, 0);  // Убедимся, что индекс не выходит за пределы
            $level++;
            $index = $new_index;
        }
        elseif ($number_from_sequence > 0) {
            // Двигаемся вправо
            $new_index = $index + 1;
            $level++;
            $index = $new_index;
        }
        return array($level, $index);
    }

    public function play_one_game($bet): float{
        $level = 0;
        $index = 0;
        for ($i = 0; $i < $this->level; $i++) {
            list($level, $index) = $this->move_one_level_down($level, $index, $this->random_sequence[$i]);
        }
        return $bet * $this->multipliers[$index];
    }
}

