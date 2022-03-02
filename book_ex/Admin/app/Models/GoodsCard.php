<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsCard extends Model
{
    protected $table = "goods_card"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;

    public function Goods()
    {
        return $this->hasOne(Goods::class,"id","goods_id");
    }
    public function user(){
        return $this->hasOne(Users::class,"id","user_id");
    }

    public function Order()
    {
        return $this->hasOne(Order::class,"id","order_id");
    }
}
