<?php

namespace App\Http\Controllers\Home;

use App\Models\VipPhone;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Agreement;
use App\Models\Area;
use App\Models\GoodsClass;
use App\Models\AdvPosition;
use App\Models\Adv;
use App\Models\AttrSpec;
/**
 * @author hg <www.qingwuit.com>
 * @example location 非权限接口
 */

class ApiController extends NotokenController
{

    public function check_phone(Request $req)
    {
        return $this->success_msg("ok");
        // $phone=$req->get("phone");
        // $http=new Client();
        // $response=$http->get("http://tool.bitefu.net/shouji/?mobile=$phone");

        // $res=json_decode( $response->getBody(),true);
        // if($res["city"]=="柳州")
        // {
        //     return $this->success_msg("ok");
        // }else{
        //     if(VipPhone::where("phone",$phone)->exists())
        //     {
        //         return $this->success_msg("ok");
        //     }
        // }
        // return $this->error_msg("仅限柳州号码段注册登录！");

    }
    // 获取协议信息
    public function get_agreement_info(Request $req,Agreement $agreement_model){
        $ename = $req->ename;
        $rs = $agreement_model->get_agreement_by_ename($ename);
        return $this->success_msg('Success',$rs);
    }

    // 获取省市区信息
    public function get_area_list(Area $area_model){
        $rs = $area_model->get_area_list();
        return $this->success_msg('Success',$rs);
    }

    // 获取商品分类信息
    public function get_goods_class_list(GoodsClass $goods_class_model){
        $rs = $goods_class_model->get_goods_class_list();
        return $this->success_msg('Success',$rs);
    }

    // 获取广告或者幻灯片
    public function get_banner(Request $req,AdvPosition $adv_position_model){

        $seller_id=$req->seller_id??0;
        $data = $adv_position_model->get_adv_list('手机端-幻灯片',$seller_id); // 获取幻灯片
        return $this->success_msg('Success',$data);
    }
    // 获取公告
    public function get_notice(Request $req,AdvPosition $adv_position_model,Adv $adv_model){
        $adv_position_model=$adv_position_model->where('ap_name','公告')->first();
        $pageSize=$req->pageSize??20;

        $notice_ids=[];
        if(!empty($req->notice_json)){
            $notice_ids=json_decode($req->notice_json);
            $notice_ids=array_column($notice_ids,'id');
            $adv_model=$adv_model->whereNotIn('id',$notice_ids);
        }
        $list = $adv_model->where('ap_id',$adv_position_model->id??-1)->orderBy('adv_sort','asc')->orderBy('id','desc')->paginate($pageSize);
        
        return $this->success_msg('Success',$list);
    }
    public function get_notice_detail(Request $req,Adv $adv_model){
        $adv_model=$adv_model->where('id',$req->id)->first();

        return $this->success_msg('Success',$adv_model);
    }
    public function get_attr_spec(Request $req,AttrSpec $attr_spec_model){
        $list = $attr_spec_model->where('is_hot',1)->orderBy('is_hot','desc')->orderBy('id','desc')->get();
        return $this->success_msg('Success',$list);
    }
}
