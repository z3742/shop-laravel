<?php
// ------------------------------------------------------
// 订单控制器 (OrderController)
// 位置: app/Http/Controllers/OrderController.php
// 功能: 处理订单相关操作，包括订单列表和订单详情展示
// ------------------------------------------------------

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * 获取所有模拟商品数据（演示用）
     * 在实际项目中，此数据应从数据库的 products 表读取
     * 返回商品数组，用于订单详情中关联商品信息
     *
     * @return array 模拟商品数据数组
     */
    private function allProducts()
    {
        return [
            ['id' => 1,  'name' => '机械键盘 RGB 青轴',       'price' => 299.00,  'image' => 'https://picsum.photos/seed/keyboard/400/300',  'slug' => 'mechanical-keyboard',        'stock' => 156, 'category_id' => 1, 'category_name' => '手机数码', 'description' => '104键全键无冲，Cherry MX 青轴，RGB 背光，铝合金面板，适合游戏和办公。', 'is_recommended' => true,  'is_hot' => false],
            ['id' => 2,  'name' => '蓝牙降噪耳机 Pro',        'price' => 599.00,  'image' => 'https://picsum.photos/seed/headphone/400/300', 'slug' => 'noise-cancelling-earphone',   'stock' => 89,  'category_id' => 1, 'category_name' => '手机数码', 'description' => 'ANC 主动降噪，40mm 大动圈单元，蓝牙5.3，续航40小时，佩戴舒适。', 'is_recommended' => true,  'is_hot' => true],
            ['id' => 3,  'name' => '无线鼠标静音款',          'price' => 89.00,   'image' => 'https://picsum.photos/seed/mouse/400/300',     'slug' => 'wireless-mouse',              'stock' => 423, 'category_id' => 1, 'category_name' => '手机数码', 'description' => '2.4G 无线连接，静音按键，DPI 三档可调，一节电池用一年。', 'is_recommended' => false, 'is_hot' => false],
            ['id' => 4,  'name' => 'USB-C 快充数据线',         'price' => 29.90,   'image' => 'https://picsum.photos/seed/cable/400/300',     'slug' => 'usb-c-cable',                'stock' => 780, 'category_id' => 1, 'category_name' => '手机数码', 'description' => '100W 快充，编织线材耐弯折，1.5米长度，兼容手机、平板、笔记本。', 'is_recommended' => false, 'is_hot' => false],
            ['id' => 5,  'name' => '高清网络摄像头',          'price' => 199.00,  'image' => 'https://picsum.photos/seed/webcam/400/300',     'slug' => 'webcam-hd',                  'stock' => 67,  'category_id' => 1, 'category_name' => '手机数码', 'description' => '1080P 高清画质，自动对焦，内置降噪麦克风，即插即用免驱动。', 'is_recommended' => false, 'is_hot' => false],
            ['id' => 9,  'name' => '27寸 4K 显示器',           'price' => 2199.00, 'image' => 'https://picsum.photos/seed/monitor/400/300',    'slug' => '4k-monitor',                 'stock' => 34,  'category_id' => 2, 'category_name' => '电脑办公', 'description' => '3840×2160 分辨率，IPS 面板，HDR400，Type-C 一线连，低蓝光护眼。', 'is_recommended' => true,  'is_hot' => false],
            ['id' => 12, 'name' => '无线蓝牙音箱',            'price' => 129.00,  'image' => 'https://picsum.photos/seed/speaker/400/300',    'slug' => 'bluetooth-speaker',          'stock' => 42,  'category_id' => 2, 'category_name' => '电脑办公', 'description' => '双声道立体声，蓝牙5.0，IPX5防水，续航12小时，户外随身。', 'is_recommended' => false, 'is_hot' => true],
            ['id' => 17, 'name' => '纯棉圆领短袖 T 恤',        'price' => 79.00,   'image' => 'https://picsum.photos/seed/tshirt/400/300',     'slug' => 'cotton-tshirt',              'stock' => 356, 'category_id' => 3, 'category_name' => '服装鞋帽', 'description' => '100% 新疆长绒棉，亲肤透气，不变形不缩水，多色可选。', 'is_recommended' => false, 'is_hot' => false],
        ];
    }

    /**
     * 获取模拟订单数据（演示用）
     * 在实际项目中，此数据应从数据库的 orders 和 order_items 表读取
     * 返回订单数组，包含订单号、总金额、状态、配送地址和订单项等信息
     *
     * @return array 模拟订单数据数组
     */
    private function mockOrders()
    {
        return [
            ['id' => 1001, 'user_id' => 2, 'order_no' => 'ORD202507010001', 'total_amount' => 898.00, 'status' => 'completed', 'pay_status' => 'paid', 'shipping_address' => '上海市浦东新区金融街2号', 'created_at' => '2025-07-01 14:30:00', 'items' => [[1, 2], [3, 1]]],
            ['id' => 1002, 'user_id' => 2, 'order_no' => 'ORD202507030002', 'total_amount' => 599.00, 'status' => 'shipped', 'pay_status' => 'paid', 'shipping_address' => '上海市浦东新区金融街2号', 'created_at' => '2025-07-03 10:15:00', 'items' => [[2, 1]]],
            ['id' => 1003, 'user_id' => 2, 'order_no' => 'ORD202507050003', 'total_amount' => 2199.00, 'status' => 'processing', 'pay_status' => 'paid', 'shipping_address' => '上海市浦东新区金融街2号', 'created_at' => '2025-07-05 16:45:00', 'items' => [[9, 1]]],
            ['id' => 1004, 'user_id' => 2, 'order_no' => 'ORD202507070004', 'total_amount' => 207.90, 'status' => 'pending', 'pay_status' => 'unpaid', 'shipping_address' => '上海市浦东新区金融街2号', 'created_at' => '2025-07-07 09:20:00', 'items' => [[4, 2], [17, 2]]],
            ['id' => 1005, 'user_id' => 1, 'order_no' => 'ORD202507020005', 'total_amount' => 129.00, 'status' => 'completed', 'pay_status' => 'paid', 'shipping_address' => '北京市朝阳区科技园区1号', 'created_at' => '2025-07-02 11:00:00', 'items' => [[12, 1]]],
        ];
    }

    /**
     * 显示用户订单列表页面
     * 流程:
     * 1. 检查用户是否已登录（通过 session('user_id') 判断）
     * 2. 未登录 → 重定向到登录页面
     * 3. 已登录 → 根据用户ID筛选订单
     * 4. 遍历订单，将订单项中的商品ID转换为完整商品信息
     * 5. 返回订单列表页面，包含所有关联商品信息
     * 路由: GET /order
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if (!session('user_id')) {
            return redirect()->route('login')->with('error', '请先登录');
        }

        $userId = session('user_id');
        $orders = [];

        $allOrders = array_merge($this->mockOrders(), session('orders', []));

        $statusFilter = $request->query('status');

        foreach ($allOrders as $order) {
            if ($order['user_id'] == $userId) {
                if ($statusFilter) {
                    if ($statusFilter == 'pending' && !in_array($order['status'], ['pending', 'processing', 'shipped'])) {
                        continue;
                    }
                    if ($statusFilter == 'completed' && $order['status'] != 'completed') {
                        continue;
                    }
                }

                $orderItems = [];
                foreach ($order['items'] as $item) {
                    $productId = $item[0];
                    $quantity = $item[1];
                    foreach ($this->allProducts() as $product) {
                        if ($product['id'] == $productId) {
                            $orderItems[] = [
                                'product' => $product,
                                'quantity' => $quantity,
                                'subtotal' => $product['price'] * $quantity,
                            ];
                            break;
                        }
                    }
                }
                $order['order_items'] = $orderItems;
                $orders[] = $order;
            }
        }

        return view('order.index', compact('orders'));
    }

    /**
     * 显示订单详情页面
     * 流程:
     * 1. 检查用户是否已登录
     * 2. 根据订单ID查找订单
     * 3. 验证订单所属用户，非本人订单返回403错误
     * 4. 订单不存在返回404错误
     * 5. 将订单项中的商品ID转换为完整商品信息
     * 6. 返回订单详情页面
     * 路由: GET /order/{orderId}
     *
     * @param int $orderId 订单ID
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show($orderId)
    {
        if (!session('user_id')) {
            return redirect()->route('login')->with('error', '请先登录');
        }

        $userId = session('user_id');
        $order = null;

        $allOrders = array_merge($this->mockOrders(), session('orders', []));

        foreach ($allOrders as $mockOrder) {
            if ($mockOrder['id'] == $orderId) {
                if ($mockOrder['user_id'] != $userId) {
                    abort(403, '无权查看此订单');
                }
                $order = $mockOrder;
                break;
            }
        }

        if (!$order) {
            abort(404, '订单不存在');
        }

        $orderItems = [];
        foreach ($order['items'] as $item) {
            $productId = $item[0];
            $quantity = $item[1];
            foreach ($this->allProducts() as $product) {
                if ($product['id'] == $productId) {
                    $orderItems[] = [
                        'product' => $product,
                        'quantity' => $quantity,
                        'subtotal' => $product['price'] * $quantity,
                    ];
                    break;
                }
            }
        }

        return view('order.show', compact('order', 'orderItems'));
    }

    /**
     * 创建订单（处理结算请求）
     * 流程:
     * 1. 检查用户是否已登录
     * 2. 获取购物车数据并验证非空
     * 3. 获取用户选择的地址信息
     * 4. 生成订单号（格式：ORD + 年月日 + 4位随机数）
     * 5. 计算订单总金额
     * 6. 组装订单数据并保存（当前演示使用 Session 存储）
     * 7. 清空购物车
     * 8. 重定向到订单列表页面并提示成功
     * 路由: POST /order/store
     *
     * @param \Illuminate\Http\Request $request 请求对象，包含支付方式和地址ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if (!session('user_id')) {
            return redirect()->route('login')->with('error', '请先登录');
        }

        $cartItems = session('cart', []);
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', '购物车为空');
        }

        $validated = $request->validate([
            'payment_method' => 'required|string|in:wechat,alipay',
            'selected_address_id' => 'required|integer',
        ]);

        $userId = session('user_id');
        $orderNo = 'ORD' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

        $totalAmount = 0;
        $orderItems = [];
        foreach ($cartItems as $productId => $quantity) {
            foreach ($this->allProducts() as $product) {
                if ($product['id'] == $productId) {
                    $totalAmount += $product['price'] * $quantity;
                    $orderItems[] = [$productId, $quantity];
                    break;
                }
            }
        }

        $addresses = [
            ['id' => 1, 'name' => '康刘勇', 'phone' => '15219209751', 'address' => '广东省广州市天河区珠吉街道100号', 'is_default' => true],
            ['id' => 2, 'name' => '爱丽丝', 'phone' => '16809801654', 'address' => '北京市东城区108街道', 'is_default' => false],
        ];
        $selectedAddress = collect($addresses)->firstWhere('id', $validated['selected_address_id']) ?? $addresses[0];
        $shippingAddress = $selectedAddress['name'] . ' ' . $selectedAddress['phone'] . ' ' . $selectedAddress['address'];

        $newOrder = [
            'id' => time(),
            'user_id' => $userId,
            'order_no' => $orderNo,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'pay_status' => 'unpaid',
            'payment_method' => $validated['payment_method'],
            'shipping_address' => $shippingAddress,
            'created_at' => date('Y-m-d H:i:s'),
            'items' => $orderItems,
        ];

        $orders = session('orders', []);
        $orders[] = $newOrder;
        session(['orders' => $orders]);

        session()->forget('cart');

        return redirect()->route('order.index')->with('success', '订单创建成功！订单号：' . $orderNo);
    }
}