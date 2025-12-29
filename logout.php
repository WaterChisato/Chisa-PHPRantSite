<?php
require 'config.php';
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>退出登录</title>

<style>
body {
    font-family:-apple-system,BlinkMacSystemFont,"Segoe UI","PingFang SC","Microsoft Yahei",sans-serif;
    background:#0f172a;
    color:#e5e7eb;
    padding:20px;
    text-align:center;
}
.box {
    max-width:400px;
    margin:80px auto;
    background:#1e293b;
    padding:30px;
    border-radius:16px;
    border:1px solid #334155;
    box-shadow:0 10px 40px rgba(0,0,0,0.4);
}
.title {
    font-size:22px;
    margin-bottom:15px;
}
.msg {
    font-size:15px;
    color:#94a3b8;
    margin-bottom:20px;
}
.btn {
    display:inline-block;
    padding:10px 18px;
    border-radius:10px;
    background:#3b82f6;
    border:1px solid #60a5fa;
    color:#fff;
    text-decoration:none;
    font-size:15px;
}
.btn:hover { background:#2563eb; }

@media(max-width:600px){
    .box { margin-top:60px; border-radius:0; }
    .title { font-size:20px; }
}
</style>

</head>
<body>

<div class="box">
    <div class="title">已退出登录</div>
    <div class="msg">你已成功退出管理员账号</div>
    <a class="btn" href="index.php">返回首页</a>
</div>

<script>
// 3 秒后自动跳转首页
setTimeout(()=>{ location.href="index.php"; }, 3000);
</script>

</body>
</html>
