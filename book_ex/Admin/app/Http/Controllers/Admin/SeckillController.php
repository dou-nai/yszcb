<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Seckill;
use App\Models\SeckillGoods;
use App\Models\GoodsBrand;
class SeckillController extends BaseController
{
    public function index(Request $req,Seckill $seckill_model){
        $seckill_model = $seckill_model->orderBy('id','desc');
        $list = $seckill_model->paginate(20);
        return $this->success_msg('Success',$list);
    }
    public function seckill_is_index(Request $req,Seckill $seckill_mode)
    {
        $seckill_mode->where("id","<>",$req->id)->update(["is_index"=>0]);
        $seckill_mode->where("id",$req->id)->update(["is_index"=>$req->is_index]);
        return $this->success_msg();
    }
    public function seckill_enable(Request $req,Seckill $seckill_mode)
    {

        //$seckill_mode->where("id","<>",$req->id)->update(["enable"=>0]);
        $i=$seckill_mode->where("id",$req->id)->update(["enable"=>intval($req->enable)]);
        return $this->success_msg(['i'=>$req->id]);
    }
    public function add(Request $req,Seckill $seckill_model,GoodsBrand $goods_brand_model){
        if(!$req->isMethod('post')){
            $data['brands_list'] = $goods_brand_model->orderBy('is_sort','desc')->get();
    		return $this->success_msg('Success',$data);
        }
        $goods_images = implode(',',$req->file_list);                           // 商品图片
    	$data = [
            'name' => $req->name,
            'hall' => $req->hall,
            'guest' => $req->guest,
            'sponsor' => $req->sponsor,
            'start_time'=> isset($req->start_time)?strtotime($req->start_time):time(),
            'end_time'=> isset($req->end_time)?strtotime($req->end_time):time(),
            'goods_images'          =>  $goods_images,                          // 商品图片
            'goods_master_image'=> $req->goods_master_image,
            'content'=> empty($req->content)?'':$req->content,
            'enable'=> $req->enable,
            'brands'=>empty($req->brands)?'':implode(',',$req->brands)
    	];

    	$seckill_model->insert($data);
    	return $this->success_msg();
    }

    public function edit(Request $req,Seckill $seckill_model,$id,GoodsBrand $goods_brand_model){
        if(!$req->isMethod('post')){
            $info = $seckill_model->find($id)->toArray();

            $info['start_time'] = date('Y-m-d H:m',$info['start_time']);
            $info['end_time'] = date('Y-m-d H:m',$info['end_time']);
            $info['brands_list'] = $goods_brand_model->get();
            $brands=explode(',',$info['brands']);
            $info['brands'] = $goods_brand_model->whereIn('id',$brands)->orderBy('is_sort','desc')->get();

    		return $this->success_msg('Success',$info);
    	}
        $goods_images = implode(',',$req->file_list);                           // 商品图片
    	$data = [
            'name' => $req->name,
            'hall' => $req->hall,
            'guest' => $req->guest,
            'sponsor' => $req->sponsor,
            'start_time'=> isset($req->start_time)?strtotime($req->start_time):time(),
            'end_time'=> isset($req->end_time)?strtotime($req->end_time):time(),
            'goods_images'          =>  $goods_images,                          // 商品图片
            'goods_master_image'=> $req->goods_master_image,
            'content'=> empty($req->content)?'':$req->content,
            'enable'=> $req->enable,
            'brands'=>empty($req->brands)?'':implode(',',$req->brands)
    	];

    	$seckill_model->where('id',$id)->update($data);
    	return $this->success_msg();
    }

    public function del(Request $req,Seckill $seckill_model){
        $id = $req->id;
        $ids = explode(',',$id);
        $seckill_model->destroy($ids);
        return $this->success_msg();
    }

    public function del_seckill_goods(Request $req,SeckillGoods $seckill_goods_model){
        $id = $req->id;
        $ids = explode(',',$id);
        $seckill_goods_model->destroy($ids);
        return $this->success_msg();
    }

    // 获取参加的商品
    public function get_add_seckill_goods(Request $req,SeckillGoods $seckill_goods_model){
        $sid = $req->sid;
        $goods_list = $seckill_goods_model->has('goods')->with('goods')->where('sid',$sid)->paginate(20);
        return $this->success_msg('Success',$goods_list);
    }

    // 审核通过
    public function change_status(Request $req,SeckillGoods $seckill_goods_model){
        $id = $req->id;
        $info = $seckill_goods_model->find($id);
        if($info['status'] == 0){
            $seckill_goods_model->where('id',$id)->update(['status'=>1]);
        }else{
            $seckill_goods_model->where('id',$id)->update(['status'=>0]);
        }
        return $this->success_msg('Success');
    }
    // 添加一个商品到商品秒杀
    public function add_seckill_goods(Request $req,SeckillGoods $seckill_goods_model){
        $id = $req->id;
        $seckill_id = $req->seckill_id??0;
        $user_info = auth()->user();
        $ids = explode(',',$id);
        $seckill_goods_list = $seckill_goods_model->where(['sid'=>$seckill_id,'user_id'=>$user_info['id']])->get()->toArray();

        $seckill_ids = [];
        foreach($seckill_goods_list as $v){
            $seckill_ids[] = $v['goods_id'];
        }

        foreach($ids as $v){
            if(in_array($v,$seckill_ids)){
                return $this->error_msg('已经加入!');
            }
        }

        $insertData = [];
        foreach($ids as $v){
            $insertData[] = ['sid'=>$seckill_id,'goods_id'=>$v,'user_id'=>$user_info['id'],'status'=>1];
        }
        $seckill_goods_model->insert($insertData);

        return $this->success_msg();
    }
}
