<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\StoreAdv;
use App\Models\AdvPosition;
use App\Tools\Uploads;

class AdvController extends BaseController
{
    public function index(Request $req,StoreAdv $adv_model){
        $adv_model = $adv_model->orderBy('adv_sort','asc');
        if(!empty($req->adv_position_id)){
            $adv_model->where('ap_id',$req->adv_position_id);
        }

        $user_info = auth()->user();

        $list = $adv_model->where('seller_id',$user_info['id'])->paginate(20);
        return $this->success_msg('Success',$list);
    }

    public function add(Request $req,StoreAdv $adv_model,AdvPosition $adv_position_model){
        if(!$req->isMethod('post')){
            $list = $adv_position_model->get();
    		return $this->success_msg('Success',$list);
        }
        $user_info = auth()->user();
    	$data = [
    		'adv_title' => $req->adv_title,
    		'ap_id' => $req->ap_id,
    		'adv_link' => $req->adv_link??'',
    		'adv_image' => $req->adv_image,
    		'adv_sort' => intval($req->adv_sort),
            'adv_type' => intval($req->adv_type),
            'adv_start'=> strtotime($req->adv_date[0]),
            'adv_end'=> strtotime($req->adv_date[1]),
            'seller_id'=>$user_info['id']
    	];

    	$adv_model->insert($data);
    	return $this->success_msg();
    }

    public function edit(Request $req,StoreAdv $adv_model,AdvPosition $adv_position_model,$id){
        if(!$req->isMethod('post')){
            $info = $adv_model->find($id)->toArray();
            $info['adv_date'] = [];
            $info['adv_date'][0] = date('Y-m-d H:m',$info['adv_start']);
            $info['adv_date'][1] = date('Y-m-d H:m',$info['adv_end']);
            $info['list'] = $adv_position_model->get();
    		return $this->success_msg('Success',$info);
    	}

    	$data = [
    		'adv_title' => $req->adv_title,
    		'ap_id' => $req->ap_id,
    		'adv_link' => $req->adv_link??'',
    		'adv_image' => $req->adv_image,
    		'adv_sort' => intval($req->adv_sort),
            'adv_type' => intval($req->adv_type),
            'adv_start'=> strtotime($req->adv_date[0]),
            'adv_end'=> strtotime($req->adv_date[1]),
    	];

    	$adv_model->where('id',$id)->update($data);
    	return $this->success_msg();
    }

    public function del(Request $req,StoreAdv $adv_model){
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
