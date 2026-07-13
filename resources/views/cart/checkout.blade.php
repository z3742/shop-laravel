@extends('layouts.app')

@section('title', '订单结算')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">首页</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">购物车</a></li>
            <li class="breadcrumb-item active" aria-current="page">订单结算</li>
        </ol>
    </nav>

    <h3 class="mb-4"><i class="bi bi-receipt"></i> 订单结算</h3>

    <form action="{{ route('order.store') }}" method="POST">
        @csrf
        <input type="hidden" name="selected_address_id" id="selected_address_id" value="{{ $addresses[0]['id'] }}">

        <div class="card mb-4" style="border-radius: 12px;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center" style="cursor: pointer;" onclick="toggleAddressModal()">
                    <div>
                        <h5 class="fw-bold">选择地址</h5>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </div>
                <div class="mt-3 pt-3 border-top">
                    @if($addresses && count($addresses) > 0)
                        @php
                            $defaultAddress = collect($addresses)->firstWhere('is_default', true) ?? $addresses[0];
                        @endphp
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold text-lg">{{ $defaultAddress['name'] }}</span>
                                    <span class="ms-3 text-muted">{{ $defaultAddress['phone'] }}</span>
                                    @if($defaultAddress['is_default'])
                                        <span class="ms-2 bg-primary text-white text-xs px-2 py-0.5 rounded">默认</span>
                                    @endif
                                </div>
                                <p class="mt-1 text-muted">{{ $defaultAddress['address'] }}</p>
                            </div>
                        </div>
                    @else
                        <p class="text-muted">暂无收货地址，请添加</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="card mb-4" style="border-radius: 12px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">订单信息</h5>
                <div class="mb-4">
                    @foreach($cartWithProducts as $cartItem)
                    <div class="d-flex align-items-center mb-4">
                        <img src="{{ $cartItem['product']['image'] }}" 
                             alt="{{ $cartItem['product']['name'] }}"
                             style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;"
                             class="me-3">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="fw-bold">{{ $cartItem['product']['name'] }}</h6>
                                    <small class="text-muted">{{ $cartItem['product']['category_name'] }}</small>
                                </div>
                                <span class="text-danger fw-bold">¥{{ number_format($cartItem['product']['price'], 2) }}</span>
                            </div>
                        </div>
                        <div class="ms-4 text-right">
                            <span class="text-muted">x{{ $cartItem['quantity'] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-4 pt-4 border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">共计 <strong>{{ $totalItems }}</strong> 件商品</span>
                        <div>
                            <span class="text-muted">订单金额：</span>
                            <span class="text-danger display-6 fw-bold">¥{{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="border-radius: 12px;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">支付方式</span>
                    <div class="d-flex ms-3">
                        <label class="radio-inline me-4">
                            <input type="radio" name="payment_method" value="wechat" checked>
                            <span class="ms-1">微信支付</span>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="payment_method" value="alipay">
                            <span class="ms-1">支付宝</span>
                        </label>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted">实付款：</span>
                        <span class="text-danger display-6 fw-bold">¥{{ number_format($total, 2) }}</span>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg" style="border-radius: 25px; padding-left: 2rem; padding-right: 2rem;">
                        立即结算
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="margin: 0; margin-top: auto; width: 100%; max-width: none; border-radius: 16px 16px 0 0;">
        <div class="modal-content" style="border-radius: 16px 16px 0 0;">
            <div class="modal-header">
                <h5 class="modal-title" id="addressModalLabel">选择地址</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    @foreach($addresses as $address)
                    <div class="d-flex align-items-center p-3 border rounded-lg mb-3" 
                         style="cursor: pointer;"
                         onclick="selectAddress({{ $address['id'] }})">
                        <div class="me-3">
                            <input type="radio" name="address" value="{{ $address['id'] }}" 
                                   {{ $address['is_default'] ? 'checked' : '' }}>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center">
                                <span class="fw-bold">{{ $address['name'] }}</span>
                                <span class="ms-3 text-muted">{{ $address['phone'] }}</span>
                                @if($address['is_default'])
                                    <span class="ms-2 bg-primary text-white text-xs px-2 py-0.5 rounded">默认</span>
                                @endif
                            </div>
                            <p class="mt-1 text-muted text-sm">{{ $address['address'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-block" style="border-radius: 25px;">
                    新增地址
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function toggleAddressModal() {
    var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('addressModal'));
    modal.toggle();
}

function selectAddress(addressId) {
    document.querySelectorAll('input[name="address"]').forEach(function(input) {
        input.checked = (input.value == addressId);
    });
    document.getElementById('selected_address_id').value = addressId;
    toggleAddressModal();
}
</script>
@endsection