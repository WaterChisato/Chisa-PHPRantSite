<?php
require 'config.php';
if (!is_admin()) { header("Location: login.php"); exit; }

$id = (int)($_GET['id'] ?? 0);

// 删除文章
$pdo->prepare("DELETE FROM posts WHERE id=?")->execute([$id]);

// 删除文章的所有回复
$pdo->prepare("DELETE FROM replies WHERE post_id=?")->execute([$id]);

header("Location: admin.php");
exit;
