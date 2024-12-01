<?php
class ValueModel {
    public $return_to_player;
    public $level;
    public $number_of_games;
    public $result;

    public function __construct($value1 = '', $value2 = '', $value3 = '') {
        $this->return_to_player = $value1;
        $this->level = $value2;
        $this->number_of_games = $value3;
    }


    public function calculate() {
        // Здесь можно добавить логику для вычисления результата
        // Например, сложение значений
        $this->result = $this->return_to_player + $this->level + $this->number_of_games; // Пример
    }
}