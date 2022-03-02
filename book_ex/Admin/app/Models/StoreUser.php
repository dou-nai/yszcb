<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class StoreUser extends Model
{
    protected $table = "Store_User"; //指定表
    protected $primaryKey = "id"; //指定id字段
    protected $fillable =['store_id','user_id'];
    public $timestamps = false;

    public function user()
    {
        return $this->hasOne(Users::class,"id","user_id");
    }

    public function store()
    {
        return $this->hasOne(Store::class,"id","store_id");
    }
}
