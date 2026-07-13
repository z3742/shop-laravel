{{-- ======================================================
注册页面
位置: resources/views/auth/register.blade.php
====================================================== --}}
@extends('layouts.app')

@section('title', '用户注册')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                {{-- 卡片头部（绿色背景） --}}
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-person-plus"></i> 用户注册</h5>
                </div>
                <div class="card-body">

                    {{-- 注册表单 --}}
                    <form action="{{ route('register.post') }}" method="POST">
                        @csrf

                        {{-- ===== 用户名 ===== --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">用户名 <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="请输入用户名（最多50字符）" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ===== 邮箱 ===== --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">邮箱地址 <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}"
                                   placeholder="请输入有效邮箱" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ===== 手机号（选填） ===== --}}
                        <div class="mb-3">
                            <label for="phone" class="form-label">手机号码</label>
                            <input type="text" name="phone" id="phone"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone') }}"
                                   placeholder="请输入手机号码（选填）">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ===== 密码 ===== --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">密码 <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="至少6位字符" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ===== 确认密码 ===== --}}
                        {{--
                        password_confirmation 是 Laravel 的命名约定：
                        当验证规则中有 confirmed 时，Laravel 自动匹配
                        password 和 password_confirmation 字段的值是否一致
                        --}}
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">确认密码 <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-control"
                                   placeholder="请再次输入密码" required>
                        </div>

                        {{-- ===== 提交按钮 ===== --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-person-check"></i> 立即注册
                            </button>
                        </div>
                    </form>

                    {{-- ===== 已有账号？去登录 ===== --}}
                    <div class="mt-3 text-center">
                        <span class="text-muted">已有账号？</span>
                        <a href="{{ route('login') }}">立即登录</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection