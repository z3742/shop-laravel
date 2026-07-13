@extends('layouts.app')

@section('title', '购物车')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">首页</a></li>
            <li class="breadcrumb-item active" aria-current="page">购物车</li>
        </ol>
    </nav>

    <h3 class="mb-4"><i class="bi bi-cart3"></i> 我的购物车</h3>

    @if(count($cartWithProducts) > 0)
        <div class="card mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>商品</th>
                                <th>单价</th>
                                <th>数量</th>
                                <th>小计</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartWithProducts as $cartItem)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $cartItem['product']['image'] }}" 
                                             alt="{{ $cartItem['product']['name'] }}"
                                             style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px;"
                                             class="me-3">
                                        <div>
                                            <h6 class="mb-1">{{ $cartItem['product']['name'] }}</h6>
                                            <small class="text-muted">{{ $cartItem['product']['category_name'] }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>¥{{ number_format($cartItem['product']['price'], 2) }}</td>
                                <td>
                                    <div class="input-group" style="width: 130px;">
                                        <button type="button" class="btn btn-outline-secondary"
                                                onclick="updateQuantity({{ $cartItem['product']['id'] }}, {{ $cartItem['quantity'] - 1 }})">
                                            -
                                        </button>
                                        <input type="number" class="form-control text-center"
                                               value="{{ $cartItem['quantity'] }}" readonly>
                                        <button type="button" class="btn btn-outline-secondary"
                                                onclick="updateQuantity({{ $cartItem['product']['id'] }}, {{ $cartItem['quantity'] + 1 }})">
                                            +
                                        </button>
                                    </div>
                                </td>
                                <td class="text-danger fw-bold">¥{{ number_format($cartItem['subtotal'], 2) }}</td>
                                <td>
                                    <form action="{{ route('cart.remove', $cartItem['product']['id']) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('确定移除该商品？')">
                                            <i class="bi bi-trash"></i> 移除
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row justify-content-between align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0">
                            <span class="text-muted">共 <strong>{{ $totalItems }}</strong> 件商品</span>
                            <span class="mx-2">|</span>
                            <form action="{{ route('cart.clear') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link text-muted p-0" onclick="return confirm('确定清空购物车？')">
                                    <i class="bi bi-trash"></i> 清空购物车
                                </button>
                            </form>
                        </p>
                    </div>
                    <div class="col-md-6 text-right">
                        <p class="mb-1">
                            <span class="text-muted">合计：</span>
                            <span class="text-danger display-6 fw-bold">¥{{ number_format($total, 2) }}</span>
                        </p>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">继续购物</a>
                            <a href="{{ route('cart.checkout') }}" class="btn btn-danger btn-lg">去结算</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-10">
                <i class="bi bi-cart-x display-4 text-muted mb-3"></i>
                <h4 class="text-muted mb-2">购物车是空的</h4>
                <p class="text-muted mb-4">快去挑选心仪的商品吧！</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">去购物</a>
            </div>
        </div>
    @endif
</div>

<script>
function updateQuantity(productId, quantity) {
    if (quantity < 1) return;
    fetch('{{ route('cart.update', ':id') }}'.replace(':id', productId), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'quantity=' + quantity,
    }).then(response => window.location.reload());
}
</script>
@endsection