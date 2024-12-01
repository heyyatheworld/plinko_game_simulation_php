<?php
class Controller {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo; // Сохраняем объект PDO в классе
    }

    public function handleRequest(): array {
        $model = new Model();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['reset'])) {
                // Сброс значений
                return [new Model(), []]; // Возвращаем пустую модель и пустой массив ошибок
            } else {
                // Получение значений из формы
                $model->return_to_player = isset($_POST['value1']) ? htmlspecialchars(trim($_POST['value1'])) : '';
                $model->level = isset($_POST['value2']) ? htmlspecialchars(trim($_POST['value2'])) : '';
                $model->number_of_games = isset($_POST['value3']) ? htmlspecialchars(trim($_POST['value3'])) : '';

                // Проверка на наличие ошибок
                if (empty($model->return_to_player)) {
                    $errors[] = 'Значение 1 не должно быть пустым.';
                }
                if (empty($model->level)) {
                    $errors[] = 'Значение 2 не должно быть пустым.';
                }
                if (empty($model->number_of_games)) {
                    $errors[] = 'Значение 3 не должно быть пустым.';
                }

                // Если нет ошибок, вычисляем результат и сохраняем его
                if (empty($errors)) {
                    $model->calculate();
                    try {
                        if ($this->saveResult($model)) { // Используем сохраненный объект PDO
                            echo "Результат успешно сохранен.";
                        }
                    } catch (Exception $e) {
                        echo "Ошибка при сохранении результата: " . $e->getMessage();
                    }
                }
            }
        }

        return [$model, $errors];
    }

    public function saveResult(Model $model): bool {
        // Подготовка SQL-запроса
        $stmt = $this->pdo->prepare("INSERT INTO results (RTP, Level, Player, Result) VALUES (?, ?, ?, ?)");

        // Выполнение запроса с параметрами и обработка ошибок
        if (!$stmt->execute([$model->return_to_player, $model->level, $model->number_of_games, $model->result])) {
            throw new Exception("Ошибка при сохранении результата: " . implode(", ", $stmt->errorInfo()));
        }

        return true; // Возвращаем true при успешном выполнении
    }
}