<?php
require 'config.php';
$posts = $pdo->query("SELECT * FROM posts ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>吐槽站 - 首页</title>

<style>
/* 全局基础 */
*{box-sizing:border-box;margin:0;padding:0;}
body{
    font-family:-apple-system,BlinkMacSystemFont,"Segoe UI","PingFang SC","Microsoft Yahei",sans-serif;
    background:#0f172a;
    color:#e5e7eb;
    padding:20px;
}

/* 主容器 */
.container{
    max-width:900px;
    margin:auto;
    background:#1e293b;
    border-radius:16px;
    padding:20px;
    border:1px solid #334155;
    box-shadow:0 10px 40px rgba(0,0,0,0.4);
}

/* 标题 */
.title{font-size:24px;font-weight:600;margin-bottom:10px;}
.subtitle{font-size:14px;color:#94a3b8;margin-bottom:20px;}

/* 按钮 */
.btn{
    display:inline-block;
    padding:8px 16px;
    border-radius:10px;
    background:#334155;
    color:#e5e7eb;
    text-decoration:none;
    border:1px solid #475569;
    transition:0.2s;
    font-size:14px;
}
.btn:hover{background:#475569;}
.btn-primary{background:#3b82f6;border-color:#60a5fa;}
.btn-primary:hover{background:#2563eb;}

/* 文章卡片 */
.post{
    background:#0f172a;
    border:1px solid #334155;
    padding:16px;
    border-radius:12px;
    margin-bottom:14px;
}
.post-title{font-size:18px;font-weight:600;margin-bottom:6px;}
.post-meta{font-size:12px;color:#94a3b8;margin-bottom:10px;}

/* 响应式 */
@media(max-width:600px){
    .container{padding:14px;}
    .title{font-size:20px;}
    .btn{font-size:13px;padding:6px 12px;}
}
 @media (max-width: 600px) {
  .container {
    padding: 14px;
    border-radius: 0;
  }
  .title {
    font-size: 20px;
  }
  .btn {
    font-size: 15px;
    padding: 10px 16px;
  }
  input, textarea {
    font-size: 15px;
    padding: 12px;
  }
  .reply {
    flex-direction: column;
  }
  .reply-avatar {
    margin-bottom: 8px;
  }
 }
</style>

</head>
<body>

<div class="container">

    <div class="title">吐槽站</div>
    <div class="subtitle">一个简单、轻量、美观的留言吐槽系统</div>

    <a class="btn" href="login.php">管理员登录</a>

    <hr style="border-color:#334155;margin:20px 0;">

    <?php foreach ($posts as $p): ?>
        <div class="post">
            <div class="post-title">
                <a href="reply.php?post=<?= $p['id'] ?>" style="color:#93c5fd;">
                    <?= htmlspecialchars($p['title']) ?>
                </a>
            </div>
            <div class="post-meta">发布于 <?= $p['created_at'] ?></div>
            <div><?= nl2br(htmlspecialchars(mb_strimwidth($p['content'],0,120,'...'))) ?></div>
        </div>
    <?php endforeach; ?>

</div>

</body>
</html>
