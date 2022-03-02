<?php

namespace App\Http\Controllers\Admin;

use App\Models\StoreUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Users;
use App\Models\Roles;
use App\Models\Menus;
use App\Models\MoneyLog;
use App\Models\GoodsClass;
class UsersController extends BaseController
{

	public function index(Request $req,Users $users_model,GoodsClass $goods_class_model){
        $list = $users_model->orderBy('id','desc')->with('roles')->paginate(20)->toArray();

        foreach($list['data'] as $k=>&$v){
            $cate_tag=explode(',',$v['like_cate_ids']);
            if(!empty($cate_tag)){
                $v['cate_tag'] = $goods_class_model->whereIn('id',$cate_tag)->pluck('name');
            }else{
                $v['cate_tag']=[];
            }
        }
        unset($v);
		return $this->success_msg('Success',$list);
	}

	public function add(Request $req,Users $users_model,Roles $roles_model){
		if(!$req->isMethod('post')){
			$roles_list = $roles_model->get();
			$data['roles_list'] = $roles_list;
			return $this->success_msg('Success',$data);
		}

		$data['username'] = $req->username;
        $data['phone'] = $req->phone;
		$data['password'] = Hash::make($req->password);
		$data['add_time'] = time();
		$data['nickname']=$req->nickname;
		$users = $users_model->create($data);
		$users->roles()->attach($req->get("role_id"));
        if($req->exists("store_id"))
        {
            $su=StoreUser::firstOrCreate(["store_id"=>$req->get("store_id"),"user_id"=>$users->id]);
            //dd($su);
        }

		return $this->success_msg("ok",$users);
	}

	public function edit(Request $req,Users $users_model,Roles $roles_model,$id){
		if(!$req->isMethod('post')){
			$data['info'] = $users_model->with('roles')->find($id);

			$roles_list = $roles_model->get();
			$data['roles_list'] = $roles_list;
			if(count($data['info']->roles)>0) {
                if ($data['info']->roles[0]->name == "商家") {
                    $su = StoreUser::where("user_id", $data['info']->id)->first();
                    $data['info']->store_id = $su->store_id;
                }
            }
			return $this->success_msg('Success',$data);
		}

		if(!empty($req->password)){
			$save_data['password'] = Hash::make($req->password);
		}

		if(!empty($req->username)){
			$save_data['username'] = $req->username;
		}
        if(!empty($req->nickname)){
            $save_data['nickname'] = $req->nickname;
        }
        if(!empty($req->phone)){
            $save_data['phone'] = $req->phone;
        }

		if(!empty($req->avatar)){
			$save_data['avatar'] = $req->avatar;
		}

		if(!empty($req->email)){
			$save_data['email'] = $req->email;
		}

		$users = $users_model->find($id);

		if(!empty($req->roles_list)){
			$users->roles()->sync($req->roles_list);
		}else{
			$users->roles()->sync([]);
		}

		if(!empty($save_data)){
			$users_model->where('id',$id)->update($save_data);

            if($req->exists("store_id"))
            {
                $su=StoreUser::firstOrCreate(["store_id"=>$req->get("store_id"),"user_id"=>$users->id]);
                //dd($su);
            }

		}

		return $this->success_msg("ok",$users);


	}

	public function del(Request $req,Users $users_model){
		$id = $req->id;
		$ids = explode(',',$id);
		if(in_array('1',$ids)){
			return $this->error_msg('超级管理员不能删除.');
		}
		$users = $users_model->whereIn('id',$ids)->get();
		foreach($users as $v){
			$v->roles()->sync([]);
		}
        $users_model->destroy($ids);
        return $this->success_msg();
	}

	// 获取用户的权限栏目\
	public function get_permission_menus(Request $req,Users $users_model,Roles $roles_model,Menus $menus_model){
		$user_info = auth()->user();
		$user_info = $users_model->find($user_info['id']);

		// 获取角色ID
		$roles_id = [];
		foreach($user_info->roles()->get() as $v){
			$roles_id[] = $v['id'];
		}

		// 获取栏目ID
		$roles_info = $roles_model->whereIn('id',$roles_id)->get();
		$menus_id = [];
		foreach($roles_info as $v){
			foreach($v->menus as $vo){
				if(!in_array($vo->id,$menus_id)){
					$menus_id[] = $vo->id;
				}
			}

		}

		$menus_model = $menus_model->whereIn('id',$menus_id)->orderBy('is_sort','asc');
		if(empty($req->is_type)){
			$menus_list = $menus_model->where('is_type',0)->get();
		}else{
			$menus_list = $menus_model->where('is_type',1)->get();
		}

		// 去查询列表并且生成树状结构
		$menus_list = $menus_model->whereIn('id',$menus_id)->get();
		$new = getChild($menus_list);
		return $this->success_msg('ok',$new);
	}

    public function get_user_info(){
    	$user_info = auth()->user();
    	return $this->success_msg('ok',$user_info);
    }

    // 改变用户资金
    public function change_money(Request $req,Users $user_model,MoneyLog $money_log_model){
        $user_id = $req->user_id;
        $change_type = $req->change_type;
        $type = $req->type;
        $money = $req->money;

        $admin_info = auth()->user();

        // 随机数生成  订单号
        $make_rand = make_rand();

        $user_info = $user_model->find($user_id);

        switch($change_type){
            case 0:
                if(empty($type)){
                    if($user_info['money']>=$money){
                        $user_model->money_change('系统操作','money',-$money,$user_info,$admin_info['nickname'].'执行了该操作','-');
                    }else{
                        return $this->error_msg('余额不够');
                    }

                }else{
                    $user_model->money_change('系统操作','money',$money,$user_info,$admin_info['nickname'].'执行了该操作','-');
                }
            break;
            case 1:
                if(empty($type)){
                    if($user_info['integral']>=$money){
                    $log_data['money'] = $money;
                        $user_model->money_change('系统操作','integral',-$money,$user_info,$admin_info['nickname'].'执行了该操作','-');
                    }else{
                        return $this->error_msg('积分不够');
                    }

                }else{
                    $user_model->money_change('系统操作','integral',$money,$user_info,$admin_info['nickname'].'执行了该操作','-');
                }
            break;
            case 2:
                if(empty($type)){
                    if($user_info['frozen_money']>=$money){
                        $user_model->money_change('系统操作','frozen_money',-$money,$user_info,$admin_info['nickname'].'执行了该操作','-');
                    }else{
                        return $this->error_msg('冻结资金不够');
                    }

                }else{
                    $user_model->money_change('系统操作','frozen_money',$money,$user_info,$admin_info['nickname'].'执行了该操作','-');
                }
            break;

        }

        return $this->success_msg('ok');

    }
}
