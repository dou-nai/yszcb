<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Roles;
use App\Models\Users;
use App\Models\SeckillGoods;
class StoreController extends BaseController
{
    public function index(Request $req,Store $store_model,SeckillGoods $seckill_goods_model){

        $store_verify = 1; // 默认取审核通过的出版社
        $where = [
            'store_verify' => $store_verify,
        ];

        if(isset($req->store_verify)){
            $where['store_verify'] = $req->store_verify;
        }

        if(isset($req->store_status)){
            $where['store_status'] = $req->store_status;
        }

        if(isset($req->store_name) && !empty($req->store_name)){
            $store_model = $store_model->where('store_name','like','%'.$req->store_name.'%');
        }

        if(isset($req->seckill_id)){

            $sid=$req->seckill_id;
            $store_model = $store_model->whereNotIn('id', function ($query) use($sid){
                $query->select('goods_id')->from('seckill_goods')->where('sid', $sid);
             }) ;
        }

        $list = $store_model->where($where)->orderBy('id','desc')->paginate($req->get("page_size")??20);

		return $this->success_msg('Success',$list);
	}
    public function add(Request $req,Store $store_model){
        if(!$req->isMethod('post')){
    		return $this->success_msg('Success',[]);
    	}

        $data = [
            'store_name'            =>  $req->store_name,
            'store_description'     =>  $req->store_description,
            'store_logo'            =>  $req->store_logo,
            'store_mobile'          =>  $req->store_mobile,
            'store_address'         =>  $req->store_address,
            'company_web'=>  $req->company_web,
            'hall_address'=>  $req->hall_address,
            'store_verify'=>1,
            'store_status'=>$req->store_status,
            'store_company_name'=>$req->store_company_name
        ];

    	$store_model->insert($data);
    	return $this->success_msg();
    }

    public function edit(Request $req,Store $store_model,$id){
        if(!$req->isMethod('post')){
            $info = $store_model->find($id);
    		return $this->success_msg('Success',$info);
    	}

        $data = [
            'store_name'            =>  $req->store_name,
            'store_description'     =>  $req->store_description,
            'store_logo'            =>  $req->store_logo,
            'store_mobile'          =>  $req->store_mobile,
            'store_address'         =>  $req->store_address,
            'company_web'=>  $req->company_web,
            'hall_address'=>  $req->hall_address,
            'store_verify'=>1,
            'store_status'=>$req->store_status,
            'store_company_name'=>$req->store_company_name
        ];
        $store_model->where('id',$id)->update($data);
    	return $this->success_msg();
    }

	public function del(Request $req,Store $store_model){
		$id = $req->id;
		$ids = explode(',',$id);
        $store_model->destroy($ids);
        return $this->success_msg();
    }

    // 修改出版社信息  审核之类的
    public function store_pass(Request $req,Store $store_model,Roles $roles_model,Users $users_model){
        $info = $req->only(['store_status','store_close_info','id']);
        if($info['store_status']==0){
            $info['store_status']=1;
        }else{
            $info['store_status']=0;
        }
        $store_model->where('id',$info['id'])->update($info);

        // 增加权限
        //$store_info = $store_model->find($info['id']);
        // $users = $users_model->find($store_info['user_id']);

        // if($store_info['user_id'] != 1){
        //     if($info['store_status'] == 1 && $info['store_verify'] == 1){
        //         $users->roles()->sync([30]);
        //     }else{
        //         $users->roles()->sync([]);
        //     }
        // }

        return $this->success_msg('Success',[]);
    }
}
