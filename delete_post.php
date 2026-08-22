<?php
session_start();
require_once __DIR__ . '/config/db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$postId = $_GET['id'] ?? null;
if ($postId) {
    $stmt = $pdo->prepare('DELETE FROM posts WHERE id = ? AND user_id = ?');
    $stmt->execute([$postId, $_SESSION['user_id']]);
}
header('Location: index.php');
exit;

?>
