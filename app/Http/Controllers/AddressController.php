 <?php
// ------------------------------------------------------
// 地址管理控制器 (AddressController)
// 位置: app/Http/Controllers/AddressController.php
// 功能: 处理用户收货地址的增删改查操作
// ------------------------------------------------------

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * 获取用户地址列表
     * 路由: GET /user/addresses
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        if (!session('user_id')) {
            return redirect()->route('login')->with('error', '请先登录');
        }

        $addresses = session('addresses', [
            ['id' => 1, 'name' => '康刘勇', 'phone' => '15219209751', 'address' => '广东省广州市天河区珠吉街道100号', 'is_default' => true],
            ['id' => 2, 'name' => '爱丽丝', 'phone' => '16809801654', 'address' => '北京市东城区108街道', 'is_default' => false],
        ]);

        return view('user.addresses', compact('addresses'));
    }

    /**
     * 添加新地址
     * 路由: POST /user/addresses/add
     *
     * @param \Illuminate\Http\Request $request 请求对象
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(Request $request)
    {
        if (!session('user_id')) {
            return redirect()->route('login')->with('error', '请先登录');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:200',
            'is_default' => 'nullable|boolean',
        ]);

        $addresses = session('addresses', [
            ['id' => 1, 'name' => '康刘勇', 'phone' => '15219209751', 'address' => '广东省广州市天河区珠吉街道100号', 'is_default' => true],
            ['id' => 2, 'name' => '爱丽丝', 'phone' => '16809801654', 'address' => '北京市东城区108街道', 'is_default' => false],
        ]);

        if ($validated['is_default']) {
            foreach ($addresses as &$addr) {
                $addr['is_default'] = false;
            }
        }

        $newId = count($addresses) + 1;
        $addresses[] = [
            'id' => $newId,
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'is_default' => $validated['is_default'] ?? false,
        ];

        session(['addresses' => $addresses]);

        return redirect()->route('user.addresses')->with('success', '地址添加成功');
    }

    /**
     * 删除地址
     * 路由: POST /user/addresses/delete/{addressId}
     *
     * @param int $addressId 地址ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($addressId)
    {
        if (!session('user_id')) {
            return redirect()->route('login')->with('error', '请先登录');
        }

        $addresses = session('addresses', [
            ['id' => 1, 'name' => '康刘勇', 'phone' => '15219209751', 'address' => '广东省广州市天河区珠吉街道100号', 'is_default' => true],
            ['id' => 2, 'name' => '爱丽丝', 'phone' => '16809801654', 'address' => '北京市东城区108街道', 'is_default' => false],
        ]);

        $addresses = array_filter($addresses, function ($addr) use ($addressId) {
            return $addr['id'] != $addressId;
        });

        session(['addresses' => array_values($addresses)]);

        return redirect()->route('user.addresses')->with('success', '地址删除成功');
    }

    /**
     * 设置默认地址
     * 路由: POST /user/addresses/set-default/{addressId}
     *
     * @param int $addressId 地址ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setDefault($addressId)
    {
        if (!session('user_id')) {
            return redirect()->route('login')->with('error', '请先登录');
        }

        $addresses = session('addresses', [
            ['id' => 1, 'name' => '康刘勇', 'phone' => '15219209751', 'address' => '广东省广州市天河区珠吉街道100号', 'is_default' => true],
            ['id' => 2, 'name' => '爱丽丝', 'phone' => '16809801654', 'address' => '北京市东城区108街道', 'is_default' => false],
        ]);

        foreach ($addresses as &$addr) {
            $addr['is_default'] = ($addr['id'] == $addressId);
        }

        session(['addresses' => $addresses]);

        return redirect()->route('user.addresses')->with('success', '默认地址设置成功');
    }
}