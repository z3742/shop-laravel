<?php
/*
 * 分类控制器
 * 位置: app/Http/Controllers/CategoryController.php
 * 负责处理商品分类相关的所有请求: 分类列表、分类详情
 * 所有分类数据当前为写死数组 (零数据库依赖)，便于教学演示
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * 获取所有分类数据 (数据源方法)
     * 当前返回写死数组 (零数据库依赖)。
     * 接入数据库后, 应创建 Category 模型并替换为:
     * return Category::where('status', 'active')->orderBy('sort_order')->get();
     * 
     * 分类数据结构说明:
     * - id: 分类唯一标识
     * - name: 分类名称
     * - slug: 分类别名(用于URL)
     * - icon: 分类图标(使用 Bootstrap Icons)
     * - sort_order: 排序序号
     * 
     * @return array 分类数组列表
     */
    private function getCategories(){
        return [
            ['id' => 1, 'name' => '手机数码',   'slug' => 'phone-digital',   'icon' => 'bi-phone',      'sort_order' => 1],
            ['id' => 2, 'name' => '电脑办公',   'slug' => 'computer-office', 'icon' => 'bi-laptop',     'sort_order' => 2],
            ['id' => 3, 'name' => '服装鞋帽',   'slug' => 'clothing',        'icon' => 'bi-handbag',    'sort_order' => 3],
            ['id' => 4, 'name' => '家居生活',   'slug' => 'home-living',     'icon' => 'bi-house-door', 'sort_order' => 4],
            ['id' => 5, 'name' => '食品饮料',   'slug' => 'food-drink',      'icon' => 'bi-cup-hot',    'sort_order' => 5],
            ['id' => 6, 'name' => '图书教育',   'slug' => 'books',           'icon' => 'bi-book',       'sort_order' => 6],
            ['id' => 7, 'name' => '运动户外',   'slug' => 'sports',          'icon' => 'bi-bicycle',    'sort_order' => 7],
            ['id' => 8, 'name' => '美妆个护',   'slug' => 'beauty',          'icon' => 'bi-heart',      'sort_order' => 8],
        ];
    }

    /**
     * 分类列表页面
     * 获取所有分类并传递给视图展示
     * 路由: GET /categories
     * 
     * @return \Illuminate\View\View 返回分类列表页面
     */
    public function index(){
        // 调用获取分类数据的函数，获取分类列表
        $categories = $this->getCategories();

        // 传递分类数据到视图 categories.index (resources/views/categories/index.blade.php)
        return view('categories.index', compact('categories'));
    }

    /**
     * 分类详情页面
     * 通过分类别名(slug)查找分类并展示详情
     * 路由: GET /categories/{slug}
     * 
     * @param string $slug 分类别名(URL友好的标识符)
     * @return \Illuminate\View\View 返回分类详情页面
     */
    public function show($slug){
        // 获取所有分类数据
        $categories = $this->getCategories();
        
        // 使用 array_filter 过滤出匹配的分类
        $filter = array_filter($categories, function($cat) use ($slug){
            return $cat['slug'] == $slug;
        });
        
        // reset() 获取数组第一个元素 (过滤结果应该只有一个)
        $category = reset($filter);
        
        // 检查分类是否存在，如果不存在返回404错误
        if (!$category) {
            abort(404, '分类不存在');
        }
        
        // 传递分类数据到视图 categories.show (resources/views/categories/show.blade.php)
        return view('categories.show', compact('category'));
    }
}