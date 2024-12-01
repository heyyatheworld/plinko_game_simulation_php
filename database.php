<?php
$host = '127.0.0.1:8889';
$db = 'plinko';
$user = 'root';
$pass = 'root';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;charset=$charset"; // Подключение без указания базы данных
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Подключение к серверу MySQL
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Проверка наличия базы данных
    $stmt = $pdo->query("SHOW DATABASES LIKE '$db'");
    if ($stmt->rowCount() == 0) {
        // Если база данных не существует, создаем её
        $pdo->exec("CREATE DATABASE `$db`");
        echo "База данных '$db' успешно создана.<br>";
    } else {
        echo "База данных '$db' уже существует.<br>";
    }

    // Подключаемся к базе данных
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset"; // Обновляем DSN для подключения к базе данных
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Проверка наличия таблицы
    $tableName = 'results';
    $stmt = $pdo->query("SHOW TABLES LIKE '$tableName'");

    if ($stmt->rowCount() == 0) {
        // Если таблица не существует, создаем её
        $createTableSQL = "CREATE TABLE results (
            id INT AUTO_INCREMENT PRIMARY KEY,
            RTP DECIMAL(10, 2),
            Level DECIMAL(10, 2),
            Player DECIMAL(10, 2),
            Result DECIMAL(10, 2)
        )";

        $pdo->exec($createTableSQL);
        echo "Таблица '$tableName' успешно создана.";
    } else {
        echo "Таблица '$tableName' уже существует.";
    }
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}


