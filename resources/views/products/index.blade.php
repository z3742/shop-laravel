{{-- ==========================================================================
    商品列表页视图 (products/index.blade.php)

    用途：展示全部商品或按分类筛选，响应式网格布局。
    数据来源：ProductController@index
========================================================================== --}}
@extends('layouts.app')

@section('title', '全部商品')

@section('content')
<div class="container py-4">
    {{-- 面包屑导航 --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">首页</a></li>
            <li class="breadcrumb-item active" aria-current="page">全部商品</li>
        </ol>
    </nav>

    <h3 class="mb-4">全部商品</h3>

    {{-- ======================================================================
        商品网格
        row-cols-2 → 小屏每行 2 个
        row-cols-md-4 → 中屏及以上每行 4 个
    ====================================================================== --}}
    <div class="row row-cols-2 row-cols-md-4 g-4">
        @foreach($products as $product)
            <div class="col">
                <a href="{{ route('products.show', $product['slug']) }}" class="text-decoration-none">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ $product['image'] }}"
                             class="card-img-top"
                             alt="{{ $product['name'] }}"
                             style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h6 class="card-title text-dark">{{ $product['name'] }}</h6>
                            <p class="text-danger fw-bold mb-1">
                                ¥{{ number_format($product['price'], 2) }}
                            </p>
                            <small class="text-muted">库存: {{ $product['stock'] }}</small>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection