<?php
class Controller {
    /**Управление процессом. Ответ на запросы. Работа с базой данных.*/
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function handleRequest(): array {
        $model = new Model;
        $errors = [];

        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['reset'])) {
                // Сброс значений
                clearTable($this->pdo, 'results');
                return [new Model(), []]; // Возвращаем пустую модель и пустой массив ошибок
            } else {
                // Получение значений из формы
                $model->level = isset($_POST['level']) ? htmlspecialchars(trim($_POST['level'])) : '';
                $model->target_rtp = isset($_POST['TargetRTP']) ? htmlspecialchars(trim($_POST['TargetRTP'])) : '';
                $model->number_of_games = isset($_POST['number_of_games']) ? htmlspecialchars(trim($_POST['number_of_games'])) : '';

                for($i = 0; $i < $model->number_of_games; $i++) {
                    if (empty($errors)) {
                        $model->calculate_round();
                        try {
                            $this->saveResult($model);
                        } catch (Exception $e) {
                            echo "Ошибка при сохранении результата: " . $e->getMessage();
                        }
                    }
                }
                //Пересчитывает RTP игровой сессии после каждой игры.
                $model->actual_session_rtp = $this->get_total_results($model)/$this->get_total_bets($model)*100;
            }
        }
        return [$model, $errors];
    }

    public function saveResult(Model $model): bool {
        /**Сохраняет в базу данных результат каждой игры.*/
        $stmt = $this->pdo->prepare("INSERT INTO results (Level, TargetRTP, Bet, Result) VALUES (?, ?, ?, ?)");
        if (!$stmt->execute([$model->level, $model->target_rtp, $model->bet, $model->result])) {
            throw new Exception("Ошибка при сохранении результата: " . implode(", ", $stmt->errorInfo()));
        }
        return true;
    }

    public function get_total_bets (Model $model): float {
        /**Получает из базы данных сумму всех ставок за игровую сессию.*/
        $stmt = $this->pdo->prepare("SELECT SUM(Bet) AS total_bet FROM results");
        if (!$stmt->execute()) {
            throw new Exception("Ошибка обращения к базе: " . implode(", ", $stmt->errorInfo()));
        }
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $result = round((float)$result['total_bet'],2);
        return $result;
    }

    public function get_total_results (Model $model): float {
        /**Получает из базы данных сумму всех выигрышей за игровую сессию.*/
        $stmt = $this->pdo->prepare("SELECT SUM(Result) AS total_result FROM results");
        if (!$stmt->execute()) {
            throw new Exception("Ошибка обращения к базе: " . implode(", ", $stmt->errorInfo()));
        }
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $result = round((float)$result['total_result'],2);
        return $result;
    }
}