{{-- ======================================================
    登录页面
    位置: resources/views/auth/login.blade.php
====================================================== --}}
@extends('layouts.app')

@section('title', '用户登录')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        {{-- 登录卡片居中，最大宽度 500px --}}
        <div class="col-md-5">
            <div class="card shadow">
                {{-- 卡片头部（蓝色背景） --}}
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-box-arrow-in-right"></i> 用户登录</h5>
                </div>
                <div class="card-body">

                    {{-- 登录表单 --}}
                    {{--
                    action="{{ route('login.post') }}"
                    → 使用命名路由生成 URL，实际指向 POST /login

                    method="POST"
                    → 登录是"提交数据"的操作，用 POST 方式
                    --}}
                    <form action="{{ route('login.post') }}" method="POST">
                        {{-- @csrf 生成隐藏的 CSRF 令牌字段，防止跨站请求伪造 --}}
                        @csrf

                        {{-- ===== 邮箱输入框 ===== --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">邮箱地址</label>
                            <input type="email" name="email" id="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}"
                                   {{--
                                   old('email') → 验证失败后保留上次输入的邮箱
                                   避免用户重新输入—提升体验
                                   --}}
                                   placeholder="请输入邮箱" required autofocus>
                            {{--
                            @error('email')
                            如果邮箱验证失败，显示红色错误信息
                            {{ $message }} 是 Laravel 自动注入的验证错误消息
                            --}}
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ===== 密码输入框 ===== --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">密码</label>
                            <input type="password" name="password" id="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="请输入密码" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ===== 记住我复选框 ===== --}}
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" id="remember"
                                   class="form-check-input {{ old('remember') ? 'checked' : '' }}">
                            <label class="form-check-label" for="remember">记住登录状态</label>
                        </div>

                        {{-- ===== 提交按钮（占满整行） ===== --}}
                        {{-- d-grid → 让按钮成为块级元素并撑满容器宽度 --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right"></i> 立即登录
                            </button>
                        </div>
                    </form>

                    {{-- ===== 跳转注册链接 ===== --}}
                    <div class="mt-3 text-center">
                        <span class="text-muted">还没有账号？</span>
                        <a href="{{ route('register') }}">立即注册</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection