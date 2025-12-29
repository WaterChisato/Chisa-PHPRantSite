<?php
require 'config.php';
if (!is_admin()) { header("Location: login.php"); exit; }

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if ($title && $content) {
        $pdo->prepare("INSERT INTO posts (title,content) VALUES (?,?)")
            ->execute([$title,$content]);

        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>发布文章</title>

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
    height:180px;
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
.btn:hover {
    background:#2563eb;
}
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

@media(max-width:600px){
    .container { padding:14px; border-radius:0; }
    .title { font-size:22px; }
    .btn { width:100%; font-size:17px; padding:14px; }
}
</style>

</head>
<body>

<div class="container">

    <a class="back" href="admin.php">← 返回后台</a>

    <div class="title">发布文章</div>

    <form method="post">
        <label>标题</label>
        <input name="title" placeholder="请输入文章标题">

        <label>内容</label>
        <textarea name="content" placeholder="请输入文章内容"></textarea>

        <button class="btn">发布</button>
    </form>

</div>

</body>
</html>
