<?php
// Настройки подключения к серверу MySQL
$host = '127.0.0.1:8889'; // Убедитесь, что порт соответствует вашему серверу
$user = 'root';
$pass = 'root';
$dbName = 'plinko';

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
    } else {
        echo "База данных '$dbName' уже существует.<br>";
    }

    // Подключаемся к базе данных
    $pdo = new PDO("mysql:host=$host;dbname=$dbName", $user, $pass);

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
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}
?>