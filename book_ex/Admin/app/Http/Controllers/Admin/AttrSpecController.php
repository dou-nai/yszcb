<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\AttrSpec;

class AttrSpecController extends BaseController
{
    public function index(AttrSpec $attr_spec_model){
        $user_info = auth()->user();
        $list = $attr_spec_model->where('user_id',$user_info['id'])->orderBy('is_hot','desc')->orderBy('id','desc')->paginate(20);
        return $this->success_msg('Success',$list);
    }

    public function add(Request $req,AttrSpec $attr_spec_model){
        if(!$req->isMethod('post')){
    		return $this->success_msg('Success',[]);
    	}
        $user_info = auth()->user();
    	$data = [
    		'user_id' => $user_info['id'],
    		'spec_name' => $req->spec_name,
            'attr_name' => '后台设置',
            'is_hot'=> $req->is_hot,
    	];

    	$attr_spec_model->insert($data);
    	return $this->success_msg();
    }

    public function edit(Request $req,AttrSpec $attr_spec_model,$id){
        if(!$req->isMethod('post')){
            $info = $attr_spec_model->find($id);
    		return $this->success_msg('Success',$info);
    	}

    	$data = [
    		'spec_name' => $req->spec_name,
            //'attr_name' => $req->attr_name,
            'is_hot'=> $req->is_hot,
    	];

    	$attr_spec_model->where('id',$id)->update($data);
    	return $this->success_msg();
    }

    public function del(Request $req,AttrSpec $attr_spec_model){
        $id = $req->id;
        $ids = explode(',',$id);
        $attr_spec_model->destroy($ids);
        return $this->success_msg();
    }

      // 指定热门
      public function hot_status(Request $req,AttrSpec $attr_spec_model){
        $id = $req->id;
        $attr_spec_info = $attr_spec_model->find($id);
        $attr_spec_status = $attr_spec_info['is_hot']==1?0:1;
        $attr_spec_model->where('id',$id)->update(['is_hot'=>$attr_spec_status]);
        return $this->success_msg();
    }
}
