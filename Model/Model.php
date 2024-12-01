<?php
class ValueModel {
    public $return_to_player;
    public $level;
    public $number_of_games;

    public function __construct($value1 = '', $value2 = '', $value3 = '') {
        $this->return_to_player = $value1;
        $this->level = $value2;
        $this->number_of_games = $value3;
    }
}