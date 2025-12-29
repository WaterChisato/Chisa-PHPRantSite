<?php
require 'config.php';

$post_id = (int)($_GET['post'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=?");
$stmt->execute([$post_id]);
$post = $stmt->fetch();
if (!$post) die("文章不存在");

function show_replies($pdo, $post_id, $parent = null, $level = 0) {
    $sql = "SELECT * FROM replies WHERE post_id=? AND parent_id ";
    $sql .= $parent ? "= ?" : "IS NULL";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($parent ? [$post_id, $parent] : [$post_id]);
    $rows = $stmt->fetchAll();

    foreach ($rows as $r) {
        $avatar = $r['avatar'] ?: "https://q.qlogo.cn/headimg_dl?dst_uin={$r['qq']}&spec=100";
        $mask = substr($r['qq'],0,3)."****";
        $levelClass = "level-" . min($level,3);
?>
        <div class="reply <?= $levelClass ?>">
            <div class="reply-avatar">
                <img src="<?= $avatar ?>" width="40">
            </div>
            <div class="reply-bubble">
                <div class="reply-meta"><?= htmlspecialchars($r['nickname']) ?> (<?= $mask ?>)</div>
                <div class="reply-content"><?= nl2br(htmlspecialchars($r['content'])) ?></div>
                <div class="reply-action">
                    <a href="reply.php?post=<?= $post_id ?>&reply=<?= $r['id'] ?>">回复</a>
                </div>
            </div>
        </div>
<?php
        show_replies($pdo, $post_id, $r['id'], $level + 1);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nickname = trim($_POST['nickname']);
    $qq = trim($_POST['qq']);
    $avatar = trim($_POST['avatar_url']);
    $content = trim($_POST['content']);
    $parent = $_POST['parent'] ?: null;

    $pdo->prepare("INSERT INTO replies (post_id,parent_id,nickname,qq,avatar,content)
                   VALUES (?,?,?,?,?,?)")
        ->execute([$post_id,$parent,$nickname,$qq,$avatar,$content]);

    header("Location: reply.php?post=$post_id");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?= htmlspecialchars($post['title']) ?></title>

<style>
body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "PingFang SC", "Microsoft Yahei", sans-serif;
    background: #0f172a;
    color: #e5e7eb;
    padding: 20px;
}
.container {
    max-width: 900px;
    margin: auto;
    background: #1e293b;
    border-radius: 16px;
    padding: 20px;
    border: 1px solid #334155;
    box-shadow: 0 10px 40px rgba(0,0,0,0.4);
}
.title {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 10px;
}
.subtitle {
    font-size: 14px;
    color: #94a3b8;
    margin-bottom: 20px;
}
.btn {
    display: inline-block;
    padding: 8px 16px;
    border-radius: 10px;
    background: #334155;
    color: #e5e7eb;
    text-decoration: none;
    border: 1px solid #475569;
    transition: 0.2s;
    font-size: 14px;
}
.btn:hover { background: #475569; }
.btn-primary {
    background: #3b82f6;
    border-color: #60a5fa;
}
.btn-primary:hover { background: #2563eb; }
input, textarea {
    width: 100%;
    padding: 10px;
    border-radius: 10px;
    border: 1px solid #475569;
    background: #0f172a;
    color: #e5e7eb;
    margin-top: 6px;
    margin-bottom: 14px;
    font-size: 14px;
}
.reply {
    display: flex;
    margin-bottom: 14px;
}
.reply-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    overflow: hidden;
    margin-right: 10px;
    border: 1px solid #475569;
}
.reply-bubble {
    background: #0f172a;
    border: 1px solid #334155;
    padding: 10px 14px;
    border-radius: 12px;
    width: 100%;
}
.reply-meta {
    font-size: 13px;
    color: #94a3b8;
    margin-bottom: 4px;
}
.reply-content {
    font-size: 14px;
    line-height: 1.5;
}
.reply-action {
    font-size: 12px;
    margin-top: 6px;
    color: #60a5fa;
}
.level-1 { margin-left: 20px; }
.level-2 { margin-left: 40px; }
.level-3 { margin-left: 60px; }
@media (max-width: 600px) {
    .container { padding: 14px; border-radius: 0; }
    .title { font-size: 20px; }
    .btn { font-size: 15px; padding: 10px 16px; }
    input, textarea { font-size: 15px; padding: 12px; }
    .reply { flex-direction: column; }
    .reply-avatar { margin-bottom: 8px; }
}
</style>

</head>
<body>

<div class="container">

    <a class="btn" href="index.php">← 返回首页</a>

    <div class="title"><?= htmlspecialchars($post['title']) ?></div>
    <div class="subtitle"><?= $post['created_at'] ?></div>

    <div class="post"><?= nl2br(htmlspecialchars($post['content'])) ?></div>

    <hr style="border-color:#334155;margin:20px 0;">

    <div class="title" style="font-size:20px;">回复列表</div>
    <?php show_replies($pdo, $post_id); ?>

    <hr style="border-color:#334155;margin:20px 0;">

    <div class="title" style="font-size:20px;">发表回复</div>

    <form method="post">
        <label>昵称</label>
        <input id="nickname" name="nickname">

        <label>QQ号</label>
        <input id="qq" name="qq" onblur="fetchQQ()">

        <img id="avatar" style="width:50px;height:50px;border-radius:50%;display:none;margin-bottom:10px;">

        <label>内容</label>
        <textarea name="content"></textarea>

        <input type="hidden" name="parent" value="<?= $_GET['reply'] ?? '' ?>">
        <input type="hidden" id="avatar_url" name="avatar_url">

        <button class="btn btn-primary">提交</button>
    </form>

</div>

<script>
function fetchQQ(){
    let qq = document.getElementById("qq").value.trim();
    if(!qq) return;

    fetch("http://notese.waterchisato.top/proxy.php?qq=" + qq)
    .then(r=>r.json())
    .then(d=>{
        if(d.code===200){
            document.getElementById("nickname").value = d.data.name;
            document.getElementById("avatar").src = d.data.avatar_apiurl_1;
            document.getElementById("avatar").style.display = "block";
            document.getElementById("avatar_url").value = d.data.avatar_apiurl_1;
        }
    });
}
</script>

</body>
</html>
