@extends('layouts.app')

@section('title', '我的订单')

@section('content')
<div class="container-fluid p-0">
    <div class="bg-primary text-white py-4 px-4">
        <div class="d-flex align-items-center">
            <a href="{{ route('user.profile') }}" class="text-white me-3">
                <i class="bi bi-arrow-left"></i> 返回
            </a>
            <h3 class="mb-0">我的订单</h3>
        </div>
    </div>

    <div class="bg-white shadow-sm">
        <div class="nav nav-tabs">
            <a href="{{ route('order.index') }}" class="nav-link flex-fill text-center py-3 {{ !request()->query('status') ? 'active text-primary border-primary' : 'text-muted' }}">
                全部
            </a>
            <a href="{{ route('order.index') }}?status=pending" class="nav-link flex-fill text-center py-3 {{ request()->query('status') == 'pending' ? 'active text-primary border-primary' : 'text-muted' }}">
                进行中
            </a>
            <a href="{{ route('order.index') }}?status=completed" class="nav-link flex-fill text-center py-3 {{ request()->query('status') == 'completed' ? 'active text-primary border-primary' : 'text-muted' }}">
                已完成
            </a>
        </div>
    </div>

    <div class="container py-4">
        @if(count($orders) > 0)
            @foreach($orders as $order)
            <div class="card mb-4" style="border-radius: 8px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="text-muted">订单编号：</span>
                            <span class="text-primary">{{ $order['order_no'] }}</span>
                        </div>
                        @if($order['status'] == 'pending')
                            <button class="btn btn-sm btn-outline-primary">确认收货</button>
                        @endif
                    </div>

                    <div>
                        @foreach($order['order_items'] as $item)
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $item['product']['image'] }}" 
                                 alt="{{ $item['product']['name'] }}"
                                 style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;"
                                 class="me-3">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-0">{{ $item['product']['name'] }}</h6>
                                        <small class="text-muted">{{ $item['product']['category_name'] }}</small>
                                    </div>
                                    <span class="text-danger fw-bold">¥{{ number_format($item['product']['price'], 2) }}</span>
                                </div>
                            </div>
                            <div class="ms-4 text-right">
                                <span class="text-muted">x{{ $item['quantity'] }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-4 pt-4 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">{{ $order['created_at'] }}</span>
                            <div>
                                <span class="text-muted">共计 {{ count($order['order_items']) }} 件商品</span>
                                <span class="mx-2">|</span>
                                <span class="text-danger fw-bold">订单金额：¥{{ number_format($order['total_amount'], 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light px-4 py-3">
                    <div class="row justify-content-between align-items-center">
                        <div>
                            <span class="text-muted">收货地址：</span>
                            <span>{{ $order['shipping_address'] }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge {{ ($order['status'] == 'completed') ? 'bg-success' : 
                                                  (($order['status'] == 'shipped') ? 'bg-info' : 
                                                  (($order['status'] == 'processing') ? 'bg-warning text-dark' : 'bg-secondary')) }}">
                                {{ ($order['status'] == 'completed') ? '已完成' : 
                                   (($order['status'] == 'shipped') ? '已发货' : 
                                   (($order['status'] == 'processing') ? '处理中' : '待付款')) }}
                            </span>
                            <a href="{{ route('order.show', $order['id']) }}" class="btn btn-sm btn-outline-primary">
                                查看详情
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-receipt display-4 text-muted mb-3"></i>
                    <h4 class="text-muted mb-2">暂无订单</h4>
                    <p class="text-muted mb-4">快去购物下单吧！</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">去购物</a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection