<?php
// ------------------------------------------------------
// 用户中心控制器 (UserController)
// 位置: app/Http/Controllers/UserController.php
// 功能: 处理用户个人信息相关操作，包括查看和更新个人资料
// ------------------------------------------------------

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * 获取模拟用户数据（演示用）
     * 在实际项目中，此数据应从数据库读取
     * 返回用户数组，包含ID、姓名、邮箱、手机号、地址等信息
     *
     * @return array 模拟用户数据数组
     */
    private function mockUsers()
    {
        return [
            ['id' => 1, 'name' => '管理员', 'email' => 'admin@shop.com',  'password' => '123456', 'phone' => '13800000001', 'is_admin' => true, 'address' => '北京市朝阳区科技园区1号', 'created_at' => '2025-01-01'],
            ['id' => 2, 'name' => '张三',    'email' => 'zhangsan@shop.com', 'password' => '123456', 'phone' => '13800000002', 'is_admin' => false, 'address' => '上海市浦东新区金融街2号', 'created_at' => '2025-02-15'],
            ['id' => 3, 'name' => '李四',    'email' => 'lisi@shop.com',     'password' => '123456', 'phone' => '13800000003', 'is_admin' => false, 'address' => '广州市天河区珠江新城3号', 'created_at' => '2025-03-20'],
        ];
    }

    /**
     * 显示用户个人资料页面
     * 流程:
     * 1. 检查用户是否已登录（通过 session('user_id') 判断）
     * 2. 未登录 → 重定向到登录页面
     * 3. 已登录 → 根据用户ID查找用户信息
     * 4. 找不到用户 → 重定向到首页并提示错误
     * 5. 找到用户 → 返回个人资料页面
     * 路由: GET /user/profile
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function profile()
    {
        if (!session('user_id')) {
            return redirect()->route('login')->with('error', '请先登录');
        }

        $userId = session('user_id');
        $user = null;
        
        foreach ($this->mockUsers() as $mockUser) {
            if ($mockUser['id'] == $userId) {
                $user = $mockUser;
                break;
            }
        }

        if (!$user) {
            return redirect()->route('home')->with('error', '用户不存在');
        }

        return view('user.profile', compact('user'));
    }

    /**
     * 更新用户个人资料
     * 流程:
     * 1. 检查用户是否已登录
     * 2. 验证表单数据（姓名必填、手机号选填、地址选填）
     * 3. 将更新后的信息写入 Session
     * 4. 重定向到个人资料页面并提示成功
     * 路由: POST /user/profile/update
     *
     * @param \Illuminate\Http\Request $request 请求对象，包含表单数据
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        if (!session('user_id')) {
            return redirect()->route('login')->with('error', '请先登录');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:200',
        ]);

        session([
            'user_name' => $validated['name'],
            'user' => array_merge(session('user', []), $validated),
        ]);

        return redirect()->route('user.profile')->with('success', '个人信息已更新');
    }
}