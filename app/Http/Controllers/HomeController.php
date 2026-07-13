<?php
/*
 * 首页控制器
 * 位置: app/Http/Controllers/HomeController.php
 * 控制器 (Controller) 是 MVC 中的 C 层, 负责:
 * 1. 接收用户请求 (浏览器访问某个 URL)
 * 2. 处理业务逻辑 (查询数据库、计算数据等)
 * 3. 返回响应 (HTML 页面、JSON 数据等)
 */

namespace App\Http\Controllers;

//【数据库模式】取消下面这行注释即可从数据库读取分类
// use App\Category;

class HomeController extends Controller
{
    /**
     * 获取分类数据 (与 CategoryController 共用同一数据源)
     * 当前返回写死数组 (零数据库依赖)。
     * 接入数据库后, 替换为:
     * return Category::where('status', 'active')->orderBy('sort_order')->get();
     * @return array
     */
    private function getCategories()
    {
        return [
            ['id' => 1, 'name' => '手机数码', 'slug' => 'phone-digital', 'icon' => 'bi-phone', 'sort_order' => 1],
            ['id' => 2, 'name' => '电脑办公', 'slug' => 'computer-office', 'icon' => 'bi-laptop', 'sort_order' => 2],
            ['id' => 3, 'name' => '服装鞋帽', 'slug' => 'clothing', 'icon' => 'bi-handbag', 'sort_order' => 3],
            ['id' => 4, 'name' => '家居生活', 'slug' => 'home-living', 'icon' => 'bi-house-door', 'sort_order' => 4],
            ['id' => 5, 'name' => '食品饮品', 'slug' => 'food-drink', 'icon' => 'bi-cup-straw', 'sort_order' => 5],
            ['id' => 6, 'name' => '图书教育', 'slug' => 'books', 'icon' => 'bi-book', 'sort_order' => 6],
            ['id' => 7, 'name' => '运动户外', 'slug' => 'sports', 'icon' => 'bi-bicycle', 'sort_order' => 7],
            ['id' => 8, 'name' => '美妆个护', 'slug' => 'beauty', 'icon' => 'bi-heart', 'sort_order' => 8],
        ];

        //【数据库模式】(接入数据库后取消注释, 并注释掉上面的 return)
        // return Category::where('status', 'active')
        //     ->orderBy('sort_order')
        //     ->get();
    }

    /**
     * 首页显示方法
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // 网站基本信息
        $data = [
            'title' => 'Laravel 12 教学项目',
            'description' => '这是一个用于教学的 Laravel 12 简单首页',
        ];

        // 轮播图数据 (实际项目应从数据库 banners 表查询)
        $slides = [
            [
                'image' => 'https://picsum.photos/seed/php/800/400',
                'title' => 'PHP 入门基础',
                'desc' => '从变量、数组到面向对象，系统学习 PHP 编程语言。',
            ],
            [
                'image' => 'https://picsum.photos/seed/laravel/800/400',
                'title' => 'Laravel 框架实战',
                'desc' => '掌握路由、控制器、Blade 模板，快速构建 Web 应用。',
            ],
            [
                'image' => 'https://picsum.photos/seed/mysql/800/400',
                'title' => '数据库与 Eloquent ORM',
                'desc' => '使用迁移管理表结构，Eloquent 优雅操作数据库。',
            ],
        ];

        // 分类数据 (调用统一数据源方法)
        $categories = $this->getCategories();

        // 推荐商品 (模拟 Product::recommended() 查询结果)
        // 接入数据库应使用: Product::active()->recommended()->latest()->take(8)->get()
        $recommendedProducts = [
            ['name' => '机械键盘 RGB 青轴', 'image' => 'https://picsum.photos/seed/keyboard/400/300', 'price' => 299.00, 'slug' => 'mechanical-keyboard', 'stock' => 156],
            ['name' => '蓝牙耳机降噪 Pro', 'image' => 'https://picsum.photos/seed/headphone/400/300', 'price' => 599.00, 'slug' => 'noise-cancelling-earphone', 'stock' => 89],
            ['name' => '27寸 4K 显示器', 'image' => 'https://picsum.photos/seed/monitor/400/300', 'price' => 2199.00, 'slug' => '4k-monitor', 'stock' => 341],
            ['name' => '无线鼠标静音款', 'image' => 'https://picsum.photos/seed/mouse/400/300', 'price' => 89.00, 'slug' => 'wireless-mouse', 'stock' => 423],
            ['name' => 'Type-C 扩展坞 7合1', 'image' => 'https://picsum.photos/seed/dock/400/300', 'price' => 159.00, 'slug' => 'type-c-dock', 'stock' => 201],
            ['name' => '人体工学电脑椅', 'image' => 'https://picsum.photos/seed/chair/400/300', 'price' => 899.00, 'slug' => 'ergonomic-chair', 'stock' => 512],
            ['name' => 'USB-C 快充数据线', 'image' => 'https://picsum.photos/seed/cable/400/300', 'price' => 29.90, 'slug' => 'usb-c-cable', 'stock' => 7801],
            ['name' => '高清网络摄像头', 'image' => 'https://picsum.photos/seed/webcam/400/300', 'price' => 199.00, 'slug' => 'webcam-hd', 'stock' => 671],
        ];

        // 热销爆款商品 (模拟 Product::hot() 查询结果)
        // 数据库接入应使用: Product::active()->hot()->latest()->take(8)->get()
        $hotProducts = [
            ['name' => '无线蓝牙音箱', 'image' => 'https://picsum.photos/seed/speaker/400/300', 'price' => 129.00, 'slug' => 'bluetooth-speaker', 'stock' => 42],
            ['name' => '手机散热背夹', 'image' => 'https://picsum.photos/seed/cooler/400/300', 'price' => 49.00, 'slug' => 'phone-cooler', 'stock' => 18],
            ['name' => '大容量充电宝 20000mAh', 'image' => 'https://picsum.photos/seed/powerbank/400/300', 'price' => 139.00, 'slug' => 'power-bank', 'stock' => 97],
            ['name' => '桌面 LED 护眼灯', 'image' => 'https://picsum.photos/seed/lamp/400/300', 'price' => 79.00, 'slug' => 'led-desk-lamp', 'stock' => 234],
            ['name' => '人体工学办公凳', 'image' => 'https://picsum.photos/seed/stool/400/300', 'price' => 899.00, 'slug' => 'ergonomic-stool', 'stock' => 11],
            ['name' => '移动固态硬盘 1TB', 'image' => 'https://picsum.photos/seed/ssd/400/300', 'price' => 499.00, 'slug' => 'portable-ssd', 'stock' => 56],
            ['name' => '智能手环运动版', 'image' => 'https://picsum.photos/seed/band/400/300', 'price' => 199.00, 'slug' => 'smart-band', 'stock' => 143],
            ['name' => '降噪麦克风套装', 'image' => 'https://picsum.photos/seed/mic/400/300', 'price' => 259.00, 'slug' => 'mic-kit', 'stock' => 28],
        ];

        // 示例文章列表
        $articles = [
            [
                'title' => 'Laravel 入门指南',
                'summary' => '学习 Laravel 框架的基本概念: 路由、控制器、模型的基础用法。',
                'date' => '2025-06-01',
            ],
            [
                'title' => 'MVC 架构详解',
                'summary' => '深入理解 Model (模型)、View (视图)、Controller (控制器) 三层架构如何协同工作。',
                'date' => '2025-06-05',
            ],
            [
                'title' => '数据库迁移入门',
                'summary' => '使用 Migration (迁移) 来管理数据库表结构，告别手动建表的繁琐。',
                'date' => '2025-06-10',
            ],
        ];

        // 把八个变量打包传给视图 home.index
        return view('home.index', compact('data', 'slides', 'categories', 'recommendedProducts', 'hotProducts', 'articles'));
    }
}