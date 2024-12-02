<?php

class RandomSequence{
    /** Класс для генерации случайной последовательности значений -1 и 1. */
    private int $level;
    private array $sequence;
    private array $sp_sequence;

    public function __construct(int $level){
    /** Инициализация класса с заданным уровнем. */
        $this->level = $level;
        $this->sequence = [];
        $this->sp_sequence = [];
        $this->set_sequence();
        $this->set_sp_sequence();
    }

    public function set_sequence_level(int $new_level)
    {
        $this->level = $new_level;
        $this->sequence = [];
        $this->sp_sequence = [];
        $this->set_sequence();
        $this->set_sp_sequence();
    }

    public function set_sequence() {
        /** Генерирует и записывает случайную последовательность значений -1 и 1 размером level. */
        $this->sequence = array_map(function () {
            return random_int(0,1) ? -1 : 1;
        }, range(1, $this->level));
    }

    public function get_sequence(): array{
        /** Возвращает записанную случайную последовательность значений -1 и 1 размером level. */
        return $this->sequence;
    }

    public function set_sp_sequence() {
    /** Генерирует специальную последовательность значений -1 и 1 размером level. */
        $special_factor = 0.5;

        $this->sp_sequence = array_map(function () {
            return random_int(0,1) ? -1 : 1;
        }, range(1, $this->level));

        while (abs(array_sum($this->sp_sequence)) > $this->level * $special_factor) {
            $this->sp_sequence = array_map(function () {
                return random_int(0,1) ? -1 : 1;
            }, range(1, $this->level));
        }
    }

    public function get_sp_sequence(): array{
        /** Возвращает записанную случайную последовательность значений -1 и 1 размером level. */
        return $this->sp_sequence;
    }
}