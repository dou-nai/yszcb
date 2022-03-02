<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Shopping;
use App\Tools\Helper;
class ShoppingController extends NotokenController
{
    // 获取购物商品列表
    public function get_cart_list(Shopping $shopping_model){
        //$user_info = auth()->user();
        $cart_list = $shopping_model->where('user_id',0)->get()->toArray();
        $list = [];
        foreach($cart_list as $v){
            $list[$v['store_id']][] = $v;
        }
        $list = array_merge($list);
        return $this->success_msg('ok',$list);
    }

    // 获取购物商品数量
    public function get_cart_count(Shopping $shopping_model){
        //$user_info = auth()->user();
        $count = $shopping_model->where('user_id',0)->count();
        return $this->success_msg('ok',$count);
    }

    // 加入购物商品
    public function add_cart(Request $req,Shopping $shopping_model){
        //$user_info = auth()->user();
        $data = [
            'user_id'           =>  0,
            'spec_id'           =>  $req->spec_id??0,
            'goods_id'          =>  $req->id,
            'seller_id'         =>  $req->user_id,
            'store_id'          =>  $req->store_info['id'],
            'store_name'        =>  $req->store_info['store_name'],
            'goods_name'        =>  $req->goods_name,
            'image'             =>  get_format_image($req->goods_master_image,200),
            'goods_price'       =>  floatval($req->goods_price),
            'goods_num'         =>  intval($req->buy_num),
            'goods_spec'        =>  $req->goods_spec_name??'-',
        ];
        $rs = $shopping_model->add_cart($data);

        if($rs['status']){
            return $this->success_msg($rs['msg'],$rs['status']);
        }else{
            return $this->error_msg($rs['msg'],$rs['status']);
        }

    }

    // 修改购物商品参数
    public function change_cart(Request $req,Shopping $shopping_model){
        $user_info = auth()->user();
        $data = [
            'id'  =>  $req->id,
            'goods_num' =>$req->goods_num,
            'type'  =>  $req->type??0,
        ];
        $change_type = empty($req->change_type)?false:true;

        $rs = $shopping_model->change_cart($data,$change_type);
        if($rs['status']){
            return $this->success_msg($rs['msg'],$rs['status']);
        }else{
            return $this->error_msg($rs['msg'],$rs['status']);
        }

    }

    // 删除购物商品参数
    public function del_cart(Request $req,Shopping $shopping_model){
        $ids = explode(',',$req->ids);
        $rs = $shopping_model->del_cart($ids);

        if($rs){
            return $this->success_msg('移除购物商品成功',$rs);
        }else{
            return $this->error_msg('移除失败');
        }
    }
    public function get_wxqr(Request $req){
        $type=$req->type??1;
        $info= isset($req->info)?str_replace("|", "/", $req->info):'222';

        $helper_model = new Helper();

        $params=array('scene' => 'type=' .$type . '&info=' . $info, 'page' => 'pages/ConfirmationOrder/ConfirmationOrder');

        $res=$helper_model->getCodeUnlimit($params);

        $url=$req->server('HTTP_HOST').$res;

        if($res){
            return $this->success_msg('获取成功',$res);
        }else{
            return $this->error_msg('获取失败');
        }
    }
}
