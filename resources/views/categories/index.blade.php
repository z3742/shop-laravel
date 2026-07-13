{{-- ============================================================
     所有分类列表页
     位置：resources/views/categories/index.blade.php
     路由：GET /categories (categories.index)
============================================================ --}}

@extends('layouts.app')

@section('title', '商品分类')

@section('content')

<div class="container">
    {{-- ========================================== --}}
    {{-- 页面标题区 --}}
    {{-- ========================================== --}}
    <div class="p-4 mb-4 bg-primary text-white rounded-3">
        <div class="container-fluid">
            <h1 class="display-6 fw-bold">
                <i class="bi bi-grid-3x3-gap-fill"></i> 全部商品分类
            </h1>
            <p class="col-md-8 fs-6 mb-0">
                以下是本商城的所有商品分类，点击可查看该分类下的商品。
            </p>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- 面包屑导航（告诉用户当前所在页面层级） --}}
    {{-- ========================================== --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            {{-- route('home') → 生成首页链接 --}}
            <li class="breadcrumb-item"><a href="{{ route('home') }}">首页</a></li>
            {{-- active → 当前页面，无链接 --}}
            <li class="breadcrumb-item active" aria-current="page">商品分类</li>
        </ol>
    </nav>

    {{-- ========================================== --}}
    {{-- 分类卡片网格 --}}
    {{-- ========================================== --}}
    {{--
    row-cols-1     → 手机端每行 1 个
    row-cols-sm-2  → 小屏幕每行 2 个
    row-cols-md-3  → 平板每行 3 个
    row-cols-lg-4  → 桌面端每行 4 个
    g-4            → 卡片间距（Bootstrap 5 的 gap 工具类）
    --}}
    @if(!empty($categories))
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        @foreach($categories as $category)
            <div class="col">
                {{--
                route('categories.show', $category['slug'])
                → 生成 /categories/phone-digital 这样的链接
                --}}
                <a href="{{ route('categories.show', $category['slug']) }}" class="text-decoration-none">
                    <div class="card h-100 shadow-sm category-card">
                        <div class="card-body text-center py-5">
                            {{-- ===== 分类图标 ===== --}}
                            {{-- display-3 → 超大图标尺寸 --}}
                            <i class="bi {{ $category['icon'] }} display-3 text-primary mb-3"></i>

                            {{-- ===== 分类名称 ===== --}}
                            <h5 class="card-title text-dark">{{ $category['name'] }}</h5>

                            {{-- ===== 引导文字 ===== --}}
                            <p class="card-text text-muted small mb-0">
                                点击查看 {{ $category['name'] }} 相关商品
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
    @else
        {{-- 没有分类数据时的占位提示 --}}
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> 暂无分类数据，请先执行数据迁移和填充。
        </div>
    @endif

  
</div>

@endsection