<?php
class Model {
    /**Класс для моделирования одного раунда игрового процесса.*/
    public int $level; //Заданный уровень игры.
    public float $target_rtp; //Целевой RTP.
    public float $actual_rtp; //Текущий RTP.
    public int $bet; //Размер ставки.
    public float $result; //Результат игры.
    public array $random_sequence; //Случайная последовательность для симуляции раунда игры.
    public array $multipliers; //Мультипликаторы. Зависят от уровня игры и целевого RTP.
    public int $number_of_games; //Количество игр в игровой сессии.
    public float $actual_session_rtp; //RTP игровой сессии.

    public function __construct($target_rtp = 0, $level = 0, $number_of_games = 0){
        /** Инициализация класса с заданным уровнем и RTP. */
        $this->target_rtp = $target_rtp;
        $this->actual_rtp = 0;
        $this->level = $level;
        $this->number_of_games = $number_of_games;
        $this->result = 0;
        $this->bet = 1;
        $this->random_sequence = [];
        $this->multipliers = [];
        $this->actual_session_rtp = 0;
    }

    public function calculate_round() {
        /**Подсчёт результатов игрового раунда.*/
        $this->get_random_sequence();
        $this->get_multipliers();
        $this->result = $this->play_one_game($this->bet);
    }

    public function get_random_sequence() {
        /**Получение случайной последовательности из экземпляра класса RandomSequence.*/
        $rs = new RandomSequence($this->level);
        $this->random_sequence = $rs->get_sequence();
    }

    public function get_multipliers() {
        /**Получение мультипликаторов из экземпляра класса Multipliers.*/
        $ms = new Multipliers($this->level, $this->target_rtp);
        $this->multipliers = $ms->get_multipliers();
    }

    public function move_one_level_down($level, $index, $number_from_sequence): array{
        /**Пересчитывает результат перехода на уровень ниже при движении шарика по "пирамиде".*/
        if ($number_from_sequence < 0) {
            $new_index = max($index, 0);
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
        /**Пересчитывает результаты одного игрового раунда.*/
        $level = 0;
        $index = 0;
        for ($i = 0; $i < $this->level; $i++) {
            list($level, $index) = $this->move_one_level_down($level, $index, $this->random_sequence[$i]);
        }
        return $bet * $this->multipliers[$index];
    }
}

