<?php
class Controller {
    private $pdo;


    public function __construct(PDO $pdo) {
        $this->pdo = $pdo; // Сохраняем объект PDO в классе
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
                #$model->multipliers = [1,1,1];

                // Проверка на наличие ошибок
                if (empty($model->target_rtp)) {
                    $errors[] = 'Значение не должно быть пустым.';
                }
                if (empty($model->level)) {
                    $errors[] = 'Значение не должно быть пустым.';
                }
                if (empty($model->number_of_games)) {
                    $errors[] = 'Значение не должно быть пустым.';
                }

                for($i = 0; $i < $model->number_of_games; $i++) {
                // Если нет ошибок, вычисляем результат и сохраняем его
                if (empty($errors)) {
                    $model->calculate_round();
                    try {
                        if ($this->saveResult($model)) { // Используем сохраненный объект PDO
//                            echo "Результат успешно сохранен.";
                        }
                    } catch (Exception $e) {
                        echo "Ошибка при сохранении результата: " . $e->getMessage();
                    }
                }
                }
                $model->actual_rtp = $this->get_total_results($model)/$this->get_total_bets($model);
            }
        }
        return [$model, $errors];
    }

    public function saveResult(Model $model): bool {
        // Подготовка SQL-запроса
//        $stmt = $this->pdo->prepare("INSERT INTO results (TargetRTP, Level, Player, Result) VALUES (?, ?, ?, ?)");
        $stmt = $this->pdo->prepare("INSERT INTO results (Level, TargetRTP, Bet, Result) VALUES (?, ?, ?, ?)");

        // Выполнение запроса с параметрами и обработка ошибок
        if (!$stmt->execute([$model->level, $model->target_rtp, $model->bet, $model->result])) {
            throw new Exception("Ошибка при сохранении результата: " . implode(", ", $stmt->errorInfo()));
        }

        return true; // Возвращаем true при успешном выполнении
    }

    public function get_total_bets (Model $model): float {
        // Подготовка SQL-запроса
        $stmt = $this->pdo->prepare("SELECT SUM(Bet) AS total_bet FROM results");

        // Выполнение запроса
        if (!$stmt->execute()) {
            throw new Exception("Ошибка обращения к базе: " . implode(", ", $stmt->errorInfo()));
        }

        // Получение результата
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Возвращаем общее количество записей
        $result = round((float)$result['total_bet'],2);
        return $result;
    }
    public function get_total_results (Model $model): float {
        // Подготовка SQL-запроса
        $stmt = $this->pdo->prepare("SELECT SUM(Result) AS total_result FROM results");

        // Выполнение запроса
        if (!$stmt->execute()) {
            throw new Exception("Ошибка обращения к базе: " . implode(", ", $stmt->errorInfo()));
        }

        // Получение результата
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Возвращаем общее количество записей
        $result = round((float)$result['total_result'],2);
        return $result;
    }
}