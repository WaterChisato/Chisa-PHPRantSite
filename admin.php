<?php
require 'config.php';
if (!is_admin()) { header("Location: login.php"); exit; }

$info = "";

// 修改管理员名称
if (isset($_GET['edit_name'])) {
    $new = trim($_POST['new']);
    if ($new) {
        $pdo->prepare("UPDATE admins SET username=? WHERE id=?")
            ->execute([$new, $_SESSION['admin_id']]);
        $_SESSION['admin_name'] = $new;
        $info = "管理员名称已修改";
    }
}

// 修改密码
if (isset($_GET['edit_pass'])) {
    $new = trim($_POST['new']);
    if ($new) {
        $pdo->prepare("UPDATE admins SET password=? WHERE id=?")
            ->execute([hash('sha256',$new), $_SESSION['admin_id']]);
        $info = "密码已修改";
    }
}

// 添加管理员
if (isset($_GET['add_admin'])) {
    $u = trim($_POST['u']);
    $p = trim($_POST['p']);
    if ($u && $p) {
        $pdo->prepare("INSERT INTO admins (username,password) VALUES (?,?)")
            ->execute([$u, hash('sha256',$p)]);
        $info = "管理员已添加";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>管理员后台</title>

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
    margin-bottom:10px;
}
.subtitle {
    font-size:14px;
    color:#94a3b8;
    margin-bottom:20px;
}
.btn {
    display:inline-block;
    padding:10px 18px;
    border-radius:10px;
    background:#334155;
    color:#e5e7eb;
    text-decoration:none;
    border:1px solid #475569;
    transition:0.2s;
    font-size:15px;
    margin-right:10px;
}
.btn:hover { background:#475569; }
.btn-primary {
    background:#3b82f6;
    border-color:#60a5fa;
}
.btn-primary:hover { background:#2563eb; }

input {
    width:100%;
    padding:12px;
    border-radius:10px;
    border:1px solid #475569;
    background:#0f172a;
    color:#e5e7eb;
    margin-bottom:16px;
    font-size:15px;
}

.section-title {
    font-size:20px;
    margin-top:25px;
    margin-bottom:10px;
}

.info {
    background:#14532d;
    border:1px solid #15803d;
    padding:10px;
    border-radius:10px;
    margin-bottom:20px;
    color:#bbf7d0;
}

@media(max-width:600px){
    .container { padding:14px; border-radius:0; }
    .title { font-size:22px; }
    .btn { font-size:14px; padding:8px 14px; margin-bottom:10px; display:inline-block; }
}
</style>

</head>
<body>

<div class="container">

    <div class="title">管理员后台</div>
    <div class="subtitle">欢迎你，<?= htmlspecialchars($_SESSION['admin_name']) ?></div>

    <a class="btn" href="index.php">返回首页</a>
    <a class="btn btn-primary" href="post.php">发布文章</a>
    <a class="btn" href="logout.php">退出登录</a>
    <a class="btn" href="reply_manage.php">回复管理</a>

    <?php if($info): ?>
        <div class="info"><?= $info ?></div>
    <?php endif; ?>

    <div class="section-title">修改管理员名称</div>
    <form method="post" action="?edit_name=1">
        <input name="new" placeholder="新的管理员名称">
        <button class="btn btn-primary">修改</button>
    </form>

    <div class="section-title">修改密码</div>
    <form method="post" action="?edit_pass=1">
        <input name="new" placeholder="新的密码">
        <button class="btn btn-primary">修改</button>
    </form>

    <div class="section-title">添加管理员</div>
    <form method="post" action="?add_admin=1">
        <input name="u" placeholder="用户名">
        <input name="p" placeholder="密码">
        <button class="btn btn-primary">添加</button>
    </form>

</div>

</body>
</html>
