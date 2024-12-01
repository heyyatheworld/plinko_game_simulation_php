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
                max-width: 400px;
                margin: 0 auto;
                padding: 20px;
                background-color: white;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
                color: #333;
            }
            input[type="text"] {
                width: 100%;
                padding: 10px;
                margin-bottom: 15px;
                border-radius: 4px;
                border: 1px solid #ccc;
            }
            button {
                width: 100%;
                padding: 10px;
                background-color: #5cb85c;
                color: white;
                border: none;
                border-radius: 4px;
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
        <label for="value1">Значение 1:</label>
        <input type="text" id="value1" name="value1" value="<?php echo htmlspecialchars($model->value1); ?>" required>

        <label for="value2">Значение 2:</label>
        <input type="text" id="value2" name="value2" value="<?php echo htmlspecialchars($model->value2); ?>" required>

        <label for="value3">Значение 3:</label>
        <input type="text" id="value3" name="value3" value="<?php echo htmlspecialchars($model->value3); ?>" required>

        <button type="submit">Отправить</button>
    </form>

    <?php if (!empty($errors)): ?>
        <h2>Ошибки:</h2>
        <ul class="error-message">
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if (!empty($model->value1) || !empty($model->value2) || !empty($model->value3)): ?>
        <h2>Вы ввели следующие значения:</h2>
        <p>Значение 1: <?php echo htmlspecialchars($model->value1); ?></p>
        <p>Значение 2: <?php echo htmlspecialchars($model->value2); ?></p>
        <p>Значение 3: <?php echo htmlspecialchars($model->value3); ?></p>

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