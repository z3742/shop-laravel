<?php
// ======================================================
// 控制器基类 (Controller)
// 位置: app/Http/Controllers/Controller.php
// 说明: 所有自定义控制器的父类，提供控制器的基础功能和共享方法
// ======================================================

namespace App\Http\Controllers;

/**
 * 控制器基类
 * 
 * 所有应用控制器都应继承此类，该类继承自 Laravel 框架的基础控制器，
 * 提供了中间件管理、路由绑定等核心功能。
 * 
 * 使用方式:
 * <code>
 * class HomeController extends Controller
 * {
 *     // 控制器方法
 * }
 * </code>
 */
abstract class Controller
{
    // 此类为抽象基类，由 Laravel 框架提供基础功能
    // 可在此添加项目级别的共享方法或中间件配置
}
