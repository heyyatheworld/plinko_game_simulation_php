<?php
require_once 'Model/Model.php';

class Controller {
    public function handleRequest() {
        $model = new Model();
        $errors = [];
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ваш код обработки формы
            //}
            //if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['reset'])) {
                // Сброс значений
                return new Model(); // Возвращаем пустую модель
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

                $model->calculate();
                if ($this->saveResult($model)) {
                    echo "Результат успешно сохранен.";
                } else {
                    echo "Ошибка при сохранении результата.";
                }
            }
        }

        return [$model, $errors];
    }

    public function saveResult($model) {
        global $pdo; // Используем глобальную переменную для доступа к PDO

        // Подготовка SQL-запроса
        $stmt = $pdo->prepare("INSERT INTO results (return_to_player, level, number_of_games, result) VALUES (?, ?, ?, ?)");

        // Выполнение запроса с параметрами
        return $stmt->execute([$model->return_to_player, $model->level, $model->number_of_games, $model->result]);
    }
}