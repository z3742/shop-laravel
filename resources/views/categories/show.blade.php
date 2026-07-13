{{-- 单个分类详情页
位置: resources\views\categories\show.blade.php
路由: GET /categories/{slug} (categories.show)
--}}

@extends('layouts.app')

@section('title', $category['name'] . ' - 商品分类')

@section('content')

<div class="container">

    {{-- ================ 面包屑导航（三层：首页 > 商品分类 > 当前分类）============ --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">首页</a></li>
            <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">商品分类</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $category['name'] }}</li>
        </ol>
    </nav>

    {{-- ================ 分类头部信息 ================ --}}
    <div class="mb-4 bg-primary text-white rounded-3">
        <div class="d-flex align-items-center">
            <i class="bi {{ $category['icon'] }} display-4 me-3"></i>
            <div>
                <h1 class="display-5 fw-bold mb-1">{{ $category['name'] }}</h1>
                <p class="mb-0 opacity-75">浏览 {{ $category['name'] }} 分类下的所有商品</p>
            </div>
        </div>
    </div>

    {{-- ================ 商品列表区域（占位，等待后续开发商品模块）============ --}}
    <div class="row mb-4">
        <div class="col">
            <div class="card card-shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-box-seam display-4 text-muted mb-3"></i>
                    <h4 class="text-muted">商品模块即将上线</h4>
                    <p class="text-muted mb-4">{{ $category['name'] }} 分类已就绪。</p>
                    <p>
                        当您完成 <strong>Product 模型</strong>/<strong>控制器</strong> 的开发后，这里将展示该分类下的商品列表。
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ================ 即将展示的商品卡片结构（注释预览）============ --}}
    <div class="row mb-4">
        <h4 class="border-bottom pb-2 mb-3">开发路线图</h4>
        <div class="alert alert-light border">
            <pre class="bg-dark text-light p-3 rounded mt-2 mb-0"><code>// CategoryController@show 中查询该分类下的商品：
$products = Product::where('category_id', $category->id)
    ->paginate(12);  // 自动分页，每页 12 个

// 视图中遍历显示商品卡片...</code></pre>
        </div>
    </div>

    {{-- ================ 返回按钮 ================ --}}
    <div class="mb-4">
        <a href="{{ route('categories.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left"></i> 返回全部分类
        </a>
    </div>

</div>

@endsection