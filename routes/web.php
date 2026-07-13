<?php
// ======================================================
// Laravel 路由定义文件
// 位置: routes/web.php
// 说明: 本文件采用 Laravel 传统路由定义方式，所有前端页面路由均在此定义
// ======================================================

// Laravel 12 的路由注册方式有两种：
// 1. 在 bootstrap/app.php 中通过 withRouting() 注册（新方式）
// 2. 在 routes/web.php 中定义（传统方式，本项目采用此方式）

// 引入路由门面
use Illuminate\Support\Facades\Route;

// 引入控制器类
// 创建控制器命令: php artisan make:controller 控制器名称
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AddressController;

// ======================================================
// 【首页路由】
// ======================================================
// Route::get()   → 处理 GET 请求（浏览器直接访问的路径）
// 第一个参数     → URL 路径（'/' 表示网站根路径）
// 第二个参数     → 数组格式，指定控制器和方法 [控制器类, '方法名']
// ->name()       → 给路由命名，后续可通过 route('名称') 生成 URL
Route::get('/', [HomeController::class, 'index'])->name('home');

// ======================================================
// 【商品分类路由组】
// ======================================================
// prefix('categories') → URL 前缀，所有子路由均以 /categories 开头
// name('categories.')  → 路由名称前缀，子路由名称自动加上 categories.
// group(function{})    → 路由分组，内部子路由共享前缀配置
Route::prefix('categories')->name('categories.')->group(function () {
    // 分类列表页 → GET /categories → route('categories.index')
    Route::get('/', [CategoryController::class, 'index'])->name('index');

    // 分类详情页 → GET /categories/{slug} → route('categories.show', ['slug' => 'xxx'])
    // {slug} 为路由参数，表示分类别名
    Route::get('/{slug}', [CategoryController::class, 'show'])->name('show');
});

// ======================================================
// 【商品路由】
// ======================================================
// 商品列表页 → GET /products → route('products.index')
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// 商品搜索页 → GET /products/search → route('products.search')
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');

// 商品详情页 → GET /products/{slug} → route('products.show', ['slug' => 'xxx'])
// {slug} 为路由参数，表示商品别名（URL友好的标识符）
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// ======================================================
// 【认证路由】（登录 / 注册 / 退出）
// ======================================================
// GET  → /login     → 显示登录表单          → route('login')
// POST → /login     → 处理登录请求          → route('login.post')
// GET  → /register  → 显示注册表单          → route('register')
// POST → /register  → 处理注册请求          → route('register.post')
// POST → /logout    → 处理退出登录          → route('logout')
Route::get('/login',    [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login',   [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register',[AuthController::class, 'register'])->name('register.post');
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');

// ======================================================
// 【购物车路由组】
// ======================================================
// prefix('cart') → URL 前缀，所有子路由均以 /cart 开头
// name('cart.')  → 路由名称前缀，子路由名称自动加上 cart.
Route::prefix('cart')->name('cart.')->group(function () {
    // 购物车列表页 → GET /cart → route('cart.index')
    Route::get('/', [CartController::class, 'index'])->name('index');

    // 添加商品到购物车 → POST /cart/add → route('cart.add')
    Route::post('/add', [CartController::class, 'add'])->name('add');

    // 更新购物车商品数量 → POST /cart/update/{productId} → route('cart.update', ['productId' => 1])
    Route::post('/update/{productId}', [CartController::class, 'update'])->name('update');

    // 移除购物车商品 → POST /cart/remove/{productId} → route('cart.remove', ['productId' => 1])
    Route::post('/remove/{productId}', [CartController::class, 'remove'])->name('remove');

    // 清空购物车 → POST /cart/clear → route('cart.clear')
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');

    // 结算页面 → GET /cart/checkout → route('cart.checkout')
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
});

// ======================================================
// 【个人中心路由组】
// ======================================================
// prefix('user') → URL 前缀，所有子路由均以 /user 开头
// 个人中心路由组
Route::prefix('user')->name('user.')->group(function () {
    // 个人资料页面 → GET /user/profile → route('user.profile')
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');

    // 更新个人资料 → POST /user/profile/update → route('user.profile.update')
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');

    // 地址管理页面 → GET /user/addresses → route('user.addresses')
    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses');

    // 添加地址 → POST /user/addresses/add → route('user.addresses.add')
    Route::post('/addresses/add', [AddressController::class, 'add'])->name('addresses.add');

    // 删除地址 → POST /user/addresses/delete/{addressId} → route('user.addresses.delete')
    Route::post('/addresses/delete/{addressId}', [AddressController::class, 'delete'])->name('addresses.delete');

    // 设置默认地址 → POST /user/addresses/set-default/{addressId} → route('user.addresses.set-default')
    Route::post('/addresses/set-default/{addressId}', [AddressController::class, 'setDefault'])->name('addresses.set-default');
});

// ======================================================
// 【订单路由组】
// ======================================================
// prefix('order') → URL 前缀，所有子路由均以 /order 开头
// name('order.')  → 路由名称前缀，子路由名称自动加上 order.
Route::prefix('order')->name('order.')->group(function () {
    // 订单列表页 → GET /order → route('order.index')
    Route::get('/', [OrderController::class, 'index'])->name('index');

    // 订单详情页 → GET /order/{orderId} → route('order.show', ['orderId' => 1001])
    Route::get('/{orderId}', [OrderController::class, 'show'])->name('show');

    // 创建订单 → POST /order/store → route('order.store')
    Route::post('/store', [OrderController::class, 'store'])->name('store');
});
