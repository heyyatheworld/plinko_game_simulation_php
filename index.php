<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'Controller/Controller.php';
require_once 'Model/Model.php';
require_once 'View/View.php';

// Создаем экземпляр контроллера
$controller = new Controller();

// Обрабатываем запрос и получаем модель и ошибки
list($model, $errors) = $controller->handleRequest();

// Отображаем представление с моделью и ошибками
render($model, $errors);