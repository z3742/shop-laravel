{{-- ============================================================
     公共布局模板（所有页面共用的"外壳"）
     位置：resources/views/layouts/app.blade.php
============================================================ --}}
{{--
Blade 是 Laravel 的模板引擎，文件以 .blade.php 结尾。
@xxx 是 Blade 的指令（Directive），由 Laravel 编译成 PHP 代码。
--}}

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    {{-- 页面编码，确保中文字符正常显示 --}}
    <meta charset="UTF-8">
    {{-- 响应式设计：让页面在手机和平板上也能正常显示 --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{--
    @yield('title', '默认标题')
    定义一个"区块"叫 title，子页面可以用 @section('title', 'xxx') 来填充
    如果子页面没有定义，就显示 '默认标题'
    --}}
    <title>@yield('title', 'Laravel ')</title>

    {{-- ========================================== --}}
    {{-- 引入 Bootstrap 5 CSS（从 CDN 加载，无需下载） --}}
    {{-- Bootstrap 是一个流行的前端框架，提供按钮、卡片、导航等样式 --}}
    {{-- ========================================== --}}
    <link href="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons 图标字体（导航栏中的 🛒、👤 等图标） --}}
    <link href="https://cdn.bootcdn.net/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    {{-- 分类卡片悬停效果 --}}
    <style>
        .category-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .category-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
    </style>
</head>
<body style="background-color: #F5F5F5;">

    {{-- ========================================== --}}
    {{-- 头部导航栏（使用 Bootstrap 的 navbar 组件） --}}
    {{-- ========================================== --}}
    {{-- ========================================== --}}
    {{-- 顶部导航栏（深色电商风格） --}}
    {{-- ========================================== --}}
    {{--
    sticky-top → 导航栏固定在页面顶部，滚动时不消失
    navbar-dark bg-dark → 深色背景 + 浅色文字
    --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            {{-- ===== 网站品牌/Logo ===== --}}
            {{-- route('home') → 使用路由名称生成 URL，比写死 '/' 更灵活 --}}
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-shop"></i> Laravel Shop
            </a>

            {{-- ===== 移动端汉堡菜单按钮 ===== --}}
            {{-- 屏幕宽度 < lg（992px）时显示，点击展开/折叠导航 --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- ===== 导航链接区域 ===== --}}
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    {{-- 首页 --}}
                    {{--
                    request()->routeIs('home')
                    → 判断当前请求的路由名称是否为 'home'
                    → 如果是，自动添加 active 类高亮当前项
                    --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                           href="{{ route('home') }}">首页</a>
                    </li>

                    {{-- 全部商品（点击跳转到商品列表页） --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
                           href="{{ route('products.index') }}">全部商品</a>
                    </li>

                    {{-- 购物车 --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('cart.*') ? 'active' : '' }}"
                           href="{{ route('cart.index') }}">
                            购物车
                            @if(session('user_id'))
                            <span class="badge bg-warning text-dark cart-count">{{ count(session('cart', [])) }}</span>
                            @endif
                        </a>
                    </li>
                </ul>

                {{-- ========================================== --}}
                {{-- 搜索表单 --}}
                {{-- ========================================== --}}
                {{--
                GET 方式提交到 products.search 路由
                form-inline → 将输入框和按钮放在同一行
                ms-lg-3 → 桌面端左边距
                --}}
                <form action="{{ route('products.search') }}" method="GET"
                      class="d-flex my-2 my-lg-0 ms-lg-3" role="search">
                    <div class="input-group">
                        {{--
                        搜索输入框
                        name="keyword" → 表单提交后的参数名，控制器用 request()->query('keyword') 获取
                        request()->query('keyword') → 搜索后保留关键词在输入框中，方便用户看到自己搜了什么
                        --}}
                        <input class="form-control" type="search" name="keyword"
                               placeholder="搜索商品..." aria-label="搜索"
                               value="{{ request()->query('keyword', '') }}"
                               style="min-width: 200px;">
                        <button class="btn btn-outline-light" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>

                {{-- ===== 右侧：用户菜单 ===== --}}
                {{--
                判断登录状态的核心逻辑：
                session('user_id') → 有值 = 已登录 | 无值 = 未登录
                这是用 PHP Session 模拟的认证，不依赖数据库 users 表。
                接入数据库后改为 Laravel 的 @auth / @guest 指令即可。
                --}}
                <ul class="navbar-nav">
                    @if(!session('user_id'))
                        {{-- 未登录 → 显示登录和注册按钮 --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">登录</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">注册</a>
                        </li>
                    @else
                        {{-- 已登录 → 显示用户名和下拉菜单 --}}
                        {{--
                        dropdown → Bootstrap 下拉菜单组件
                        data-bs-toggle="dropdown" → 点击触发下拉
                        dropdown-menu-end → 下拉菜单右对齐
                        --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button"
                               data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                                {{ session('user_name') }}
                                {{--
                                session('user_name') → 从 Session 中取用户名
                                接入数据库后改为：auth()->user()->name
                                --}}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('user.profile') }}">
                                    <i class="bi bi-person"></i> 个人中心</a></li>
                                <li><a class="dropdown-item" href="{{ route('order.index') }}">
                                    <i class="bi bi-receipt"></i> 我的订单</a></li>
                                <li><hr class="dropdown-divider"></li>
                                {{-- 退出登录 --}}
                                {{--
                                退出必须用 POST 方式提交（安全要求）
                                form 嵌套在 dropdown-item 中，点击即提交
                                @csrf → 生成隐藏的 CSRF 令牌字段，防止跨站请求伪造
                                --}}
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right"></i> 退出登录
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    {{-- ========================================== --}}
    {{-- 主要内容区域 --}}
    {{-- @yield('content') 是"占位符" --}}
    {{-- 子页面所有内容会插入到这里 --}}
    {{-- ========================================== --}}
    <main class="py-4">
        {{-- 闪存消息：登录/注册/退出后的提示 --}}
        @if(session('success'))
            <div class="container mb-3">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="container mb-3">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif
        @if(session('info'))
            <div class="container mb-3">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif
        @yield('content')
    </main>

    {{-- ========================================== --}}
    {{-- 底部版权信息 --}}
    {{-- ========================================== --}}
    <footer class="bg-light text-center py-3 mt-4 border-top">
        <div class="container">
            <small class="text-muted">Laravel Shop v12 &copy; {{ date('Y') }}</small>
        </div>
    </footer>

    {{-- ========================================== --}}
    {{-- Bootstrap 5 JavaScript（实现折叠菜单、弹窗等交互效果） --}}
    {{-- ========================================== --}}
    <script src="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>