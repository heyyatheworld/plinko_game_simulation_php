<?php
// Подключение конфигурации базы данных
//require_once __DIR__ . '/../config/database.php';
// public/index.php
require_once '../src/Model/Game.php';
require_once '../src/Controller/Controller.php';

// Установка заголовка страницы
$title = "Главная страница";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
$controller = new Controller();
$controller->show($id);
//include __DIR__ . '/../templates/footer.php';
