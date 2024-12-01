<?php

class Controller {
    public function show($id) {
        $userModel = new Game();
        $user = $userModel->getUser($id);

        if ($user) {
            #include '../src/View/View.php'; // Подключение представления с данными пользователя
            include __DIR__ . '/../View/View.php';
        } else {
            echo "Пользователь не найден.";
        }
    }
}

