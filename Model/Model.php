<?php
class Model {
    public float $return_to_player;
    public int $level;
    public int $number_of_games;
    public float $result;

    public function __construct($return_to_player = 0, $level = 0, $number_of_games = 0) {
        $this->return_to_player = $return_to_player;
        $this->level = $level;
        $this->number_of_games = $number_of_games;
    }

    public function calculate() {
        // Здесь можно добавить логику для вычисления результата
        // Например, сложение значений
        //$this->result = $this->return_to_player + $this->level + $this->number_of_games; // Пример
        $this->result = $this->play_one_game(1);
    }

    public function get_random_sequence(): array {
        $rs = new RandomSequence($this->level);
        $rs_array = $rs->get_sequence();
        echo "Сгенерирована последовательность: ";
        print_r($rs_array);
        echo "<br />";
        return $rs_array;
    }

    public function get_multipliers(): array {
        $ms = new Multipliers($this->level, $this->return_to_player);
        $ms_array = $ms->getMultipliersList();
        echo "Сгенерированы множители: ";
        print_r($ms_array);
        echo "<br />";
        return $ms_array;
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
            list($level, $index) = $this->move_one_level_down($level, $index, $this->get_random_sequence()[$i]);
        }
        return $bet * $this->get_multipliers()[$index];
    }
}
