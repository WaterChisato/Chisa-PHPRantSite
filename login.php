<?php
require 'config.php';
$error = '';

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $u = trim($_POST['username']);
    $p = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username=?");
    $stmt->execute([$u]);
    $admin = $stmt->fetch();

    if ($admin && hash('sha256',$p)===$admin['password']) {
        $_SESSION['admin_id']=$admin['id'];
        $_SESSION['admin_name']=$admin['username'];
        header("Location: admin.php");
        exit;
    } else {
        $error="账号或密码错误";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>管理员登录</title>

<style>
body {
    font-family:-apple-system,BlinkMacSystemFont,"Segoe UI","PingFang SC","Microsoft Yahei",sans-serif;
    background:#0f172a;
    color:#e5e7eb;
    padding:20px;
}
.container {
    max-width:400px;
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
    text-align:center;
}
.error {
    background:#7f1d1d;
    padding:10px;
    border-radius:10px;
    margin-bottom:15px;
    text-align:center;
    border:1px solid #b91c1c;
}
label {
    font-size:14px;
    margin-bottom:6px;
    display:block;
}
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
.btn {
    width:100%;
    padding:12px;
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
@media(max-width:600px){
    .container { border-radius:0; padding:20px; }
    .title { font-size:22px; }
}
</style>

</head>
<body>

<div class="container">

    <div class="title">管理员登录</div>

    <?php if($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
        <label>用户名</label>
        <input name="username">

        <label>密码</label>
        <input type="password" name="password">

        <button class="btn">登录</button>
    </form>

</div>

</body>
</html>
