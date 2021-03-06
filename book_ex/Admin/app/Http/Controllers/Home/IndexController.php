<?php
/*
 * @Author: your name
 * @Date: 2020-05-20 16:02:40
 * @LastEditTime: 2020-10-24 14:38:22
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \ShopX\app\Http\Controllers\Home\IndexController.php
 */

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GoodsClass;
use App\Models\GoodsBrand;
use App\Models\AdvPosition;
use App\Models\Config;
use App\Models\Goods;
use App\Models\Seckill;
use App\Models\SeckillGoods;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;


class IndexController extends NotokenController
{
    // 导航全部分类的获取
    public function get_subnav_info(GoodsClass $goods_class_model,GoodsBrand $goods_brand_model,AdvPosition $adv_position_model){
        $data['goods_class'] = $goods_class_model->get_goods_class_list();
        $data['goods_brand'] = $goods_brand_model->take(12)->get();
        $data['goods_brand_adv'] = $adv_position_model->get_adv_list('PC_首页左导航栏');
        return $this->success_msg('ok',$data);
    }

    // 获取首页全部信息
    public function get_index_info(Request $request, AdvPosition $adv_position_model,GoodsClass $goods_class_model,Seckill $seckill_model,SeckillGoods $seckill_goods_model,Config $config){

        if(Cache::has('get_index_info'))
        {
            $data=json_decode(Cache::get("get_index_info"));
            return $this->success_msg('ok-cache',$data);
        }
        //dd($request->get("wx"));


        $data['banner_bottom_adv'] = $adv_position_model->get_adv_list('PC_首页幻灯片下广告');
        $data['banner'] = $adv_position_model->get_adv_list('PC_首页幻灯片');
        $configs=$config->whereIn("name",['web_name','web_url','logo','mobile'])->get();

        $data['config']['app_name']=$configs->where('name','web_name')->first()->val;
        $data['config']['logo']=$configs->where('name','logo')->first()->val;
        $data['config']['web_url']=$configs->where('name','web_url')->first()->val;

        // 秒杀产品seckill
        //$seckill_info = $seckill_model->where('end_time','>',time())->first();
        $seckill_info = $seckill_model->where('enable',1)->first();
        $data['seckill_goods'] = $seckill_goods_model->where('status',1)->where('sid',$seckill_info['id'])->take(4)->with(['goods','spec_once'])->get()->toArray();
        foreach($data['seckill_goods'] as $k=>$v){
            $data['seckill_goods'][$k]['goods']['image'] = get_format_image($data['seckill_goods'][$k]['goods']['goods_master_image'],200);
            if(!empty($v['spec_once'])){
                $data['seckill_goods'][$k]['goods']['goods_price'] = $v['spec_once']['goods_price'];
                $data['seckill_goods'][$k]['goods']['goods_market_price'] = $v['spec_once']['goods_market_price'];
            }
        }
        $data['seckill_info'] = $seckill_info;

        // 栏目商品
        $goods_class = $goods_class_model->where('pid',0)->get()->toArray();
        $goods_list = [];
        foreach($goods_class as $k=>$v){
            $goods_list[$k]['list'] = $goods_class_model->getGoodsListByClassId([$v['id']],[],['list'=>['is_index'],'order_type'=>'desc'],8);
            $goods_list[$k]['class_info'] = $v;
        }
        $data['goods_list'] =  $goods_list;
        $data['all_goods_list'] =$goods_class_model->searchGoods('',[],['list'=>['is_index'],'order_type'=>'desc'],'',8);
        $data['goods_list_left_adv'] = $adv_position_model->get_adv_list('PC_首页商品列表左侧');
        $data['goods_list_bottom_adv'] = $adv_position_model->get_adv_list('PC_首页商品列表底部');
        Cache::put('get_index_info',json_encode($data));
        return $this->success_msg('ok',$data);
    }

    // 获取首页大栏目 推荐商品
    public function get_foot_info(AdvPosition $adv_position_model,Config $config_model){
        $data['adv'] = $adv_position_model->get_adv_list('PC_首页版权底部');
        $data['info'] = $config_model->get_format_config();
        return $this->success_msg('ok',$data);
    }

    // 加入我们 幻灯片
    public function get_join_index_adv(AdvPosition $adv_position_model){
        $data = $adv_position_model->get_adv_list('PC_加入我们幻灯片');
        return $this->success_msg('ok',$data);
    }




}
