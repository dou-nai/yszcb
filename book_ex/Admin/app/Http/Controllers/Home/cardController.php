<?php


namespace App\Http\Controllers\Home;


use App\Models\Goods;
use App\Models\GoodsCard;
use App\Models\Order;
use App\Models\Store;
use App\Models\StoreUser;
use Illuminate\Http\Request;

class cardController extends  BaseController
{
    public function list(Request $req,GoodsCard $goodsCard,Goods $goods)
    {
        $user_info = auth()->user();

        $su= StoreUser::where("user_id",$user_info->id)->with(["store","user"])->first();
        if($su->store==null)
        {
            return $this->success_msg("ok");
        }
        $store_userid=$su->store->user_id;

        $arr=$goods->where("user_id",$store_userid)->pluck('id');
        $where=[];

        if($req->exists("status")==true)
        {
            $where=["status"=>$req->get("status")];

        }
        $data=$goodsCard->whereIn("goods_id",$arr)->where($where)->has("goods")->with(['goods'])->paginate(30);
        return $this->success_msg("ok",$data);
    }
    public function info(Request $req,Order $order,GoodsCard $goodsCard,Goods $goods)
    {
        $user_info = auth()->user();


        $card= $goodsCard->where("sn",$req->get("id"))->first();
        if($card==null)
        {
            return $this->error_msg("卡号不存在!");
        }

        $_goods=$goods->where("id",$card->goods_id)->get()->first();

        //return $this->success_msg($card);
        if($card->sn!="") {

            if ($user_info->id != $_goods->user_id) {

                $store= Store::where("user_id",$_goods->user_id)->first();

                $su= StoreUser::where("user_id",$user_info->id)->where("store_id",$store->id)->first();
                if(!$su){
                    //if ($user_info->id != $_goods->user_id) {
                    return $this->error_msg('商家不符,该商品不是您的商品');
                }

            }
            if ($card->order_no == null) {
                return $this->error_msg('该卡号未销售!');
            }
            if($card->status=="2")
            {
                return $this->error_msg('该卡号已经核销!');
            }
            $card= $goodsCard->where("sn",$req->get("id"))->with(['order','goods'])->first();

            return $this->success_msg('ok', $card);
        }else{
            return $this->error_msg('该卡号无效!',$card);
        }
    }

    public function write_off(Request $req,GoodsCard $goodsCard)
    {
        $user_info = auth()->user();
        $card= $goodsCard->where("sn",$req->get("id"))->first();
        if($card)
        {
            if($card->status==1 && $card->order_no!=null)
            {
                $re=$goodsCard->where("sn",$req->get("id"))->update([
                    "user_id"=>$user_info->id,
                    "pay_time"=>time(),
                    "status"=>2
                ]);

                Order::where("id",$card->order_id)->update(["order_status"=>1, "complete_time"=>time()]);
                return $this->success_msg('核销成功',$re);
            }else{
                return $this->error_msg('核销失败,该卡号已经失效!');
            }
        }
        return $this->error_msg('核销失败,该卡号无效!');

    }
}
