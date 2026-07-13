{{-- ======================================================
商品详情页视图 (products/show.blade.php)

用途: 展示单个商品的详细信息—大图、价格、库存、描述、
同类推荐，以及加入购物车按钮。
数据来源: ProductController@show
====================================================== --}}
@extends('layouts.app')

@section('title', $product['name'])

@section('content')
<div class="container py-4">

    {{-- =============================================
    面包屑导航 (Breadcrumb)
    ---------------------------------------------
    - route('home')            → 首页
    - route('products.index')  → 全部商品列表
    - route('products.index', ['category' => $product['category_id']])
      → 该商品所属分类页
    - 最后一项不加链接，显示当前商品名
    ============================================= --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">首页</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">全部商品</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('products.index', ['category' => $product['category_id']]) }}">
                    {{ $product['category_name'] }}
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product['name'] }}</li>
        </ol>
    </nav>

    <div class="row">
        {{-- ======================================
        左侧: 商品主图
        ====================================== --}}
        <div class="col-md-6">
            <div class="card">
                {{-- 商品主图 (大图展示) --}}
                {{-- $product['image'] 是 picsum.photos 的占位图 URL --}}
                <img src="{{ $product['image'] }}"
                     class="card-img-top"
                     alt="{{ $product['name'] }}"
                     style="height: 400px; object-fit: cover;">
            </div>
        </div>

        {{-- ======================================
        右侧: 商品信息区
        ====================================== --}}
        <div class="col-md-6">
            {{-- 商品名称（用 h2 大标题） --}}
            <h2 class="mb-2">{{ $product['name'] }}</h2>

            {{-- ==================================
            商品标签（Badge）
            ----------------------------------
            - category_name: 所属分类名
            - is_recommended: 如果推荐，显示黄色"推荐"徽章
            - is_hot: 如果热销，显示红色"热销"徽章
            标签含义由业务决定，这里用代码演示如何条件渲染
            ================================== --}}
            <div class="mb-3">
                <span class="badge bg-secondary">{{ $product['category_name'] }}</span>
                @if($product['is_recommended'])
                    <span class="badge bg-warning text-dark">推荐</span>
                @endif
                @if($product['is_hot'])
                    <span class="badge bg-danger">热销</span>
                @endif
            </div>

            {{-- 价格区块（浅灰背景 + 红色大号字体） --}}
            {{-- number_format() 保留两位小数并添加千分位逗号 --}}
            <div class="bg-light p-3 rounded mb-3">
                <h3 class="text-danger mb-0">
                    ¥{{ number_format($product['price'], 2) }}
                </h3>
            </div>

            {{-- 库存信息 --}}
            <p class="mb-3">
                <strong>库存：</strong>
                @if($product['stock'] > 0)
                    <span class="text-success">有货 ({{ $product['stock'] }}) 件</span>
                @else
                    <span class="text-danger">暂时缺货</span>
                @endif
            </p>

            {{-- 商品描述 --}}
            <div class="mb-4">
                <h6>商品描述</h6>
                <p class="text-muted">{{ $product['description'] }}</p>
            </div>

            {{-- ==================================
            加入购物车表单
            ----------------------------------
            - 有库存 + 已登录 → 显示数量选择器和"加入购物车"提交按钮
            - 有库存 + 未登录 → 显示"登录后购买"链接
            - 无库存 → 灰色"暂时缺货"按钮
            - 表单 POST 到 cart.add 路由，提交 product id 和 quantity
            ================================== --}}
            @if($product['stock'] > 0)
                @if(session('user_id'))
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf {{-- Laravel CSRF 保护，防止跨站请求伪造 --}}
                        <input type="hidden" name="product_id" value="{{ $product['id'] }}">

                        {{-- 数量选择器（纯前端 JS 实现加减） --}}
                        <div class="mb-3">
                            <label for="quantity" class="form-label">购买数量</label>
                            <div class="input-group" style="width: 160px;">
                                <button type="button" class="btn btn-outline-secondary"
                                        onclick="var el=document.getElementById('quantity'); if(el.value>1) el.value--;">
                                    -
                                </button>
                                <input type="number" name="quantity" id="quantity"
                                       class="form-control text-center"
                                       value="1" min="1" max="{{ $product['stock'] }}">
                                <button type="button" class="btn btn-outline-secondary"
                                        onclick="var el=document.getElementById('quantity'); if(el.value<{{ $product['stock'] }}) el.value++;">
                                    +
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-danger btn-lg">加入购物车</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">登录后购买</a>
                @endif
            @else
                <button class="btn btn-secondary btn-lg" disabled>暂时缺货</button>
            @endif
        </div>
    </div>

    {{-- =============================================
    底部: 同类推荐商品
    ---------------------------------------------
    - 显示与当前商品同分类的其他商品（最多4个）
    - 每个推荐卡片可点击，跳转到对应商品详情
    如果推荐列表为空，则不显示该区块
    ============================================= --}}
    @if(count($relatedProducts) > 0)
    <section class="my-5">
        <h4 class="mb-3">同类推荐</h4>
        <div class="row row-cols-2 row-cols-md-4 g-3">
            @foreach($relatedProducts as $related)
            <div class="col">
                {{-- 整张卡片作为链接，点击跳转到该商品详情 --}}
                <a href="{{ route('products.show', $related['slug']) }}" class="text-decoration-none">
                    <div class="card h-100">
                        <img src="{{ $related['image'] }}"
                             class="card-img-top"
                             alt="{{ $related['name'] }}"
                             style="height: 180px; object-fit: cover;">
                        <div class="card-body">
                            <h6 class="card-title text-dark small">{{ $related['name'] }}</h6>
                            <p class="text-danger fw-bold small mb-0">
                                ¥{{ number_format($related['price'], 2) }}
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection