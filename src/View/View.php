!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Данные пользователя</title>
</head>
<body>
<h1>Данные пользователя</h1>
<p>ID: <?php echo htmlspecialchars($user['id']); ?></p>
<p>Имя: <?php echo htmlspecialchars($user['name']); ?></p>
<p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
</body>
</html>