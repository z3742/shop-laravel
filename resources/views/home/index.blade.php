{{-- ==========================================================================
首页视图
位置: resources/views/home/index.blade.php
========================================================================== --}}

@extends('layouts.app')

@section('title', '首页')

@section('content')

<div class="container">
    {{-- ====================================== --}}
    {{-- 1. 欢迎横幅 --}}
    {{-- ====================================== --}}
    {{-- <div class="p-5 mb-4 bg-primary text-white rounded-3">
        <div class="container-fluid py-3">
            <h1 class="display-5 fw-bold">{{ $data['title'] }}</h1>
            <p class="col-md-8 fs-5">{{ $data['description'] }}</p>
            <hr class="my-4">
            <p>这是一个零数据库依赖的纯展示型首页，适合 Laravel 初学者入门学习。</p>
        </div>
    </div> --}}

    {{-- ====================================== --}}
    {{-- 2. 轮播图区域 (Bootstrap 5 Carousel 组件) --}}
    {{-- ====================================== --}}
    @if(!empty($slides))
    <div id="demoCarousel" class="carousel slide mb-5 shadow rounded overflow-hidden" data-bs-ride="carousel">
        <div class="carousel-indicators">
            @foreach($slides as $index => $slide)
            <button type="button"
                data-bs-target="#demoCarousel"
                data-bs-slide-to="{{ $index }}"
                @if($loop->first) class="active" @endif
                aria-label="第 {{ $index + 1 }} 张">
            </button>
            @endforeach
        </div>

        <div class="carousel-inner">
            @foreach($slides as $slide)
            <div class="carousel-item @if($loop->first) active @endif">
                <img src="{{ $slide['image'] }}"
                    class="d-block w-100"
                    alt="{{ $slide['title'] }}"
                    style="height: 400px; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
                    <h3>{{ $slide['title'] }}</h3>
                    <p>{{ $slide['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#demoCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">上一张</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#demoCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">下一张</span>
        </button>
    </div>
    @endif

    {{-- ====================================== --}}
    {{-- 3. 功能特性卡片 (一行三列) --}}
    {{-- ====================================== --}}
    {{-- <div class="row mb-4">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="display-4 mb-3">🌐</div>
                    <h5 class="card-title">路由系统</h5>
                    <p class="card-text text-muted">
                        在 routes/web.php 中定义 URL 与控制器方法的映射关系，Laravel 自动完成请求分发。
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="display-4 mb-3">🎛️</div>
                    <h5 class="card-title">控制器</h5>
                    <p class="card-text text-muted">
                        控制器 (Controller) 负责接收请求、处理逻辑、返回响应，是 MVC 中的 C 层核心。
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="display-4 mb-3">🧩</div>
                    <h5 class="card-title">Blade 模板</h5>
                    <p class="card-text text-muted">
                        Blade 是 Laravel 的模板引擎，支持布局继承、条件判断、循环输出等功能。
                    </p>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- ====================================== --}}
    {{-- 4. 商品分类导航 --}}
    {{-- ====================================== --}}
    @if(!empty($categories))
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
            <h3 class="mb-0">
                <i class="bi bi-grid-fill text-primary"></i> 商品分类
            </h3>
            <a href="{{ route('categories.index') }}" class="text-decoration-none">查看全部 &raquo;</a>
        </div>

        <div class="row row-cols-2 row-cols-md-4 g-3">
            @foreach($categories as $category)
            <div class="col">
                <a href="{{ route('categories.show', $category['slug']) }}" class="text-decoration-none">
                    <div class="card text-center h-100 shadow-sm category-card">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                            <i class="bi {{ $category['icon'] }} display-5 text-primary mb-2"></i>
                            <span class="fw-medium text-dark">{{ $category['name'] }}</span>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ====================================== --}}
    {{-- 5. 精选推荐商品 --}}
    {{-- ====================================== --}}
    @if(!empty($recommendedProducts))
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
            <h3 class="mb-0">
                <i class="bi bi-star-fill text-warning"></i> 精选推荐
            </h3>
            <a href="{{ route('products.index')}}" class="text-decoration-none">查看更多 &raquo;</a>
        </div>

        <div class="row row-cols-2 row-cols-md-4 g-4">
            @foreach($recommendedProducts as $product)
            <div class="col">
                <a href="{{ route('products.show',$product['slug'])}}" class="text-decoration-none">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ $product['image'] }}" class="card-img-top"
                            alt="{{ $product['name'] }}"
                            style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h6 class="card-title text-dark">{{ $product['name'] }}</h6>
                            <p class="text-danger fw-bold mb-1">¥{{ number_format($product['price'], 2) }}</p>
                            <small class="text-muted">库存: {{ $product['stock'] }}</small>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ====================================== --}}
    {{-- 6. 热销爆款商品 --}}
    {{-- ====================================== --}}
    @if(!empty($hotProducts))
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
            <h3 class="mb-0">
                <i class="bi bi-fire text-danger"></i> 热销爆款
            </h3>
            <a href="{{ route('categories.index') }}" class="text-decoration-none">查看更多 &raquo;</a>
        </div>

        <div class="row row-cols-2 row-cols-md-4 g-4">
            @foreach($hotProducts as $product)
            <div class="col">
                <a href="{{ route('products.show',$product['slug'])}}" class="text-decoration-none">
                    <div class="card h-100 shadow-sm position-relative">
                        <span class="badge bg-danger position-absolute top-0 end-0 m-2">HOT</span>
                        <img src="{{ $product['image'] }}" class="card-img-top"
                            alt="{{ $product['name'] }}"
                            style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h6 class="card-title text-dark">{{ $product['name'] }}</h6>
                            <p class="text-danger fw-bold mb-1">¥{{ number_format($product['price'], 2) }}</p>
                            <small class="text-muted">库存: {{ $product['stock'] }}</small>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ====================================== --}}
    {{-- 7. 示例文章列表 --}}
    {{-- ====================================== --}}
    {{-- <h3 class="mb-3 border-bottom pb-2">📖 推荐阅读</h3>
    @if(count($articles) > 0)
    <div class="row mb-5">
        @foreach($articles as $article)
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ $article['title'] }}</h5>
                    <p class="card-text text-muted">{{ $article['summary'] }}</p>
                </div>
                <div class="card-footer bg-transparent">
                    <small class="text-muted">📅 {{ $article['date'] }}</small>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="alert alert-info">暂无文章内容，敬请期待。</div>
    @endif --}}

    {{-- ====================================== --}}
    {{-- 8. 文件结构快速浏览 --}}
    {{-- ====================================== --}}
    {{-- <h3 class="mb-3 border-bottom pb-2 mt-5">你需要关注的 4 个文件</h3>
    <div class="table-responsive mb-5">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>文件作用</th>
                    <th>文件路径</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>定义 URL 路由</td>
                    <td><code>routes/web.php</code></td>
                </tr>
                <tr>
                    <td>首页控制器 (接收请求, 准备数据, 返回视图)</td>
                    <td><code>app/Http/Controllers/HomeController.php</code></td>
                </tr>
                <tr>
                    <td>公共布局模板 (导航栏、页脚等所有页面共享的部分)</td>
                    <td><code>resources/views/layouts/app.blade.php</code></td>
                </tr>
                <tr>
                    <td>首页视图 (本页面, 继承公共模板并填充内容)</td>
                    <td><code>resources/views/home/index.blade.php</code></td>
                </tr>
            </tbody>
        </table>
    </div> --}}

    {{-- <div class="alert alert-success mt-3">
        <h5>调用流程说明</h5>
        <ol class="mb-0">
            <li>浏览器访问 <code>/</code></li>
            <li>查找 <code>routes/web.php</code> 找到路由 <code>HomeController@index</code></li>
            <li>调用 <code>HomeController::index()</code> 方法 → 渲染 <code>home.index</code> 视图</li>
            <li>视图 <code>home/index.blade.php</code> → (继承 <code>layouts/app.blade.php</code>) → 最终生成完整 HTML 返回浏览器。</li>
        </ol>
    </div> --}}
</div>
@endsection