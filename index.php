<?php
session_start();
require_once __DIR__ . '/config/db.php';

$stmt = $pdo->query('
    SELECT posts.*, users.username 
    FROM posts 
    JOIN users ON posts.user_id = users.id 
    ORDER BY posts.created_at DESC
');
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мой Блог</title>
</head>
<body>
    <header>
        <h1>Главная страница блога</h1>
        <?php if (isset($_SESSION['user_id'])): ?>
            <p>Привет, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>! | 
               <a href="create_post.php">Создать пост</a> | 
               <a href="logout.php">Выйти</a>
            </p>
        <?php else: ?>
            <p><a href="login.php">Войти</a> | <a href="register.php">Регистрация</a></p>
        <?php endif; ?>
    </header>

    <hr>

    <main>
        <h2>Все публикации</h2>
        <?php if (empty($posts)): ?>
            <p>Постов пока нет. Будьте первым, кто опубликует запись!</p>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <article>
                    <h3><?= htmlspecialchars($post['title']) ?></h3>
                    <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                    <small>Автор: <strong><?= htmlspecialchars($post['username']) ?></strong> | <?= $post['created_at'] ?></small>
                </article>
                <hr>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</body>
</html>