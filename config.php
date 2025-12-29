<?php
session_start();

/* -------------------------
   使用你的数据库配置
-------------------------- */
$DB_HOST = 'localhost';
$DB_NAME = '名字';
$DB_USER = '数据库用户名';
$DB_PASS = '数据库密码';

/* -------------------------
   连接数据库
-------------------------- */
try {
    // 连接 MySQL（不指定数据库）
    $pdo0 = new PDO(
        "mysql:host={$DB_HOST};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    // 创建数据库
    $pdo0->exec("CREATE DATABASE IF NOT EXISTS `{$DB_NAME}` DEFAULT CHARSET utf8mb4");

    // 连接指定数据库
    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

} catch (PDOException $e) {
    die('数据库连接失败：' . $e->getMessage());
}

/* -------------------------
   自动建表（如果不存在）
-------------------------- */

// 管理员表
$pdo->exec("
CREATE TABLE IF NOT EXISTS admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

// 默认管理员
if ($pdo->query("SELECT COUNT(*) FROM admins")->fetchColumn() == 0) {
    $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)")
        ->execute(['admin', hash('sha256', '123456')]);
}

// 文章表
$pdo->exec("
CREATE TABLE IF NOT EXISTS posts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

// 回复表（初始建表）
$pdo->exec("
CREATE TABLE IF NOT EXISTS replies (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

/* -------------------------
   自动补字段（核心）
-------------------------- */
function ensure_column($pdo, $table, $column, $definition) {
    $check = $pdo->prepare("SHOW COLUMNS FROM `$table` LIKE ?");
    $check->execute([$column]);
    if ($check->rowCount() === 0) {
        $pdo->exec("ALTER TABLE `$table` ADD COLUMN `$column` $definition;");
    }
}

// 必须字段
ensure_column($pdo, 'replies', 'nickname', 'VARCHAR(50) NOT NULL AFTER id');
ensure_column($pdo, 'replies', 'qq', 'VARCHAR(20) NOT NULL AFTER nickname');
ensure_column($pdo, 'replies', 'avatar', 'VARCHAR(255) DEFAULT NULL AFTER qq');
ensure_column($pdo, 'replies', 'post_id', 'INT UNSIGNED NOT NULL DEFAULT 0 AFTER avatar');
ensure_column($pdo, 'replies', 'parent_id', 'INT UNSIGNED DEFAULT NULL AFTER post_id');
// 如果存在 comment_id 字段，则让它允许 NULL，避免报错
try {
    $check = $pdo->query("SHOW COLUMNS FROM replies LIKE 'comment_id'");
    if ($check->rowCount() > 0) {
        // 让 comment_id 允许 NULL，避免插入时报错
        $pdo->exec("ALTER TABLE replies MODIFY comment_id INT NULL;");
    }
} catch (Exception $e) {
    // 忽略错误，继续执行
}
/* -------------------------
   登录判断函数
-------------------------- */
function is_admin() {
    return isset($_SESSION['admin_id']);
}
