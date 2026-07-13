@extends('layouts.app')

@section('title', '收货地址')

@section('content')
<div class="container-fluid p-0">
    <div class="bg-primary text-white py-4 px-4">
        <div class="d-flex align-items-center">
            <a href="{{ route('user.profile') }}" class="text-white me-3">
                <i class="bi bi-arrow-left"></i> 返回
            </a>
            <h3 class="mb-0">收货地址</h3>
        </div>
    </div>

    <div class="container py-4">
        @if(count($addresses) > 0)
            <div class="space-y-3">
                @foreach($addresses as $address)
                <div class="card" style="border-radius: 8px;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold text-lg">{{ $address['name'] }}</span>
                                    <span class="ms-3 text-muted">{{ $address['phone'] }}</span>
                                    @if($address['is_default'])
                                        <span class="ms-2 bg-primary text-white text-xs px-2 py-0.5 rounded">默认</span>
                                    @endif
                                </div>
                                <p class="mt-2 text-muted">{{ $address['address'] }}</p>
                            </div>
                            <div class="d-flex flex-column gap-2">
                                @if(!$address['is_default'])
                                    <form action="{{ route('user.addresses.set-default', $address['id']) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-primary">设为默认</button>
                                    </form>
                                @endif
                                <form action="{{ route('user.addresses.delete', $address['id']) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('确定删除此地址？')">删除</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-map-pin display-4 text-muted mb-3"></i>
                    <h4 class="text-muted mb-2">暂无收货地址</h4>
                    <p class="text-muted mb-4">请添加收货地址以便购物结算</p>
                </div>
            </div>
        @endif

        <button type="button" class="btn btn-primary btn-block mt-4" style="border-radius: 25px;" data-bs-toggle="modal" data-bs-target="#addAddressModal">
            <i class="bi bi-plus"></i> 新增地址
        </button>
    </div>
</div>

<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAddressModalLabel">新增收货地址</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user.addresses.add') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">收货人</label>
                        <input type="text" class="form-control" id="name" name="name" required placeholder="请输入收货人姓名">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">联系电话</label>
                        <input type="text" class="form-control" id="phone" name="phone" required placeholder="请输入联系电话">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">详细地址</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required placeholder="请输入详细地址"></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_default" name="is_default">
                            <label class="form-check-label" for="is_default">设为默认地址</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection