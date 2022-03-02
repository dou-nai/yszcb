<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adv extends Model
{
    protected $table = "adv"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;
    // 关联到广告位
    public function advPosition(){
    	return $this->hasOne('App\Models\advPosition','id','ap_id');
    }
}
