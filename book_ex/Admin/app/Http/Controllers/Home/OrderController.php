<?php

namespace App\Http\Controllers\Home;

use App\Models\Goods;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderGoods;

class OrderController extends BaseController
{
    // 获取订单需要的信息
    public function buy(Request $req){

    }

    // 开始生成订单
    public function create_order(Request $req,Order $order_model,Goods $goods){
        $data['data'] = $req->info;
        //判断产品是否是限购
        $msg=$this->check_num($req->info);
        if($msg!="")
        {
            return $this->error_msg($msg);
        }
        $data['remark'] = $req->remark;
        $data['is_cart'] = $req->type==1?true:false;
        $data['is_scene']=$req->is_scene==1?true:false;

        //$data['is_shopping'] = $req->type==10?true:false;

       // $data['sh_data'] =isset($req->sh_data)?$req->sh_data:'';

        $data['is_groupbuy'] = false;
        if($req->type == 2){
            $data['is_groupbuy'] = true;
        }
        //Goods::where("")


        $rs = $order_model->create_order($data);
        if($rs['status']){
            return $this->success_msg('ok',$rs['data']);
        }else{
            return $this->error_msg($rs['msg']);
        }
    }

    public function check_num($info)
    {

        $goods=Goods::query();
        $order_model=Order::query();
        $infos=explode("|", $info);
        $goods_id=intval( $infos[0]);
        $g=Goods::find($goods_id);

        if($g->shop_num!=null && $g->shop_num!=0)
        {
            $sum=intval($infos[2]);

            $user_info = auth()->user();

            if($sum>$g->shop_num)
            {
                return $g->goods_name.",每人只能购买".$g->shop_num."件!";
            }
            $order_count= $order_model->where('user_id',$user_info->id)->whereHas('order_goods',function($query) use($goods_id){
                $query->where('goods_id',$goods_id);
            })->count();
            if($order_count+$sum>$g->shop_num){
                return "[".$g->goods_name.",每人只能购买:".$g->shop_num."],您购买的数量超出范围!";
            }

        }
        return "";
    }
    // 根据参数获取订单数据 购物车或者直接购买
    public function get_befor_order(Request $req,Order $order_model){

        $info = $req->get("info");
        $type = $req->get("type")==1?true:false;
        $msg=$this->check_num($info);
        if($msg!="")
        {
            return $this->error_msg($msg);
        }
        // 团购
        $is_groupbuy = false;
        if($req->get("type") == 2){
            $is_groupbuy = true;
        }
        $is_scene=$req->get("is_scene")==1?true:false;

        $store_arr = $order_model->get_befor_order($info,$type,$is_groupbuy,$is_scene);
        if($store_arr["data"]==null)
        {
            return $this->error_msg($store_arr["msg"]);
        }
        return $this->success_msg('ok',$store_arr['data']);
    }

    // 获取订单信息
    public function get_order_info_by_order_no(Request $req,Order $order_model){
        return $this->success_msg('ok',$order_model->getOrderInfoByOrderNo($req->order_no));
    }

    // 获取订单信息
    public function get_order_info_by_order_id(Request $req,Order $order_model,OrderGoods $order_goods_model){
        //$user_info = auth()->user();

        $order_info = $order_model->where('id',$req->get("order_id"))->first();
        $order_info['goods_list'] = $order_goods_model->where('order_id',$order_info['id'])->with(['Goods','goodsCard'])->get();

        if(empty($order_info)){
            return $this->error_msg('非法数据');
        }else{
            if($req->exists("tokenID"))
            {
                if($req->get("tokenID")!="")
                {
                    $pay= new PayMentController();
                    $pay->pay_ok($req->get("order_id"));
                    $order_info = $order_model->where('id',$req->get("order_id"))->first();
                    $order_info['goods_list'] = $order_goods_model->where('order_id',$order_info['id'])->with(['Goods','goodsCard'])->get();
                    //pay_ok()
                }
            }

            $order_info = get_order_status_one($order_info);
//            if($order_info->goodsCard!=null)
//            {
//                $this->lockString($order_info->goodsCard);
//            }
            return $this->success_msg('ok',$order_info);
        }
    }

    // 取消订单
    public function close_order(Request $req,Order $order_model){
        $order_no = $req->order_no;
        $order_info = $order_model->where('order_no',$order_no)->first();
        if($order_info['pay_status'] == 1){
            return $this->error_msg('订单无法取消');
        }
        $order_model->where('order_no',$order_no)->update(['order_status'=>2,'close_time'=>time()]);
        return $this->success_msg('取消成功');
    }

    // 申请售后
    public function refund(Request $req,Order $order_model){
        $id = $req->id;
        $refund_info = $req->refund_info;
        $refund_status = $req->refund_status;
        $rs = $order_model->where('id',$id)->update(['refund'=>1,'refund_status'=>$refund_status,'refund_info'=>$refund_info]);
        return $this->success_msg('申请成功');
    }

    // 寄回快递单号填写
    public function refund_delivery_no(Request $req,Order $order_model){
        $id = $req->id;
        $refund_delivery_no = $req->refund_delivery_no;
        $rs = $order_model->where('id',$id)->update(['refund'=>1,'refund_step'=>2,'refund_delivery_no'=>$refund_delivery_no]);
        return $this->success_msg('提交成功');
    }

    public function update_token(Request $req,Order $order)
    {
        //$order->where("id",$req->get("id"))->update(["tokenID"=>$req->get("")])
    }

}
