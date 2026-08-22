<?php
require_once __DIR__ . '/config/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Заполните все поля!';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
        $stmt->execute([$username]);

        if ($stmt->fetch()) {
            $error = 'Пользователь с таким логином уже существует!';
        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
            
            if ($stmt->execute([$username, $hashedPassword])) {
                $success = 'Регистрация прошла успешно! Теперь можно войти.';
            } else {
                $error = 'Ошибка при создании аккаунта.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
</head>
<body>
    <h2>Регистрация</h2>

    <?php if ($error): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p style="color: green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="POST" action="register.php">
        <div>
            <label>Логин:</label><br>
            <input type="text" name="username" required>
        </div>
        <br>
        <div>
            <label>Пароль:</label><br>
            <input type="password" name="password" required>
        </div>
        <br>
        <button type="submit">Зарегистрироваться</button>
    </form>
</body>
</html>