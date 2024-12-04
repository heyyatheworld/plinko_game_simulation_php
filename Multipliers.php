<?php
class Multipliers {
    /**Генерация мультипликаторов на основе уровня и целевого RTP.*/
    private int $level; // Уровень игры.
    private float $target_rtp; //Целевой RTP.
    private float $actual_rtp; //Текущий RTP.
    private float $progress; //Коэффициент для аппроксимации мультипликаторов.
    private array $probabilities; //Массив вероятностей для текущего уровня игры..
    private array $multipliers; //Массив мультипликаторов.


    public function __construct(int $level, float $rtp) {
        /** Инициализация класса с заданным уровнем и RTP. */
        $this->level = $level;
        $this->target_rtp = $rtp;
        $this->actual_rtp = 0;
        $this->progress = 2.625; // Значение прогрессии. Получено опытным путём.
        $this->probabilities = [];
        $this->multipliers = [];

        $this->calculateProbabilities();     //Считаем вероятности.
        $this->calculateMultipliers();      //Считаем мультипликаторы.
        $this->checkCondition();            //Проверяем условия.
    }

    private static function factorial(int $num): int {
        /**Вычисляет факториал числа.*/
        if ($num === 0 || $num === 1) {
            return 1;
        }
        $result = 1;
        for ($i = 2; $i <= $num; $i++) {
            $result *= $i;
        }
        return $result;
    }

    private static function calculateBinomialCoefficient(int $n, int $k): int {
        /**Вычисляет биномиальные коэффициенты.*/
        if ($k < 0 || $k > $n) {
            return 0;
        }
        return self::factorial($n) / (self::factorial($k) * self::factorial($n - $k));
    }

    private static function roundToNearestFive(float $num): float {
        /**Округляет десятичные знаки числа до ближайшей "пятёрки".*/
        $integerPart = (int)$num;
        $fractionalPart = $num - $integerPart;
        $roundedFraction = round($fractionalPart * 100 / 5) * 5 / 100;
        return $integerPart + $roundedFraction;
    }

    private function calculateProbabilities() {
        /**Вычисляет вероятности исходов. Вероятность= Бин. Коэффициент \ Сумма всех Бин. Коэффициентов.*/
        $level = $this->level + 1; // Увеличиваем уровень на 1 для создания списка
        $probs = array_fill(0, $level, 0.0);
        $binomials = array_fill(0, $level, 0);
        for ($i = 0; $i < $level; $i++) {
            $binomials[$i] = self::calculateBinomialCoefficient($level - 1, $i);
        }
        $totalBinomials = array_sum($binomials);
        for ($j = 0; $j < $level; $j++) {
            $probs[$j] = $binomials[$j] / $totalBinomials;
        }
        $this->probabilities = $probs;
    }

    private function calculateMultipliers() {
        /**Вычисляет мультипликаторы.*/
        $level = $this->level + 1;
        $result = array_fill(0, $level, 0.5);
        $n = count($result);
        $midIndex = intdiv($n, 2);
        if ($n % 2 === 0) {
            for ($i = 1; $i < $n / 2; $i++) {
                $result[$midIndex - 1 - $i] = $result[$midIndex - $i] * $this->progress;
                $result[$midIndex + $i] = $result[$midIndex + $i - 1] * $this->progress;
            }
        } else {
            for ($i = 1; $i <= intdiv($n, 2); $i++) {
                $result[$midIndex - $i] = $result[$midIndex - $i + 1] * $this->progress;
                $result[$midIndex + $i] = $result[$midIndex + $i - 1] * $this->progress;
            }
        }

        $result = array_map(function($value) {
            return round($value, 2);
        }, $result);
        $result = array_map([$this, 'roundToNearestFive'], $result);

        $this->multipliers = $result;
        $this->calculateRtp();
    }

    private function calculateRtp() {
        /**Вычисляет RTP для текущей версии(при аппроксимации) мультипликаторов.*/
        $probs = $this->probabilities;
        $mults = $this->multipliers;
        $weightedResults = array_map(function($m, $p) {
            return $m * $p;
        }, $mults, $probs);
        $this->actual_rtp = array_sum($weightedResults) * 100;
    }

    public function checkCondition() {
        /**Проверяет версию мультипликаторов на выполнение условий.*/
        $step = 0.01;
        while ($this->actual_rtp > $this->target_rtp){
            $this->progress -= $step;
            $this->calculateMultipliers();
            $this->calculateRtp();
        }
    }

    public function get_multipliers(): array {
        /**Геттер для массива мультипликаторов.*/
        return $this->multipliers;
    }

    public function get_actual_rtp(): float {
        /**Геттер для итогового RTP.*/
        return round($this->actual_rtp,2);
    }
}
