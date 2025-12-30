<?php
require 'config.php';
if (!is_admin()) { header("Location: login.php"); exit; }

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=?");
$stmt->execute([$id]);
$post = $stmt->fetch();
if (!$post) die("文章不存在");

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if ($title && $content) {
        $pdo->prepare("UPDATE posts SET title=?, content=?, edited=1 WHERE id=?")
            ->execute([$title, $content, $id]);

        header("Location: admin.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>编辑文章</title>

<style>
body {
    font-family:-apple-system,BlinkMacSystemFont,"Segoe UI","PingFang SC","Microsoft Yahei",sans-serif;
    background:#0f172a;
    color:#e5e7eb;
    padding:20px;
}
.container {
    max-width:900px;
    margin:auto;
    background:#1e293b;
    border-radius:16px;
    padding:20px;
    border:1px solid #334155;
    box-shadow:0 10px 40px rgba(0,0,0,0.4);
}
.title {
    font-size:24px;
    font-weight:600;
    margin-bottom:20px;
}
label {
    font-size:14px;
    margin-bottom:6px;
    display:block;
}
input, textarea {
    width:100%;
    padding:12px;
    border-radius:10px;
    border:1px solid #475569;
    background:#0f172a;
    color:#e5e7eb;
    margin-bottom:16px;
    font-size:15px;
}
textarea {
    height:200px;
    resize:none;
}
.btn {
    padding:12px 20px;
    border-radius:10px;
    background:#3b82f6;
    border:1px solid #60a5fa;
    color:#fff;
    font-size:16px;
    cursor:pointer;
    transition:0.2s;
}
.btn:hover { background:#2563eb; }
.back {
    display:inline-block;
    margin-bottom:20px;
    padding:8px 16px;
    border-radius:10px;
    background:#334155;
    border:1px solid #475569;
    color:#e5e7eb;
    text-decoration:none;
    font-size:14px;
}
.back:hover { background:#475569; }
</style>

</head>
<body>

<div class="container">

    <a class="back" href="admin.php">← 返回后台</a>

    <div class="title">编辑文章</div>

    <form method="post">
        <label>标题</label>
        <input name="title" value="<?= htmlspecialchars($post['title']) ?>">

        <label>内容</label>
        <textarea name="content"><?= htmlspecialchars($post['content']) ?></textarea>

        <button class="btn">保存修改</button>
    </form>

</div>

</body>
</html>
