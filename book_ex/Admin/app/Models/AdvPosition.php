<?php
/*
 * @Author: your name
 * @Date: 2020-05-20 16:02:40
 * @LastEditTime: 2020-10-21 14:38:48
 * @LastEditors: your name
 * @Description: In User Settings Edit
 * @FilePath: \ShopX\app\Models\AdvPosition.php
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvPosition extends Model
{
    protected $table = "adv_position"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;

    public function adv(){
        return $this->hasMany("App\Models\Adv",'ap_id','id')->orderBy('adv_sort','asc');
    }
	public function store_adv(){
        return $this->hasMany("App\Models\StoreAdv",'ap_id','id');
    }

    public function get_adv_list($ap_name,$seller_id=0){

        return $this->where('ap_isuse',1)->where('ap_name',$ap_name)->with(
            ['adv'=>function($q){
                $q->where('adv_start','<=',time())->where('adv_end','>=',time())->orderBy('adv_sort','desc');
            },'store_adv'=>function($q) use ($seller_id){
               if($seller_id!=0){
                    $q->where('seller_id',$seller_id)->where('adv_start','<=',time())->where('adv_end','>=',time())->orderBy('adv_sort','desc');
               }
            }]
        )->first();
    }
}
