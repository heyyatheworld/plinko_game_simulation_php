<?php
// Настройки подключения к серверу MySQL
$host = '127.0.0.1';
$user = 'your_username';
$pass = 'your_password';
$dbName = 'your_database_name'; // Имя базы данных, которую нужно создать

try {
    // Подключение к серверу MySQL без указания базы данных
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Проверка наличия базы данных
    $stmt = $pdo->query("SHOW DATABASES LIKE '$dbName'");
    if ($stmt->rowCount() == 0) {
        // Если база данных не существует, создаем её
        $pdo->exec("CREATE DATABASE `$dbName`");
        echo "База данных '$dbName' успешно создана.<br>";

        // Подключаемся к новой базе данных
        $pdo->exec("USE `$dbName`");

        // Создание таблицы results
        $createTableSQL = "
            CREATE TABLE results (
                id INT AUTO_INCREMENT PRIMARY KEY,
                value1 DECIMAL(10, 2),
                value2 DECIMAL(10, 2),
                value3 DECIMAL(10, 2),
                result DECIMAL(10, 2)
            )";
        $pdo->exec($createTableSQL);
        echo "Таблица 'results' успешно создана.";
    } else {
        echo "База данных '$dbName' уже существует.<br>";
    }
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}
