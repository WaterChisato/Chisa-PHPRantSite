<?php
require 'config.php';
if (!is_admin()) { header("Location: login.php"); exit; }

$info = "";

/* 递归删除回复（含子回复） */
function delete_reply($pdo, $id) {
    // 删除子回复
    $stmt = $pdo->prepare("SELECT id FROM replies WHERE parent_id=?");
    $stmt->execute([$id]);
    $children = $stmt->fetchAll();

    foreach ($children as $c) {
        delete_reply($pdo, $c['id']);
    }

    // 删除自身
    $pdo->prepare("DELETE FROM replies WHERE id=?")->execute([$id]);
}

/* 删除操作 */
if (isset($_GET['del'])) {
    $id = (int)$_GET['del'];
    delete_reply($pdo, $id);
    $info = "回复已删除（含所有子回复）";
}

/* 获取所有回复 */
$replies = $pdo->query("
    SELECT r.*, p.title AS post_title
    FROM replies r
    LEFT JOIN posts p ON r.post_id = p.id
    ORDER BY r.id DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>回复管理</title>

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
.btn {
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
.btn:hover { background:#475569; }
.btn-danger {
    background:#b91c1c;
    border-color:#dc2626;
}
.btn-danger:hover { background:#7f1d1d; }

.info {
    background:#14532d;
    border:1px solid #15803d;
    padding:10px;
    border-radius:10px;
    margin-bottom:20px;
    color:#bbf7d0;
}

.reply-box {
    background:#0f172a;
    border:1px solid #334155;
    padding:14px;
    border-radius:12px;
    margin-bottom:14px;
}
.reply-meta {
    font-size:13px;
    color:#94a3b8;
    margin-bottom:6px;
}
.reply-content {
    font-size:15px;
    margin-bottom:10px;
}
.reply-actions {
    text-align:right;
}

@media(max-width:600px){
    .container { padding:14px; border-radius:0; }
    .title { font-size:22px; }
    .btn { font-size:13px; padding:6px 12px; }
}
</style>

</head>
<body>

<div class="container">

    <div class="title">回复管理</div>

    <a class="btn" href="admin.php">← 返回后台</a>

    <?php if($info): ?>
        <div class="info"><?= $info ?></div>
    <?php endif; ?>

    <?php foreach ($replies as $r): ?>
        <div class="reply-box">
            <div class="reply-meta">
                <strong><?= htmlspecialchars($r['nickname']) ?></strong>
                (<?= htmlspecialchars($r['qq']) ?>)
                · <?= $r['created_at'] ?>
            </div>

            <div class="reply-meta">
                所属文章：<?= htmlspecialchars($r['post_title'] ?: "已删除的文章") ?>
            </div>

            <div class="reply-content">
                <?= nl2br(htmlspecialchars($r['content'])) ?>
            </div>

            <div class="reply-actions">
                <a class="btn btn-danger"
                   href="?del=<?= $r['id'] ?>"
                   onclick="return confirm('确定删除此回复及其所有子回复吗？');">
                   删除
                </a>
            </div>
        </div>
    <?php endforeach; ?>

</div>

</body>
</html>
