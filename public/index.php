<?php
// Подключение конфигурации базы данных
//require_once __DIR__ . '/../config/database.php';

// Подключение необходимых классов и функций
//require_once __DIR__ . '/../src/Controller/HomeController.php';

// Установка заголовка страницы
$title = "Главная страница";

// Создание экземпляра контроллера
//$controller = new HomeController();

// Обработка запроса и вывод контента
//$controller->index();

// Включение шаблонов для отображения
//include __DIR__ . '/../templates/header.php';
?>

    <div id="content">
        <h1><?php echo $title; ?></h1>
        <p>Добро пожаловать на главную страницу нашего веб-приложения!</p>
    </div>

<?php
//include __DIR__ . '/../templates/footer.php'; ?>

************

<?php
// public/index.php
require_once '../src/Model/User.php';
require_once '../src/Controller/UserController.php';

use MyApp\Controller\UserController;

// Получаем ID пользователя из запроса (например, ?id=1)
$id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Создаем экземпляр контроллера и вызываем метод show
$controller = new UserController();
$controller->show($id);
?>
