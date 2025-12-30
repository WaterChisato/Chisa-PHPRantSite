
# 吐槽站 / Tucao Board

一个轻量、美观、支持楼中楼回复的留言吐槽系统。  
采用 **PHP + MySQL** 开发，界面使用 **深色现代 UI**，并且 **手机 + 电脑全端适配**。  

适合：

- 个人博客留言板  
- 轻量论坛  
- 反馈系统  
- 吐槽墙  
- 轻量社区  

---

## ✨ 功能特性

### 📝 用户功能
- 发布留言（文章）
- 回复留言（支持楼中楼）
- 自动获取 QQ 昵称与头像
- 深色 UI，现代卡片风格
- 手机端完美适配
- 无需登录即可回复

### 🔧 管理员功能
- 管理员登录 / 退出
- 发布文章
- 修改管理员名称
- 修改管理员密码
- 添加管理员
- 回复管理（可删除回复，自动删除子回复）
- 全站统一风格后台界面
- 修改文章内容，删除文章

### 🗂 数据库自动建表
首次运行会自动创建：

- `posts`（文章）
- `replies`（回复）
- `admins`（管理员）

无需手动建表。

---

## 📸 界面截图

> 装上就知道啦awa     

---

## 📦 安装部署

### 1. 克隆项目

~~~
git clone https://github.com/WaterChisato/Chisa-PHPRantSite.git
cd 你的仓库名
~~~

### 2. 上传到服务器

支持任意 PHP 环境：
~~~
- 宝塔面板 
- LNMP
- Apache / Nginx
- 虚拟主机
- 本地 PHP 环境
~~~
### 3. 配置数据库

编辑 config.php：
~~~
php
$host = "localhost";
$dbname = "tucao";
$user = "root";      // 示例账号
$pass = "123456";    // 示例密码
~~~

> ⚠️ 请不要在 GitHub 上传真实数据库密码。

### 4. 访问首页

`
http://你的域名/index.php
`
  
首次访问会自动建表。

---

### 🗄 数据库结构

posts（文章）
| 字段 | 类型 | 说明 |
|------|------|------|
| id | int | 主键 |
| title | varchar | 标题 |
| content | text | 内容 |
| created_at | timestamp | 创建时间 |

replies（回复）
| 字段 | 类型 | 说明 |
|------|------|------|
| id | int | 主键 |
| post_id | int | 所属文章 |
| parent_id | int | 父回复（楼中楼） |
| nickname | varchar | 昵称 |
| qq | varchar | QQ号 |
| avatar | varchar | QQ头像 |
| content | text | 内容 |
| created_at | timestamp | 时间 |

admins（管理员）
| 字段 | 类型 | 说明 |
|------|------|------|
| id | int | 主键 |
| username | varchar | 管理员账号 |
| password | varchar | SHA256 加密密码 |

用户默认为**admin**，密码默认为**123456**  
> 在登录面板后重新设定用户名和密码，添加管理仅推荐自己信任的人


---

###  📁 项目结构

~~~
├── index.php           # 首页
├── reply.php           # 回复页（楼中楼）
├── login.php           # 管理员登录
├── admin.php           # 后台主页
├── post.php            # 发布文章
├── reply_manage.php    # 回复管理（删除回复）
├── logout.php          # 退出登录
├── config.php          # 配置 + 自动建表
└── README.md           # 项目说明
~~~

---

###  🔐 安全说明

- 管理员密码使用 sm 加密  
- 建议自行添加验证码（如需更高安全性）  
- 建议使用 Nginx/Apache 限制 config.php 访问  

---

### 📜 开源协议

本项目使用 MIT License，可自由商用、修改、分发。

---

## 🤝 贡献指南

### 欢迎提交 PR 或 Issue：
~~~
- 修复 bug  
- 优化 UI  
- 添加新功能  
- 改进文档  
~~~
---

### ⭐ 支持项目

如果你觉得这个项目不错，欢迎点一个 Star！

---

### ✍️ 制作者以及编辑者
此仓库由 [水之蔻放](http://kf.waterchisato.top)  组织编写  
![水之蔻放](http://logo.kf.waterchisato.top/zy/kf/Gemini_Generated_Image_j8ue0wj8ue0wj8ue.png "图片title")  
由 Copilot 生成大部分代码     
![Copilot](http://logo.kf.waterchisato.top/mc-ai.png "copilot")  
由 水喝千束 进行编写
![waterchisato](http://logo.kf.waterchisato.top/pic/web_logo.jpeg "chisato")

---

