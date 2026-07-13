@extends('layouts.app')

@section('title', '订单详情')

@section('content')
<div class="container-fluid p-0">
    <div class="bg-primary text-white py-4 px-4">
        <div class="d-flex align-items-center">
            <a href="{{ route('order.index') }}" class="text-white me-3">
                <i class="bi bi-arrow-left"></i> 返回
            </a>
            <h3 class="mb-0">我的订单</h3>
        </div>
    </div>

    <div class="container py-4">
        <div class="card mb-4" style="border-radius: 8px;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <span class="text-muted">订单编号：</span>
                        <span class="text-primary">{{ $order['order_no'] }}</span>
                    </div>
                    @if($order['status'] == 'shipped')
                        <button class="btn btn-sm btn-outline-primary">确认收货</button>
                    @endif
                </div>

                <div>
                    @foreach($orderItems as $item)
                    <div class="d-flex align-items-center mb-4">
                        <img src="{{ $item['product']['image'] }}" 
                             alt="{{ $item['product']['name'] }}"
                             style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;"
                             class="me-4">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $item['product']['name'] }}</h6>
                            <small class="text-muted">{{ $item['product']['category_name'] }}</small>
                            <div class="mt-2">
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
                            <span class="text-muted">共计 {{ count($orderItems) }} 件商品</span>
                            <span class="mx-2">|</span>
                            <span class="text-danger fw-bold">订单金额：¥{{ number_format($order['total_amount'], 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-light px-4 py-3">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <span class="text-muted">收货地址：</span>
                        </div>
                        <p>{{ $order['shipping_address'] }}</p>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            <span class="text-muted">订单状态：</span>
                            <span class="badge {{ ($order['status'] == 'completed') ? 'bg-success' : 
                                                  (($order['status'] == 'shipped') ? 'bg-info' : 
                                                  (($order['status'] == 'processing') ? 'bg-warning text-dark' : 'bg-secondary')) }}">
                                {{ ($order['status'] == 'completed') ? '已完成' : 
                                   (($order['status'] == 'shipped') ? '已发货' : 
                                   (($order['status'] == 'processing') ? '处理中' : '待付款')) }}
                            </span>
                        </div>
                        <div>
                            <span class="text-muted">支付状态：</span>
                            <span class="{{ $order['pay_status'] == 'paid' ? 'text-success' : 'text-danger' }}">
                                {{ $order['pay_status'] == 'paid' ? '已支付' : '未支付' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-3">
            <a href="{{ route('order.index') }}" class="btn btn-outline-primary">
                返回订单列表
            </a>
            @if($order['status'] == 'pending')
                <button class="btn btn-primary">立即支付</button>
            @endif
        </div>
    </div>
</div>
@endsection