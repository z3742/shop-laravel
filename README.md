# shop-laravel —— Laravel 商城系统

基于 **Laravel 12** 开发的电商系统，实现从商品浏览到下单的完整购物流程，用于练习 Laravel 框架的路由、控制器、Eloquent ORM 等核心机制。

## 功能特性

| 模块 | 功能 |
|------|------|
| 首页 | 商城首页展示 |
| 商品分类 | 分类列表、按分类（slug）浏览商品 |
| 商品 | 商品列表、关键词搜索、商品详情页（slug 路由）、关联商品推荐 |
| 购物车 | 加入购物车、修改数量、移除单品、清空购物车、结算页 |
| 订单 | 创建订单、订单列表、订单详情 |
| 收货地址 | 地址增删、设置默认地址 |
| 用户中心 | 注册、登录、退出、个人资料查看与修改 |

## 技术栈

- 后端：PHP 8.x + Laravel 12
- 数据库：MySQL
- Web 服务：Nginx（本地 Linux 环境）
- 前端：Blade 模板 + HTML/CSS/JavaScript

## 路由设计说明

项目采用 **Laravel 传统路由定义方式**（`routes/web.php` 统一管理），按业务模块划分路由组：

```
/                          首页
/categories/{slug}         分类详情
/products                  商品列表
/products/search           商品搜索
/products/{slug}           商品详情
/cart/*                    购物车（增/改/删/清空/结算）
/user/profile              个人资料
/user/addresses/*          收货地址管理
/order/*                   订单创建与查询
```

路由组使用 `prefix` 统一 URL 前缀、`name` 统一路由命名（如 `cart.add`、`order.store`），视图层通过 `route()` 函数生成 URL，避免硬编码链接。

## 本地安装与运行

```bash
# 1. 克隆项目
git clone https://github.com/z3742/shop-laravel.git
cd shop-laravel

# 2. 安装依赖
composer install

# 3. 配置环境
cp .env.example .env
# 编辑 .env，填写数据库连接信息（DB_DATABASE / DB_USERNAME / DB_PASSWORD）

# 4. 生成应用密钥
php artisan key:generate

# 5. 初始化数据表
php artisan migrate

# 6. 启动开发服务器
php artisan serve
```

## 项目结构（核心部分）

```
shop-laravel/
├── app/Http/Controllers/   # 控制器
│   ├── HomeController.php      # 首页
│   ├── CategoryController.php  # 分类
│   ├── ProductController.php   # 商品与搜索
│   ├── CartController.php      # 购物车与结算
│   ├── OrderController.php     # 订单
│   ├── AddressController.php   # 收货地址
│   ├── UserController.php      # 用户中心
│   └── AuthController.php      # 注册登录
├── routes/web.php          # 全部页面路由定义
├── resources/views/        # Blade 视图模板
└── database/migrations/    # 数据库迁移
```

## 说明

- 本项目为个人学习项目，未接入真实支付，订单创建到"待支付"状态为止
- 业务数据表结构【建议：导出业务表 SQL 放入仓库（如 database/shop.sql），并在此注明导入方式后删除本行】
