<?php
/*
 * @Author: your name
 * @Date: 2020-05-20 16:02:40
 * @LastEditTime: 2020-10-24 15:19:31
 * @LastEditors: your name
 * @Description: In User Settings Edit
 * @FilePath: \ShopX\app\Http\Controllers\Home\SeckillController.php
 */

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Models\Seckill;
use App\Models\SeckillGoods;
use Illuminate\Support\Facades\DB;
use App\Models\GoodsBrand;
class SeckillController extends NotokenController
{
    // 获取秒杀活动 未结束的
    public function get_seckill_detail(Request $req,Seckill $seckill_model,SeckillGoods $seckill_goods_model,GoodsBrand $goods_brand_model){
        $id = $req->id??0;

        if(!empty($id)){
            $data['seckill_list'] = $seckill_model->where('id',$id)->take(1)->get()->toArray();
        }else{
            $data['seckill_list'] = $seckill_model->where('enable',1)->orderBy('is_index','desc')->orderBy('id','desc')->take(1)->get()->toArray();
        }


        if(empty($data['seckill_list'])){
            return $this->success_msg('没有活动进行',$data);
        }

        if(empty($id)){
            $id = $data['seckill_list'][0]['id'];
        }

        $seckill_info = [];

        foreach($data['seckill_list'] as $k=>$v){
            if($v['id'] == $id){
                $seckill_info = $v;
            }
            $v['is_active'] = $v['start_time']<time()?true:false;
            $data['seckill_list'][$k] = $v;
        }

        $prefix = DB::getTablePrefix(); // 数据量前缀

        $pageSize=$req->pageSize??20;

        $data['seckill_goods'] = $seckill_goods_model->select(DB::raw('(select count(*) from '.$prefix.'store_comment as B where '.$prefix.'seckill_goods.goods_id = B.goods_id) as comment_count,seckill_goods.*'))->where('sid',$id)->has('goods')->with(['goods','spec_once'])->paginate($pageSize)->toArray();

        $data['seckill_info'] = $seckill_info;

        $ids=explode(',',$seckill_info['brands']);
        if(!empty($ids)){
            $data['seckill_brands'] = $goods_brand_model->whereIn('id',$ids)->orderBy('is_sort','desc')->get();
        }else{
            $data['seckill_brands'] =[];
        }

        return $this->success_msg('ok',$data);

    }
    public function get_seckill_list(Request $req,Seckill $seckill_model,SeckillGoods $seckill_goods_model){
        $pageSize=$req->pageSize??20;
        $data= $seckill_model->where('enable',1)->orderBy('is_index','desc')->orderBy('id','desc')->paginate($pageSize);
        return $this->success_msg('ok',$data);
    }
}
