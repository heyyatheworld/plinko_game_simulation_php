<?php
class Game {
    private $data = [
        1 => ['id' => 1, 'name' => 'Иван', 'email' => 'ivan@example.com'],
        2 => ['id' => 2, 'name' => 'Мария', 'email' => 'maria@example.com'],
    ];

    public function getUser($id) {
        return isset($this->data[$id]) ? $this->data[$id] : null;
    }
}
?>
