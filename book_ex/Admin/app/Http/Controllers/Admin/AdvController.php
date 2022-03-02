<?php
/*
 * @Author: your name
 * @Date: 2020-05-20 16:02:40
 * @LastEditTime: 2020-10-21 21:38:55
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \ShopX\app\Http\Controllers\Admin\AdvController.php
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Adv;
use App\Models\AdvPosition;
use App\Tools\Uploads;
use Hamcrest\Arrays\IsArray;
use App\Models\Store;
use App\Models\Goods;
use App\Models\Seckill;
class AdvController extends BaseController
{
    public function index(Request $req,Adv $adv_model){
        $adv_model = $adv_model->with('AdvPosition')->orderBy('adv_sort','asc');
        if(!empty($req->adv_position_id)){
            $adv_model->where('ap_id',$req->adv_position_id);
        }
        $list = $adv_model->paginate(20);
        return $this->success_msg('Success',$list);
    }

    public function add(Request $req,Adv $adv_model,AdvPosition $adv_position_model,Store $store_model,Goods $goods_model,Seckill $seckill_model){
        if(!$req->isMethod('post')){
            $list = $adv_position_model->get();
            // $list['store_list'] =$store_model->where('store_status',1)->orderBy('id','desc')->take(150)->get();
            // $list['goods_list'] =$goods_model->where('goods_status',1)->orderBy('is_index','desc')->orderBy('id','desc')->take(150)->get();
            // $list['seckill_list'] =$seckill_model->where('enable',1)->orderBy('is_index','desc')->orderBy('id','desc')->take(150)->get();

    		return $this->success_msg('Success',$list);
        }

    	$data = [
    		'adv_title' => $req->adv_title,
    		'ap_id' => $req->ap_id,
    		'adv_link' => $req->adv_link??'',
    		'adv_image' => is_array($req->adv_image)?$req->adv_image[0]:$req->adv_image,
    		'adv_sort' => intval($req->adv_sort),
            'adv_type' => intval($req->adv_type),
            'adv_start'=> isset($req->adv_start)?strtotime($req->adv_start):time(),
            'adv_end'=>  isset($req->adv_end)?strtotime($req->adv_end):time(),
            'adv_content' =>  empty($req->adv_content)?'':$req->adv_content,  // 广告内容
        ];


    	$adv_model->insert($data);
    	return $this->success_msg();
    }

    public function edit(Request $req,Adv $adv_model,AdvPosition $adv_position_model,Store $store_model,Goods $goods_model,Seckill $seckill_model,$id){
        if(!$req->isMethod('post')){
            $info = $adv_model->find($id)->toArray();
            // $info['adv_date'] = [];
            // $info['adv_date'][0] = date('Y-m-d H:m',$info['adv_start']);
            // $info['adv_date'][1] = date('Y-m-d H:m',$info['adv_end']);

            $info['adv_start'] = date('Y-m-d H:m',$info['adv_start']);
            $info['adv_end'] = date('Y-m-d H:m',$info['adv_end']);

            $info['list'] = $adv_position_model->get();
    		return $this->success_msg('Success',$info);
    	}

    	$data = [
    		'adv_title' => $req->adv_title,
    		'ap_id' => $req->ap_id,
            'adv_link' => $req->adv_link??'',
            'adv_image' =>is_array($req->adv_image)?$req->adv_image[0]:$req->adv_image,
    		'adv_sort' => intval($req->adv_sort),
            'adv_type' => intval($req->adv_type),
            'adv_start'=> isset($req->adv_start)?strtotime($req->adv_start):time(),
            'adv_end'=>  isset($req->adv_end)?strtotime($req->adv_end):time(),
            'adv_content' =>  empty($req->adv_content)?'':$req->adv_content,  // 广告内容
    	];

    	$adv_model->where('id',$id)->update($data);
    	return $this->success_msg();
    }

    public function del(Request $req,Adv $adv_model){
        $id = $req->id;
        $ids = explode(',',$id);
        $adv_model->destroy($ids);
        return $this->success_msg();
    }

    // 广告上传
    public function adv_upload(){
        $uploads = new Uploads;
        $rs = $uploads->adv_upload(['filepath'=>'adv/']);
        if($rs['status']){
            return $this->success_msg('Success',$rs['path']);
        }else{
            return $this->error_msg($rs['msg']);
        }
    }
}
