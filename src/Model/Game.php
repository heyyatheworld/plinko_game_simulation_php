<?php

class User {
    public function getUser($id) {
        // Логика для получения пользователя из базы данных
    }
}
?>

********

<?php
namespace MyApp\Model;

class User {
    private $data = [
        1 => ['id' => 1, 'name' => 'Иван', 'email' => 'ivan@example.com'],
        2 => ['id' => 2, 'name' => 'Мария', 'email' => 'maria@example.com'],
    ];

    public function getUser($id) {
        return isset($this->data[$id]) ? $this->data[$id] : null;
    }
}
?>
