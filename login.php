<?php
session_start();
require_once __DIR__ . '/config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Заполните все поля!';
    } else {

        $stmt = $pdo->prepare('SELECT id, username, password FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        
        if ($user && password_verify($password, $user['password'])) {
        
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

           
            header('Location: index.php');
            exit;
        } else {
            $error = 'Неверный логин или пароль!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход в систему</title>
</head>
<body>
    <h2>Вход</h2>

    <?php if ($error): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php">
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
        <button type="submit">Войти</button>
    </form>
    <p><a href="register.php">Нет аккаунта? Зарегистрироваться</a></p>
</body>
</html>