<?php
session_start();
require_once __DIR__ . '/config/db.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if (empty($title) || empty($content)) {
        $error = 'Заполните все поля!';
    } else {
     
        $stmt = $pdo->prepare('INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)');
        
        if ($stmt->execute([$_SESSION['user_id'], $title, $content])) {
            header('Location: index.php');
            exit;
        } else {
            $error = 'Не удалось сохранить пост.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Создать пост</title>
</head>
<body>
    <h2>Новая публикация</h2>

    <?php if ($error): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="create_post.php">
        <div>
            <label>Заголовок:</label><br>
            <input type="text" name="title" style="width: 300px;" required>
        </div>
        <br>
        <div>
            <label>Текст поста:</label><br>
            <textarea name="content" rows="8" cols="40" required></textarea>
        </div>
        <br>
        <button type="submit">Опубликовать</button>
    </form>
    <p><a href="index.php">← На главную</a></p>
</body>
</html>