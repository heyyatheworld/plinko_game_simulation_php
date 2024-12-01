<?php
function render($model, $errors) {
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Форма ввода значений</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                padding: 20px;
                margin: 0;
            }
            h1 {
                text-align: center;
                color: #333;
            }
            p {
                text-align: center;
                color: #666;
            }
            form {
                max-width: 400px; /* Уменьшена ширина формы */
                margin: 20px auto;
                padding: 15px; /* Уменьшены отступы */
                background-color: white;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            label {
                display: block;
                margin-bottom: 5px; /* Уменьшен отступ */
                font-weight: bold;
                color: #333;
            }
            select, input[type="text"], button {
                width: calc(100% - 10px); /* Уменьшена ширина */
                height: 30px; /* Уменьшена высота */
                font-size: 14px; /* Уменьшен размер шрифта */
                padding: 3px; /* Уменьшены отступы внутри элемента */
                border: 1px solid #ccc; /* Граница */
                border-radius: 5px; /* Закругление углов */
                margin-bottom: 10px; /* Уменьшен отступ между элементами */
            }
            button {
                background-color: #5cb85c;
                color: white;
                border: none;
                cursor: pointer; /* Курсор при наведении */
            }
            button:hover {
                background-color: #4cae4c; /* Темнее при наведении */
            }
            .error-message {
                color: red; /* Цвет сообщений об ошибках */
            }
        </style>
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
                echo "<option value='" . htmlspecialchars($level) . "'>" . htmlspecialchars($level) . "</option>";
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
                echo "<option value='" . htmlspecialchars($rtp) . "'>" . htmlspecialchars($rtp) . "</option>";
            }
            ?>
        </select>

        <label for="number_of_games">Количество раундов:</label>
        <input type="text" id="number_of_games" name="number_of_games" value="<?php echo htmlspecialchars($model->number_of_games); ?>" required>

        <button type="submit">Отправить</button>
    </form>

    <?php if (!empty($errors)): ?>
        <h2>Ошибки:</h2>
        <ul class="error-message">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if (!empty($model->return_to_player) || !empty($model->level) || !empty($model->number_of_games)): ?>
        <h2>Вы ввели следующие значения:</h2>
        <p>Значение RTP: <?php echo htmlspecialchars($model->return_to_player); ?></p>
        <p>Выбранный уровень: <?php echo htmlspecialchars($model->level); ?></p>
        <p>Количество игр: <?php echo htmlspecialchars($model->number_of_games); ?></p>

        <!-- Кнопка для перезапуска скрипта -->
        <form method="post" action="">
            <input type="hidden" name="reset" value="true">
            <button type="submit">Сбросить и ввести новые значения</button>
        </form>
    <?php endif; ?>
    </body>
    </html>
    <?php
}