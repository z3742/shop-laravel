<?php
// ------------------------------------------------------
// 用户认证控制器 (AuthController)
// 位置: app/Http/Controllers/AuthController.php
// 功能: 处理用户登录、注册、退出登录等认证相关操作
// ------------------------------------------------------

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * 获取模拟用户数据（演示用）
     * 在实际项目中，此数据应从数据库读取，密码应使用 bcrypt 加密存储
     * 返回用户数组，包含ID、姓名、邮箱、密码、手机号和管理员标识
     *
     * @return array 模拟用户数据数组
     */
    private function mockUsers()
    {
        return [
            ['id' => 1, 'name' => '管理员', 'email' => 'admin@shop.com',  'password' => '123456', 'phone' => '13800000001', 'is_admin' => true],
            ['id' => 2, 'name' => '张三',    'email' => 'zhangsan@shop.com', 'password' => '123456', 'phone' => '13800000002', 'is_admin' => false],
            ['id' => 3, 'name' => '李四',    'email' => 'lisi@shop.com',     'password' => '123456', 'phone' => '13800000003', 'is_admin' => false],
        ];
    }

    /**
     * 显示登录表单页面
     * 路由: GET /login
     *
     * @return \Illuminate\View\View 返回登录表单视图
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * 处理登录请求
     * 流程:
     * 1. 验证邮箱和密码输入（邮箱必填+格式验证，密码必填+至少6位）
     * 2. 在模拟用户数组中查找匹配用户（邮箱不区分大小写）
     * 3. 找到用户 → 写入 Session 表示已登录 → 重新生成 Session ID 防固定攻击
     * 4. 根据用户类型跳转（管理员/普通用户）
     * 5. 未找到 → 返回登录页并提示错误，保留邮箱输入
     * 路由: POST /login
     *
     * @param \Illuminate\Http\Request $request 请求对象，包含登录表单数据
     * @return \Illuminate\Http\RedirectResponse 重定向到首页或返回登录页
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email|max:100',
            'password' => 'required|string|min:6',
        ]);

        $email = $validated['email'];
        $password = $validated['password'];
        $user = null;

        foreach ($this->mockUsers() as $mockUser) {
            if (strtolower($mockUser['email']) === strtolower($email) && $mockUser['password'] === $password) {
                $user = $mockUser;
                break;
            }
        }

        if ($user !== null) {
            session([
                'user_id'    => $user['id'],
                'user_name'  => $user['name'],
                'user_email' => $user['email'],
                'is_admin'   => $user['is_admin'],
                'user'       => $user,
            ]);

            $request->session()->regenerate();

            if ($user['is_admin']) {
                return redirect()->route('home')->with('success', '管理员 ' . $user['name'] . ', 欢迎回来！');
            }

            return redirect()->intended(route('home'))
                ->with('success', $user['name'] . ', 欢迎回来！');
        }

        return back()
            ->withErrors(['email' => '邮箱或密码错误'])
            ->withInput($request->except('password'));
    }

    /**
     * 显示注册表单页面
     * 路由: GET /register
     *
     * @return \Illuminate\View\View 返回注册表单视图
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * 处理注册请求
     * 流程:
     * 1. 验证表单数据（用户名、邮箱、密码必填，密码需确认）
     * 2. 检查邮箱是否已被注册（邮箱不区分大小写）
     * 3. 邮箱已存在 → 返回注册页并提示错误
     * 4. 邮箱未存在 → 模拟注册，将用户信息写入 Session
     * 5. 重新生成 Session ID → 跳转到首页并提示注册成功
     * 路由: POST /register
     *
     * @param \Illuminate\Http\Request $request 请求对象，包含注册表单数据
     * @return \Illuminate\Http\RedirectResponse 重定向到首页或返回注册页
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:50',
            'email'    => 'required|email|max:100',
            'password' => 'required|string|min:6|confirmed',
            'phone'    => 'nullable|string|max:20',
        ]);

        foreach ($this->mockUsers() as $mockUser) {
            if (strtolower($mockUser['email']) === strtolower($validated['email'])) {
                return back()
                    ->withErrors(['email' => '该邮箱已被注册'])
                    ->withInput();
            }
        }

        $newId = count($this->mockUsers()) + 1;
        session([
            'user_id'    => $newId,
            'user_name'  => $validated['name'],
            'user_email' => $validated['email'],
            'is_admin'   => false,
        ]);

        $request->session()->regenerate();

        return redirect()->route('home')
            ->with('success', '注册成功，欢迎加入！');
    }

    /**
     * 处理退出登录
     * 流程:
     * 1. 清除登录相关的 Session 键（user_id, user_name, user_email, is_admin）
     * 2. 使整个 Session 失效（防 Session 劫持）
     * 3. 重新生成 CSRF Token（安全措施）
     * 4. 跳转到首页并提示退出成功
     * 路由: POST /logout
     *
     * @param \Illuminate\Http\Request $request 请求对象
     * @return \Illuminate\Http\RedirectResponse 重定向到首页
     */
    public function logout(Request $request)
    {
        session()->forget(['user_id', 'user_name', 'user_email', 'is_admin']);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', '您已成功退出登录');
    }
}