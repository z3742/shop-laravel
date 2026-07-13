@extends('layouts.app')

@section('title', '个人中心')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">首页</a></li>
            <li class="breadcrumb-item active" aria-current="page">个人中心</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-person-circle display-4 text-primary"></i>
                    </div>
                    <h4 class="card-title">{{ $user['name'] }}</h4>
                    <p class="text-muted">{{ $user['email'] }}</p>
                    @if($user['is_admin'])
                    <span class="badge bg-success mt-2">管理员</span>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="bi bi-list"></i> 菜单</h6>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('user.profile') }}" class="list-group-item list-group-item-action active">
                        <i class="bi bi-person"></i> 个人信息
                    </a>
                    <a href="{{ route('order.index') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-receipt"></i> 我的订单
                    </a>
                    <a href="{{ route('cart.index') }}" class="list-group-item list-group-item-action">
                        <i class="bi bi-cart3"></i> 购物车
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-pencil"></i> 修改个人信息</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.profile.update') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">用户名</label>
                                <input type="text" name="name" id="name" class="form-control"
                                       value="{{ $user['name'] }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">邮箱地址</label>
                                <input type="email" name="email" id="email" class="form-control"
                                       value="{{ $user['email'] }}" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">手机号码</label>
                                <input type="tel" name="phone" id="phone" class="form-control"
                                       value="{{ $user['phone'] ?? '' }}" placeholder="请输入手机号">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="is_admin" class="form-label">用户类型</label>
                                <input type="text" class="form-control"
                                       value="{{ $user['is_admin'] ? '管理员' : '普通用户' }}" readonly>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">收货地址</label>
                            <textarea name="address" id="address" class="form-control" rows="3"
                                      placeholder="请输入收货地址">{{ $user['address'] ?? '' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">注册时间</label>
                            <p class="form-control-plaintext">{{ $user['created_at'] ?? '-' }}</p>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> 保存修改
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection