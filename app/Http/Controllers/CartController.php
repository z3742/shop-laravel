<?php
/*
 * 购物车控制器
 * 位置: app/Http/Controllers/CartController.php
 * 负责处理购物车相关的所有请求: 查看购物车、添加商品、更新数量、移除商品、清空购物车
 * 购物车数据存储在 Session 中，格式为: ['商品ID' => '数量']
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * 获取所有商品数据 (数据源方法)
     * 当前返回写死数组 (零数据库依赖)。
     * 接入数据库后, 应创建 Product 模型并替换为:
     * return Product::all();
     * 
     * @return array 商品数组列表
     */
    private function allProducts()
    {
        return [
            ['id' => 1,  'name' => '机械键盘 RGB 青轴',       'price' => 299.00,  'image' => 'https://picsum.photos/seed/keyboard/400/300',  'slug' => 'mechanical-keyboard',        'stock' => 156, 'category_id' => 1, 'category_name' => '手机数码', 'description' => '104键全键无冲，Cherry MX 青轴，RGB 背光，铝合金面板，适合游戏和办公。', 'is_recommended' => true,  'is_hot' => false],
            ['id' => 2,  'name' => '蓝牙降噪耳机 Pro',        'price' => 599.00,  'image' => 'https://picsum.photos/seed/headphone/400/300', 'slug' => 'noise-cancelling-earphone',   'stock' => 89,  'category_id' => 1, 'category_name' => '手机数码', 'description' => 'ANC 主动降噪，40mm 大动圈单元，蓝牙5.3，续航40小时，佩戴舒适。', 'is_recommended' => true,  'is_hot' => true],
            ['id' => 3,  'name' => '无线鼠标静音款',          'price' => 89.00,   'image' => 'https://picsum.photos/seed/mouse/400/300',     'slug' => 'wireless-mouse',              'stock' => 423, 'category_id' => 1, 'category_name' => '手机数码', 'description' => '2.4G 无线连接，静音按键，DPI 三档可调，一节电池用一年。', 'is_recommended' => false, 'is_hot' => false],
            ['id' => 4,  'name' => 'USB-C 快充数据线',         'price' => 29.90,   'image' => 'https://picsum.photos/seed/cable/400/300',     'slug' => 'usb-c-cable',                'stock' => 780, 'category_id' => 1, 'category_name' => '手机数码', 'description' => '100W 快充，编织线材耐弯折，1.5米长度，兼容手机、平板、笔记本。', 'is_recommended' => false, 'is_hot' => false],
            ['id' => 5,  'name' => '高清网络摄像头',          'price' => 199.00,  'image' => 'https://picsum.photos/seed/webcam/400/300',     'slug' => 'webcam-hd',                  'stock' => 67,  'category_id' => 1, 'category_name' => '手机数码', 'description' => '1080P 高清画质，自动对焦，内置降噪麦克风，即插即用免驱动。', 'is_recommended' => false, 'is_hot' => false],
            ['id' => 6,  'name' => '手机散热背夹',             'price' => 49.00,   'image' => 'https://picsum.photos/seed/cooler/400/300',    'slug' => 'phone-cooler',               'stock' => 18,  'category_id' => 1, 'category_name' => '手机数码', 'description' => '半导体制冷，急速降温，RGB 灯效，兼容4-7英寸手机，游戏直播必备。', 'is_recommended' => false, 'is_hot' => true],
            ['id' => 7,  'name' => '大容量充电宝 20000mAh',    'price' => 139.00,  'image' => 'https://picsum.photos/seed/powerbank/400/300',  'slug' => 'power-bank',                 'stock' => 97,  'category_id' => 1, 'category_name' => '手机数码', 'description' => '20000mAh 大容量，22.5W 双向快充，支持三设备同时充电，登机无忧。', 'is_recommended' => true,  'is_hot' => false],
            ['id' => 8,  'name' => '智能手环运动版',          'price' => 199.00,  'image' => 'https://picsum.photos/seed/band/400/300',       'slug' => 'smart-band',                 'stock' => 143, 'category_id' => 1, 'category_name' => '手机数码', 'description' => '1.47英寸 AMOLED 屏，心率血氧监测，50米防水，14天超长续航。', 'is_recommended' => false, 'is_hot' => true],
            ['id' => 9,  'name' => '27寸 4K 显示器',           'price' => 2199.00, 'image' => 'https://picsum.photos/seed/monitor/400/300',    'slug' => '4k-monitor',                 'stock' => 34,  'category_id' => 2, 'category_name' => '电脑办公', 'description' => '3840×2160 分辨率，IPS 面板，HDR400，Type-C 一线连，低蓝光护眼。', 'is_recommended' => true,  'is_hot' => false],
            ['id' => 10, 'name' => 'Type-C 扩展坞 7合1',       'price' => 159.00,  'image' => 'https://picsum.photos/seed/dock/400/300',      'slug' => 'type-c-dock',                'stock' => 201, 'category_id' => 2, 'category_name' => '电脑办公', 'description' => 'HDMI 4K输出，USB3.0×3，SD/TF 读卡器，PD 100W 快充，铝合金散热。', 'is_recommended' => false, 'is_hot' => false],
            ['id' => 11, 'name' => '笔记本电脑支架',          'price' => 69.00,   'image' => 'https://picsum.photos/seed/stand/400/300',      'slug' => 'laptop-stand',               'stock' => 512, 'category_id' => 2, 'category_name' => '电脑办公', 'description' => '铝合金六档高度可调，镂空散热设计，防滑硅胶垫，折叠便携。', 'is_recommended' => false, 'is_hot' => false],
            ['id' => 12, 'name' => '无线蓝牙音箱',            'price' => 129.00,  'image' => 'https://picsum.photos/seed/speaker/400/300',    'slug' => 'bluetooth-speaker',          'stock' => 42,  'category_id' => 2, 'category_name' => '电脑办公', 'description' => '双声道立体声，蓝牙5.0，IPX5防水，续航12小时，户外随身。', 'is_recommended' => false, 'is_hot' => true],
            ['id' => 13, 'name' => '桌面 LED 护眼灯',          'price' => 79.00,   'image' => 'https://picsum.photos/seed/lamp/400/300',      'slug' => 'led-desk-lamp',              'stock' => 234, 'category_id' => 2, 'category_name' => '电脑办公', 'description' => '无频闪 LED 光源，三档色温无极调光，USB 供电，柔光面罩不刺眼。', 'is_recommended' => false, 'is_hot' => false],
            ['id' => 14, 'name' => '移动固态硬盘 1TB',         'price' => 499.00,  'image' => 'https://picsum.photos/seed/ssd/400/300',       'slug' => 'portable-ssd',               'stock' => 56,  'category_id' => 2, 'category_name' => '电脑办公', 'description' => '读取速度 1050MB/s，USB 3.2 Gen2，仅重45g，兼容 Win/Mac。', 'is_recommended' => true,  'is_hot' => false],
            ['id' => 15, 'name' => '降噪麦克风套装',          'price' => 259.00,  'image' => 'https://picsum.photos/seed/mic/400/300',        'slug' => 'mic-kit',                    'stock' => 28,  'category_id' => 2, 'category_name' => '电脑办公', 'description' => '心形指向拾音，USB 即插即用，含悬臂支架和防喷罩，直播录音利器。', 'is_recommended' => false, 'is_hot' => false],
            ['id' => 16, 'name' => '人体工学办公椅',          'price' => 899.00,  'image' => 'https://picsum.photos/seed/chair/400/300',      'slug' => 'ergonomic-chair',            'stock' => 11,  'category_id' => 2, 'category_name' => '电脑办公', 'description' => '透气网布，4D 调节扶手，135° 后仰，SGS 认证气杆，久坐不累。', 'is_recommended' => true,  'is_hot' => false],
            ['id' => 17, 'name' => '纯棉圆领短袖 T 恤',        'price' => 79.00,   'image' => 'https://picsum.photos/seed/tshirt/400/300',     'slug' => 'cotton-tshirt',              'stock' => 356, 'category_id' => 3, 'category_name' => '服装鞋帽', 'description' => '100% 新疆长绒棉，亲肤透气，不变形不缩水，多色可选。', 'is_recommended' => false, 'is_hot' => false],
            ['id' => 18, 'name' => '轻薄防晒衣 UPF50+',        'price' => 149.00,  'image' => 'https://picsum.photos/seed/jacket/400/300',     'slug' => 'sun-protection-jacket',      'stock' => 189, 'category_id' => 3, 'category_name' => '服装鞋帽', 'description' => 'UPF50+ 高效防晒，透气速干面料，可收纳帽兜设计，仅重120g。', 'is_recommended' => true,  'is_hot' => false],
            ['id' => 19, 'name' => '复古运动鞋经典款',        'price' => 299.00,  'image' => 'https://picsum.photos/seed/sneaker/400/300',     'slug' => 'retro-sneakers',             'stock' => 122, 'category_id' => 3, 'category_name' => '服装鞋帽', 'description' => '80年代复古设计，EVA 缓震中底，防滑橡胶大底，百搭配色。', 'is_recommended' => false, 'is_hot' => true],
            ['id' => 20, 'name' => '商务休闲西裤',             'price' => 199.00,  'image' => 'https://picsum.photos/seed/pants/400/300',      'slug' => 'business-pants',             'stock' => 87,  'category_id' => 3, 'category_name' => '服装鞋帽', 'description' => '免烫抗皱面料，修身直筒版型，弹力舒适，商务通勤两相宜。', 'is_recommended' => false, 'is_hot' => false],
            ['id' => 21, 'name' => '记忆棉颈椎枕头',          'price' => 99.00,   'image' => 'https://picsum.photos/seed/pillow/400/300',     'slug' => 'memory-foam-pillow',         'stock' => 278, 'category_id' => 4, 'category_name' => '家居生活', 'description' => '慢回弹记忆棉，人体工学弧度，透气天丝枕套，缓解颈椎疲劳。', 'is_recommended' => false, 'is_hot' => false],
            ['id' => 22, 'name' => '加厚不锈钢汤锅',          'price' => 159.00,  'image' => 'https://picsum.photos/seed/pot/400/300',        'slug' => 'stainless-steel-pot',        'stock' => 165, 'category_id' => 4, 'category_name' => '家居生活', 'description' => '304 食品级不锈钢，三层复合底，电磁炉燃气通用，5升容量。', 'is_recommended' => true,  'is_hot' => false],
            ['id' => 23, 'name' => '自动感应洗手液机',        'price' => 89.00,   'image' => 'https://picsum.photos/seed/soap/400/300',        'slug' => 'auto-soap-dispenser',        'stock' => 312, 'category_id' => 4, 'category_name' => '家居生活', 'description' => '红外感应出泡，0.25秒快速响应，IPX5防水，USB充电一次用3个月。', 'is_recommended' => false, 'is_hot' => false],
            ['id' => 24, 'name' => '折叠收纳箱 66L',           'price' => 49.90,   'image' => 'https://picsum.photos/seed/box/400/300',        'slug' => 'folding-storage-box',        'stock' => 445, 'category_id' => 4, 'category_name' => '家居生活', 'description' => '加厚 PP 材质，承重50kg，带盖防尘，折叠后仅5cm厚，搬家利器。', 'is_recommended' => false, 'is_hot' => false],
            ['id' => 25, 'name' => '云南小粒咖啡豆 500g',      'price' => 69.00,   'image' => 'https://picsum.photos/seed/coffee/400/300',     'slug' => 'yunnan-coffee-beans',        'stock' => 234, 'category_id' => 5, 'category_name' => '食品饮料', 'description' => '云南保山高海拔产区，中度烘焙，焦糖坚果风味，现磨现喝。', 'is_recommended' => true,  'is_hot' => false],
            ['id' => 26, 'name' => '红枣枸杞养生茶礼盒',      'price' => 128.00,  'image' => 'https://picsum.photos/seed/tea/400/300',        'slug' => 'red-date-goji-tea',          'stock' => 156, 'category_id' => 5, 'category_name' => '食品饮料', 'description' => '新疆红枣 + 宁夏枸杞，独立小包装，冲泡即饮，送礼自用皆宜。', 'is_recommended' => false, 'is_hot' => false],
            ['id' => 27, 'name' => '每日坚果混合装 30袋',      'price' => 99.00,   'image' => 'https://picsum.photos/seed/nuts/400/300',       'slug' => 'daily-mixed-nuts',           'stock' => 389, 'category_id' => 5, 'category_name' => '食品饮料', 'description' => '核桃仁+腰果+巴旦木+榛子+蓝莓干，每日一袋，科学配比。', 'is_recommended' => false, 'is_hot' => true],
            ['id' => 28, 'name' => '进口黑巧克力礼盒',        'price' => 59.90,   'image' => 'https://picsum.photos/seed/chocolate/400/300',   'slug' => 'dark-chocolate-gift',        'stock' => 267, 'category_id' => 5, 'category_name' => '食品饮料', 'description' => '比利时进口，72% 可可含量，丝滑口感，精美铁盒包装。', 'is_recommended' => false, 'is_hot' => false],
            ['id' => 29, 'name' => '《Laravel 框架入门与实战》', 'price' => 59.00, 'image' => 'https://picsum.photos/seed/laravelbook/400/300', 'slug' => 'laravel-book',               'stock' => 198, 'category_id' => 6, 'category_name' => '图书教育', 'description' => '从路由到 Eloquent，项目驱动教学，附完整商城源码，新手友好。', 'is_recommended' => true,  'is_hot' => false],
            ['id' => 30, 'name' => '《PHP 从入门到精通》',     'price' => 49.00,   'image' => 'https://picsum.photos/seed/phpbook/400/300',    'slug' => 'php-book',                   'stock' => 312, 'category_id' => 6, 'category_name' => '图书教育', 'description' => 'PHP 8 最新特性详解，面向对象、Composer、测试驱动开发全覆盖。', 'is_recommended' => false, 'is_hot' => false],
            ['id' => 31, 'name' => '《MySQL 必知必会》',        'price' => 39.00,   'image' => 'https://picsum.photos/seed/mysqlbook/400/300',  'slug' => 'mysql-book',                 'stock' => 234, 'category_id' => 6, 'category_name' => '图书教育', 'description' => '从 SELECT 到事务锁，图解索引原理，每条 SQL 都带执行计划分析。', 'is_recommended' => false, 'is_hot' => false],
            ['id' => 32, 'name' => '《算法导论》第四版',        'price' => 89.00,   'image' => 'https://picsum.photos/seed/algobook/400/300',   'slug' => 'algorithm-book',             'stock' => 156, 'category_id' => 6, 'category_name' => '图书教育', 'description' => 'MIT 经典教材，新增机器学习章节，伪代码 + 习题解答，面试必备。', 'is_recommended' => true,  'is_hot' => false],
            ['id' => 33, 'name' => '专业跑步鞋减震透气',      'price' => 349.00,  'image' => 'https://picsum.photos/seed/running/400/300',     'slug' => 'running-shoes',              'stock' => 98,  'category_id' => 7, 'category_name' => '运动户外', 'description' => '全掌碳板 + 超临界发泡中底，回弹率85%，透气飞织鞋面，马拉松级。', 'is_recommended' => true,  'is_hot' => false],
            ['id' => 34, 'name' => '瑜伽垫加厚防滑 NBR',        'price' => 79.00,   'image' => 'https://picsum.photos/seed/yoga/400/300',       'slug' => 'yoga-mat',                   'stock' => 287, 'category_id' => 7, 'category_name' => '运动户外', 'description' => '10mm 加厚 NBR 材质，双面防滑纹理，含收纳绑带和背包，初学者适用。', 'is_recommended' => false, 'is_hot' => false],
            ['id' => 35, 'name' => '户外双人帐篷防风防雨',    'price' => 259.00,  'image' => 'https://picsum.photos/seed/tent/400/300',       'slug' => 'outdoor-tent',               'stock' => 63,  'category_id' => 7, 'category_name' => '运动户外', 'description' => '双层设计，防风防雨 PU2000+，3秒速开，2-3人空间，含防潮垫。', 'is_recommended' => false, 'is_hot' => true],
            ['id' => 36, 'name' => '运动水壶不锈钢 750ml',      'price' => 49.00,   'image' => 'https://picsum.photos/seed/bottle/400/300',     'slug' => 'sports-water-bottle',        'stock' => 423, 'category_id' => 7, 'category_name' => '运动户外', 'description' => '316 不锈钢内胆，真空保温12小时，750ml 大容量，BPA Free。', 'is_recommended' => false, 'is_hot' => false],
            ['id' => 37, 'name' => '氨基酸温和洁面乳',        'price' => 69.00,   'image' => 'https://picsum.photos/seed/cleanser/400/300',   'slug' => 'amino-acid-cleanser',        'stock' => 456, 'category_id' => 8, 'category_name' => '美妆个护', 'description' => '弱酸性氨基酸表活，泡沫细腻，洗后不紧绷，敏感肌适用。', 'is_recommended' => false, 'is_hot' => false],
            ['id' => 38, 'name' => '玻尿酸补水面膜 20片',      'price' => 89.00,   'image' => 'https://picsum.photos/seed/mask/400/300',       'slug' => 'hyaluronic-acid-mask',       'stock' => 589, 'category_id' => 8, 'category_name' => '美妆个护', 'description' => '三重玻尿酸 + 烟酰胺，天丝膜布超薄服帖，熬夜急救补水面膜。', 'is_recommended' => false, 'is_hot' => true],
            ['id' => 39, 'name' => '矿物防晒霜 SPF50+',         'price' => 129.00,  'image' => 'https://picsum.photos/seed/sunscreen/400/300',   'slug' => 'mineral-sunscreen',          'stock' => 234, 'category_id' => 8, 'category_name' => '美妆个护', 'description' => '物理防晒氧化锌配方，SPF50+ PA++++，清爽不泛白，敏感肌可用。', 'is_recommended' => false, 'is_hot' => false],
            ['id' => 40, 'name' => '便携电动牙刷旅行装',      'price' => 199.00,  'image' => 'https://picsum.photos/seed/toothbrush/400/300',  'slug' => 'electric-toothbrush',        'stock' => 178, 'category_id' => 8, 'category_name' => '美妆个护', 'description' => '磁悬浮声波马达，31000次/分钟，IPX7防水，Type-C充电，含旅行盒。', 'is_recommended' => true,  'is_hot' => false],
        ];
    }

    /**
     * 购物车页面
     * 获取购物车中所有商品详情、计算总价和总数量
     * 路由: GET /cart
     * 
     * @return \Illuminate\View\View 返回购物车页面
     */
    public function index()
    {
        // 从 Session 中获取购物车数据，格式为 ['商品ID' => '数量']
        $cartItems = session('cart', []);
        
        // 购物车商品详情数组 (包含商品信息、数量、小计)
        $cartWithProducts = [];
        // 购物车总金额
        $total = 0;
        // 购物车商品总数量
        $totalItems = 0;

        // 遍历购物车，匹配商品信息并计算金额
        foreach ($cartItems as $productId => $quantity) {
            foreach ($this->allProducts() as $product) {
                if ($product['id'] == $productId) {
                    // 组装购物车商品详情
                    $cartWithProducts[] = [
                        'product' => $product,
                        'quantity' => $quantity,
                        'subtotal' => $product['price'] * $quantity,
                    ];
                    // 累加总金额
                    $total += $product['price'] * $quantity;
                    // 累加商品数量
                    $totalItems += $quantity;
                    break;
                }
            }
        }

        // 返回购物车页面，传递商品列表、总金额和总数量
        return view('cart.index', compact('cartWithProducts', 'total', 'totalItems'));
    }

    /**
     * 添加商品到购物车
     * 验证登录状态和库存，将商品加入 Session 购物车
     * 路由: POST /cart/add
     * 
     * @param Request $request 请求对象，包含 product_id 和 quantity
     * @return \Illuminate\Http\RedirectResponse 重定向回购物车或原页面
     */
    public function add(Request $request)
    {
        // 检查用户是否登录，未登录则跳转到登录页
        if (!session('user_id')) {
            return redirect()->route('login')->with('error', '请先登录');
        }

        // 验证请求参数
        $validated = $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        // 提取验证后的参数
        $productId = $validated['product_id'];
        $quantity = $validated['quantity'];

        // 获取当前购物车数据
        $cart = session('cart', []);
        
        // 遍历商品列表，验证商品存在并检查库存
        foreach ($this->allProducts() as $product) {
            if ($product['id'] == $productId) {
                // 检查库存是否充足
                if ($product['stock'] < $quantity) {
                    return back()->with('error', '库存不足');
                }
                
                // 更新购物车数量 (如果已存在则累加，否则新增)
                if (isset($cart[$productId])) {
                    $cart[$productId] += $quantity;
                } else {
                    $cart[$productId] = $quantity;
                }
                
                // 将更新后的购物车保存到 Session
                session(['cart' => $cart]);
                // 重定向到购物车页面并显示成功提示
                return redirect()->route('cart.index')->with('success', '商品已加入购物车');
            }
        }

        // 如果商品不存在，返回原页面并显示错误提示
        return back()->with('error', '商品不存在');
    }

    /**
     * 更新购物车商品数量
     * 验证数量并检查库存，更新 Session 中的购物车数据
     * 路由: POST /cart/update/{productId}
     * 
     * @param Request $request 请求对象，包含 quantity
     * @param int $productId 商品ID
     * @return \Illuminate\Http\RedirectResponse 重定向回购物车页面
     */
    public function update(Request $request, $productId)
    {
        // 验证请求参数
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // 获取当前购物车数据
        $cart = session('cart', []);
        
        // 检查商品是否在购物车中
        if (!isset($cart[$productId])) {
            return back()->with('error', '商品不存在');
        }

        // 遍历商品列表，验证商品存在并检查库存
        foreach ($this->allProducts() as $product) {
            if ($product['id'] == $productId) {
                // 检查库存是否充足
                if ($product['stock'] < $validated['quantity']) {
                    return back()->with('error', '库存不足');
                }
                
                // 更新购物车中该商品的数量
                $cart[$productId] = $validated['quantity'];
                // 将更新后的购物车保存到 Session
                session(['cart' => $cart]);
                // 返回购物车页面并显示成功提示
                return back()->with('success', '数量已更新');
            }
        }

        // 如果商品不存在，返回原页面并显示错误提示
        return back()->with('error', '商品不存在');
    }

    /**
     * 从购物车中移除商品
     * 删除 Session 购物车中指定商品的数据
     * 路由: POST /cart/remove/{productId}
     * 
     * @param int $productId 商品ID
     * @return \Illuminate\Http\RedirectResponse 重定向回购物车页面
     */
    public function remove($productId)
    {
        // 获取当前购物车数据
        $cart = session('cart', []);
        
        // 检查商品是否在购物车中，如果存在则删除
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            // 将更新后的购物车保存到 Session
            session(['cart' => $cart]);
            // 返回购物车页面并显示成功提示
            return back()->with('success', '商品已移除');
        }

        // 如果商品不存在，返回原页面并显示错误提示
        return back()->with('error', '商品不存在');
    }

    /**
     * 清空购物车
     * 删除 Session 中所有购物车数据
     * 路由: POST /cart/clear
     * 
     * @return \Illuminate\Http\RedirectResponse 重定向回购物车页面
     */
    public function clear()
    {
        // 清除 Session 中的购物车数据
        session()->forget('cart');
        // 重定向到购物车页面并显示成功提示
        return redirect()->route('cart.index')->with('success', '购物车已清空');
    }
    /**
     * 结算页面
     * 获取购物车商品详情、计算总价，并返回结算页面
     * 路由: GET /cart/checkout
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse 返回结算页面或重定向
     */
    public function checkout()
    {
        // 检查用户是否登录
        if (!session('user_id')) {
            return redirect()->route('login')->with('error', '请先登录');
        }

        // 获取购物车数据，格式为 ['商品ID' => '数量']
        $cartItems = session('cart', []);
        
        // 如果购物车为空，重定向回购物车页面
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', '购物车为空');
        }

        // 购物车商品详情数组 (包含商品信息、数量、小计)
        $cartWithProducts = [];
        // 购物车总金额
        $total = 0;
        // 购物车商品总数量
        $totalItems = 0;

        // 遍历购物车，匹配商品信息并计算金额
        foreach ($cartItems as $productId => $quantity) {
            foreach ($this->allProducts() as $product) {
                if ($product['id'] == $productId) {
                    $cartWithProducts[] = [
                        'product' => $product,
                        'quantity' => $quantity,
                        'subtotal' => $product['price'] * $quantity,
                    ];
                    $total += $product['price'] * $quantity;
                    $totalItems += $quantity;
                    break;
                }
            }
        }

        // 获取用户地址列表（模拟数据）
        $addresses = [
            ['id' => 1, 'name' => '康刘勇', 'phone' => '15219209751', 'address' => '广东省广州市天河区珠吉街道100号', 'is_default' => true],
            ['id' => 2, 'name' => '爱丽丝', 'phone' => '16809801654', 'address' => '北京市东城区108街道', 'is_default' => false],
        ];

        // 返回结算页面，传递商品列表、总金额、总数量和地址列表
        return view('cart.checkout', compact('cartWithProducts', 'total', 'totalItems', 'addresses'));
    }
}