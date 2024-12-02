<?php
require_once '../RandSeq.php';
require_once '../Multipliers.php';
class Model {
    public float $target_rtp;
    public int $level;
    public float $result;
    public array $random_sequence;
    public array $multipliers;
   // public float $result;

    public function __construct($target_rtp = 0, $level = 0) {
        $this->target_rtp = $target_rtp;
        $this->level = $level;
        $this->result = 0;
    }

    public function calculate_round() {
        // Здесь можно добавить логику для вычисления результата
        // Например, сложение значений
        //$this->result = $this->return_to_player + $this->level + $this->number_of_games; // Пример
        $this->get_random_sequence();
        $this->get_multipliers();
        $this->result = $this->play_one_game(1);
    }

    public function get_random_sequence() {
        $rs = new RandomSequence($this->level);
        $this->random_sequence = $rs->get_sequence();
        echo "Сгенерирована последовательность: ";
        print_r($this->random_sequence);
    }

    public function get_multipliers() {
        $ms = new Multipliers($this->level, $this->target_rtp);
        $this->multipliers = $ms->get_multipliers();
        echo "Сгенерированы мультики: ";
        print_r($this->multipliers);
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