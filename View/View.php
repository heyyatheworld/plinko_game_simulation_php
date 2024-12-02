<?php
function render($model, $errors) {
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Plinko</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
    <h1>Приглашение ввести значения</h1>
    <p>Пожалуйста, введите три значения ниже:</p>

    <form method="post" action="">
        <label for="level">Выберите уровень:</label>
        <select name="level" id="level">
            <option value="">-- Выберите --</option> <!-- Пустой вариант по умолчанию -->
            <?php
            // Массив значений для выпадающего списка
            $levels = ['7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19'];
            // Генерация элементов <option> из массива
            foreach ($levels as $level) {
                echo "<option value='" . htmlspecialchars($level) . "'" . ($model->level == $level ? " selected" : "") . ">" . htmlspecialchars($level) . "</option>";
            }
            ?>
        </select>

        <label for="TargetRTP">Выберите RTP:</label>
        <select name="TargetRTP" id="TargetRTP">
            <option value="">-- Выберите --</option> <!-- Пустой вариант по умолчанию -->
            <?php
            // Массив значений для выпадающего списка RTP
            $rtpLevels = ['75', '77', '79', '81', '83', '85', '87', '89', '91', '93', '95', '97'];
            // Генерация элементов <option> из массива
            foreach ($rtpLevels as $rtp) {
                echo "<option value='" . htmlspecialchars($rtp) . "'" . ($model->target_rtp == $rtp ? " selected" : "") . ">" . htmlspecialchars($rtp) . "</option>";
            }
            ?>
        </select>

        <label for="number_of_games">Количество раундов:</label>
        <input type="text" id="number_of_games" name="number_of_games" value="<?php echo htmlspecialchars($model->number_of_games); ?>" required>

        <button type="submit">Отправить</button>
    </form>

    <?php if (!empty($errors)): ?>
        <div class="error-message">
            <h2>Ошибки:</h2>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($model->target_rtp) || !empty($model->level) || !empty($model->number_of_games)): ?>
        <h2>Вы ввели следующие значения:</h2>
        <p>Выбранный уровень: <?php echo htmlspecialchars($model->level); ?></p>
        <p>Значение RTP: <?php echo htmlspecialchars($model->target_rtp); ?></p>
        <p>Количество игр: <?php echo htmlspecialchars($model->number_of_games); ?></p>
    <?php endif; ?>

    <?php
    // Подключение к базе данных и получение данных
    $host = '127.0.0.1:8889'; // Адрес хоста
    $db = 'plinko';           // Имя базы данных
    $user = 'root';          // Имя пользователя
    $pass = 'root';          // Пароль
    $charset = 'utf8mb4';     // Кодировка

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
        // Подключение к серверу MySQL
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass, $options);

        // Выполнение SQL-запроса для получения данных
        $stmt = $pdo->query("SELECT * FROM results ORDER BY id DESC LIMIT 20");

        // Получение всех строк результата
        $results = $stmt->fetchAll();

        // Проверка наличия данных
        if (count($results) > 0) {
            // Начало HTML-таблицы
            echo "<table>";
            echo "<thead><tr><th>ID</th><th>Created At</th><th>Player</th><th>Bet</th><th>RND</th><th>Target RTP</th><th>Level</th><th>Result</th><th>Actual RTP</th></tr></thead>";
            echo "<tbody>";

            // Вывод данных в таблицу
            foreach ($results as $row) {
                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['Created_at']}</td>";
                echo "<td>{$row['Player']}</td>";
                echo "<td>{$row['Bet']}</td>";
                echo "<td>" . json_encode($row['RND']) . "</td>"; // Преобразование JSON в строку для отображения
                echo "<td>{$row['TargetRTP']}</td>";
                echo "<td>{$row['Level']}</td>";
                echo "<td>{$row['Result']}</td>";
                echo "<td>{$row['ActualRTP']}</td>";
                echo "</tr>";
            }

            // Закрытие таблицы
            echo "</tbody></table>";

        } else {
            // Если таблица пуста
            echo "Нет данных для отображения.";
        }
    } catch (\PDOException $e) {
        die("Ошибка подключения: " . $e->getMessage());
    }
    ?>

    <!-- Кнопка для сброса данных в таблице -->
    <form method="post" action="">
        <input type="hidden" name="reset" value="true">
        <button type="submit">Сбросить и ввести новые значения</button>
    </form>

    </body>
    </html>
    <?php
}

// Обработка сброса таблицы при отправке формы
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset'])) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass, $options);
        $pdo->exec("TRUNCATE TABLE results");
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (\PDOException $e) {
        die("Ошибка подключения при сбросе таблицы: " . $e->getMessage());
    }
}
?>