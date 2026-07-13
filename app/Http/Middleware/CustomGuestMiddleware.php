<?php
/**
 * ============================================================
 * 自定义 Guest 中间件
 * 位置：app/Http/Middleware/CustomGuestMiddleware.php
 * ============================================================
 * 功能：已登录用户（Session 中有 user_id）访问登录/注册页时
 *       自动重定向到首页，避免重复登录。
 *
 *   Route::middleware('guest')->group(...)
 *   内置 guest 中间件会自动检测 Auth::check()
 * ============================================================
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomGuestMiddleware
{
    /**
     * 处理请求
     *
     * @param  Request  $request
     * @param  Closure  $next   → 下一个中间件/控制器
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // 如果 Session 中有 user_id 说明用户已登录
        if (session()->has('user_id')) {
            // 重定向到首页，附带提示消息
            return redirect()->route('home')
                ->with('info', '您已登录，无需重复操作');
        }

        // 未登录 → 放行，继续执行后续中间件和控制器
        return $next($request);
    }
}
