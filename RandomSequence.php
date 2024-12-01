<?php

class RandomSequence{
    /** Класс для генерации случайной последовательности значений -1 и 1. */
    private $level;

    public function __construct(int $level){
    /** Инициализация класса с заданным уровнем. */
        $this->level = $level;
    }

    public function generate_sequence(): array{
    /** Генерирует случайную последовательность значений -1 и 1 размером level. */
        return array_map(function () {
            return random_int(0,1) ? -1 : 1;
        }, range(1, $this->level));
    }

    public function generate_special_sequence(): array{
    /** Генерирует специальную последовательность значений -1 и 1 размером level. */
        $special_factor = 0.3;
        $result = $this->generate_sequence();
        while (abs(array_sum($result)) > $this->level * $special_factor) {
            $result = $this->generate_sequence();
        }
        return $result;
    }
}
