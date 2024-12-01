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
        $this->result = $this->return_to_player + $this->level + $this->number_of_games; // Пример
    }
}