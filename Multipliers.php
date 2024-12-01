<?php
class Multipliers {
    private $level;
    private $rtp;
    private $progress;

    public function __construct(int $level, float $rtp) {
        $this->level = $level;
        $this->rtp = $rtp;
        $this->progress = 1.618; // Значение прогрессии
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

    public function calculateProbabilities(): array {
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

        return $probs;
    }

    public function calculateMultipliers(): array {
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

        return $result;
    }

    public function calculateRtp(): float {
        $multipliers = $this->calculateMultipliers();
        $probs = $this->calculateProbabilities();

        // Применяем вероятности к структуре (умножаем)
        $weightedResults = array_map(function($m, $p) {
            return $m * $p;
        }, $multipliers, $probs);

        $result = array_sum($weightedResults) * 100;

        return $result;
    }

    public function checkCondition(): array {
        $currentResult = $this->calculateRtp();

        if ($currentResult > $this->rtp) {
            while ($currentResult > $this->rtp) {
                $this->progress -= 0.01;
                $currentResult = $this->calculateRtp();
            }
        } else {
            while ($currentResult < $this->rtp) {
                $this->progress += 0.01;
                $currentResult = $this->calculateRtp();
            }
        }

        // Вывод результатов
        $multipliers = $this->calculateMultipliers();
        return $multipliers;
    }

    public function getMultipliersList(): array {
        return $this->checkCondition();
    }
}