<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VipPhone extends Model
{
    protected $table = "vip_phone"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;
}
