<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderGoods extends Model
{
    protected $table = "order_goods"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;

    public function Goods()
    {
        return $this->hasOne('App\Models\Goods','id','goods_id');
    }

    public function GoodsCard()
    {
        return $this->hasOne('App\Models\GoodsCard','order_id','order_id');
    }
}
