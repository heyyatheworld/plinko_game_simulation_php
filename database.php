<?php
$host = '127.0.0.1:8889';
$db = 'plinko';
$user = 'root';
$pass = 'root';
$charset = 'utf8mb4';

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Подключение к серверу MySQL
    $pdo = new PDO("mysql:host=$host;charset=$charset", $user, $pass, $options);

    // Проверка наличия базы данных
    if (!databaseExists($pdo, $db)) {
        createDatabase($pdo, $db);
    }

    // Подключаемся к базе данных
    $pdo->exec("USE `$db`");

    // Проверка наличия таблицы
    if (!tableExists($pdo, 'results')) {
        createTable($pdo);
    } else {
        // Опция очистки таблицы перед использованием
        //clearTable($pdo, 'results');
    }
} catch (\PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

// Функция для проверки существования базы данных
function databaseExists($pdo, $dbName) {
    $stmt = $pdo->query("SHOW DATABASES LIKE '$dbName'");
    return $stmt->rowCount() > 0;
}

// Функция для создания базы данных
function createDatabase($pdo, $dbName) {
    try {
        $pdo->exec("CREATE DATABASE `$dbName`");
    } catch (\PDOException $e) {
        die("Ошибка при создании базы данных: " . $e->getMessage());
    }
}

// Функция для проверки существования таблицы
function tableExists($pdo, $tableName) {
    $stmt = $pdo->query("SHOW TABLES LIKE '$tableName'");
    return $stmt->rowCount() > 0;
}

// Функция для создания таблицы
function createTable($pdo) {
    try {
        $createTableSQL = "CREATE TABLE results (
            id INT AUTO_INCREMENT PRIMARY KEY,
            Created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            Level INT,
            TargetRTP INT,
            Bet INT,
            Result FLOAT
        )";

        $pdo->exec($createTableSQL);
    } catch (\PDOException $e) {
        die("Ошибка при создании таблицы: " . $e->getMessage());
    }
}

// Функция для очистки таблицы
function clearTable($pdo, $tableName) {
    try {
        $pdo->exec("TRUNCATE TABLE `$tableName`");
    } catch (\PDOException $e) {
        die("Ошибка при очистке таблицы: " . $e->getMessage());
    }
}
?>