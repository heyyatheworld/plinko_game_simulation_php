<?php
require_once 'Model/Model.php';
function render($model, $errors, $pdo) {
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
    <div class="container">
        <div class="left-container">
            <h1 style="text-align: center;">PLINKO DEMO</h1>
            <p style="text-align: center;">Введите три значения ниже:</p>

            <form method="post" action="">
                <label for="level">Выберите уровень:</label>
                <select name="level" id="level">
                    <?php
                    // Массив значений для выпадающего списка
                    $levels = range(7, 19); // Генерация массива значений от 7 до 19
                    foreach ($levels as $level) {
                        echo "<option value='" . htmlspecialchars($level) . "'" . ($level == 7 ? " selected" :"") . ">" . htmlspecialchars($level) . "</option>";
                    }
                    ?>
                </select>

                <label for="TargetRTP">Выберите RTP:</label>
                <select name="TargetRTP" id="TargetRTP">
                    <?php
                    // Массив значений для выпадающего списка RTP
                    $rtpLevels = range(75, 97, 2); // Генерация массива значений RTP
                    foreach ($rtpLevels as $rtp) {
                        echo "<option value='" . htmlspecialchars($rtp) . "'" . ($rtp == 77 ? " selected" :"") . ">" . htmlspecialchars($rtp) . "</option>";
                    }
                    ?>
                </select>

                <label for="number_of_games">Количество раундов:</label>
                    <input type="text" id="number_of_games" name="number_of_games" value="5000" required>
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

            <!-- Кнопка для сброса данных в таблице -->
            <form method="post" action="">
                <input type="hidden" name="reset" value="true">
                <button type="submit">Сбросить и ввести новые значения</button>
            </form>
        </div>

        <div class="right-container">
            <div class="top-right-container">
                <div class="top-right-left-container">
                <?php if (!empty($model->target_rtp) || !empty($model->level) || !empty($model->number_of_games)): ?>
                    <p style="text-align: center; font-size: 24px;">
                        Множители данного уровня:
                        <?php
                        if (isset($model) && !empty($model->multipliers)) {
                            echo htmlspecialchars(implode('  |  ', $model->multipliers)); // Используем три неразрывных пробела для разделения
                        } else {
                            echo 'Множители не определены.';
                        }
                        ?>
                    </p>
                <p style="text-align: center;">
                    Уровень:   <?php echo htmlspecialchars($model->level);?>
                    RTP:   <?php echo htmlspecialchars($model->target_rtp);?>
                    Количество игр:   <?php echo htmlspecialchars($model->number_of_games);?>
                </p>
                <?php endif; ?>
                </div>

            <div class="top-right-right-container">
                <?php if (!empty($model->target_rtp) || !empty($model->number_of_games)): ?>
                    <p style="text-align: center; font-size: 24px;">
                        RTP: <?php echo htmlspecialchars(number_format($model->actual_rtime_rtp, 2)); ?>%
                    </p>
                <?php endif; ?>
            </div>
    </div>


            <?php
            // Получение данных из базы данных
            try {
                $stmt = $pdo->query("SELECT * FROM results ORDER BY id DESC LIMIT 20");
                $results = $stmt->fetchAll();

                if (count($results) > 0) {
                    echo "<table>";
                    echo "<thead><tr><th>ID</th><th>Created at</th><th>Level</th><th>Target RTP</th><th>Bet</th><th>Result</th></tr></thead>";
                    echo "<tbody>";

                    foreach ($results as $row) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Created_at']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Level']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['TargetRTP']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Bet']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Result']) . "</td>";
                        echo "</tr>";
                    }

                    echo "</tbody></table>";
                } else {
                    echo "Нет данных для отображения.";
                }
            } catch (\PDOException $e) {
                die("Ошибка при получении данных: " . $e->getMessage());
            }
            ?>
        </div>
    </div>

    </body>
    </html>
    <?php
}