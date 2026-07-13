{{-- ======================================
搜索结果页
位置: resources/views/products/search.blade.php
====================================== --}}

@extends('layouts.app')

@section('title', '搜索: ' . $keyword . ' - 商品搜索')

@section('content')
<div class="container">
    {{-- ====================================== --}}
    {{-- 搜索标题栏（展示搜索关键词和结果数量） --}}
    {{-- ====================================== --}}
    <div class="mb-4">
        {{-- 面包屑导航 --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">首页</a></li>
                <li class="breadcrumb-item active" aria-current="page">搜索结果</li>
            </ol>
        </nav>

        {{-- 搜索关键词 + 结果统计 --}}
        <div class="d-flex align-items-center gap-3">
            <h3 class="mb-0">
                <i class="bi bi-search text-primary"></i>
                @if($keyword)
                    "{{ $keyword }}" 的搜索结果
                @else
                    全部商品
                @endif
            </h3>
            <span class="badge bg-secondary fs-6">
                {{-- count() 统计数组元素个数 --}}
                共 {{ count($results) }} 件
            </span>
        </div>
    </div>

    {{-- ====================================== --}}
    {{-- 搜索结果展示区域 --}}
    {{-- ====================================== --}}
    @if(count($results) > 0)
        {{--
        row-cols-2 → 手机端每行 2 个商品
        row-cols-md-4 → 平板及以上每行 4 个商品
        g-4 → 卡片间距
        --}}
        <div class="row row-cols-2 row-cols-md-4 g-4">
            @foreach($results as $product)
            <div class="col">
                {{-- 商品卡片 --}}
                <a href="{{ route('products.show', $product['slug']) }}" class="text-decoration-none">
                    <div class="card product-card h-100 shadow-sm">
                        <img src="{{ $product['image'] }}"
                            class="card-img-top"
                            alt="{{ $product['name'] }}"
                            style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            {{-- 商品名 --}}
                            <h6 class="card-title text-dark">{{ $product['name'] }}</h6>
                            {{-- 价格（number_format 格式化为两位小数） --}}
                            <p class="text-danger fw-bold mb-1">¥{{ number_format($product['price'], 2) }}</p>
                            {{-- 库存数量 --}}
                            <small class="text-muted">库存: {{ $product['stock'] }}</small>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    @else
        {{-- ====================================== --}}
        {{-- 无结果的提示区域 --}}
        {{-- ====================================== --}}
        <div class="text-center py-5">
            <div class="display-1 text-muted mb-3">
                <i class="bi bi-search"></i>
            </div>
            <h4 class="text-muted">没有找到与 "{{ $keyword }}" 相关的商品</h4>
            <p class="text-muted">请尝试其他关键词，或浏览商品分类。</p>
            <a href="{{ route('home') }}" class="btn btn-primary mt-2">
                <i class="bi bi-house"></i> 返回首页
            </a>
        </div>
    @endif
</div>
@endsection