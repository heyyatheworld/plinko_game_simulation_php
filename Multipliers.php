<?php
class Multipliers {
    private int $level;
    private float $target_rtp;
    private float $actual_rtp;
    private float $progress;
    private array $probabilities;
    private array $multipliers;

    public function __construct(int $level, float $rtp) {
        $this->level = $level;
        $this->target_rtp = $rtp;
        $this->actual_rtp = 0;
        $this->progress = 5; // Значение прогрессии
        $this->probabilities = [];
        $this->multipliers = [];
        $this->calculateProbabilities();
        $this->calculateMultipliers();
        $this->checkCondition();
    }

    private static function factorial(int $num): int {
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
        if ($k < 0 || $k > $n) {
            return 0;
        }
        return self::factorial($n) / (self::factorial($k) * self::factorial($n - $k));
    }

    private static function roundToNearestFive(float $num): float {
        $integerPart = (int)$num;
        $fractionalPart = $num - $integerPart;
        $roundedFraction = round($fractionalPart * 100 / 5) * 5 / 100;
        return $integerPart + $roundedFraction;
    }

    private function calculateProbabilities() {
        $level = $this->level + 1; // Увеличиваем уровень на 1 для создания списка
        $probs = array_fill(0, $level, 0.0);
        $binomials = array_fill(0, $level, 0);

        // Вычисление биномиальных коэффициентов
        for ($i = 0; $i < $level; $i++) {
            $binomials[$i] = self::calculateBinomialCoefficient($level - 1, $i);
        }

        // Сумма всех биномиальных коэффициентов
        $totalBinomials = array_sum($binomials);

        // Вычисление вероятностей
        for ($j = 0; $j < $level; $j++) {
            $probs[$j] = $binomials[$j] / $totalBinomials;
        }
        $this->probabilities = $probs;
    }

    private function calculateMultipliers() {
        $level = $this->level + 1; // Увеличиваем уровень на 1 для создания списка
        $result = array_fill(0, $level, 0.5);
        $n = count($result);

        // Середина массива мультипликаторов
        $midIndex = intdiv($n, 2);

        // Обработка четного количества элементов
        if ($n % 2 === 0) {
            for ($i = 1; $i < $n / 2; $i++) {
                $result[$midIndex - 1 - $i] = $result[$midIndex - $i] * $this->progress;
                $result[$midIndex + $i] = $result[$midIndex + $i - 1] * $this->progress;
            }
        } else {
            // Обработка нечетного количества элементов
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

        $probs = $this->probabilities;
        $mults = $this->multipliers;

        // Применяем вероятности к структуре (умножаем)
        $weightedResults = array_map(function($m, $p) {
            return $m * $p;
        }, $mults, $probs);

        $this->actual_rtp = array_sum($weightedResults) * 100;

    }

    public function checkCondition() {
        // Проверяем, нужно ли уменьшать или увеличивать прогресс

        $step = 0.01;
        while (abs($this->actual_rtp - $this->target_rtp) > 10) {
            // Устанавливаем шаг изменения прогресса
            if ($this->actual_rtp > $this->target_rtp) {
                $this->progress -= $step;
            }
            else{
                    $this->progress += $step;
                }
            $this->calculateMultipliers();
            $this->calculateRtp();
        }
    }

    public function get_multipliers(): array {
        return $this->multipliers;
    }
    public function get_actual_rtp(): float {
        return round($this->actual_rtp,2);
    }
    public function get_target_rtp(): float {
        return round($this->target_rtp,2);
    }
}