<?php
// Category 模型
// 位置:app/Category.php


// 命名空间
namespace App;

// 继承与Laravel的 Model 基类，从而获取所有数据库操作方法
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // 设置一个填充字段白名单，方便后面数据库建表
    protected $fillable = ['name', 'slug', 'icon', 'sort_order', 'status'];
}
