<?php

namespace App\Tools;

use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use App\Models\Config;
use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\CommissionOrder;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

/**
 * Commission
 *
 * @Description 分销类
 * @author liu <qwshop.cmx666.cn>
 */
class Commission extends Controller{
    //获取订单分销商信息
    public function getFxorderInfo($orderid){

    }

     // 获取下线
     public function getInfoOne($user_id){

        if(empty($user_id)){
            return '';
        }
        $CommissionOrder_model=new CommissionOrder();
        $v=[
            'id'=>$user_id
        ];
        $down=$this->getAllDown($v['id']);

        $v['down1']=$down['down1'];
        $v['down2']=$down['down2'];
        $v['down3']=$down['down3'];
        $v['down_num1']=count($down['down1']);
        $v['down_num2']=count($down['down2']);
        $v['down_num3']=count($down['down3']);

        $v['tongji']=$CommissionOrder_model->where('user_id',$v['id'])
        ->select(DB::raw("sum(fxmoney) as fxmoney,
        sum(IF (status = 9, fxmoney, 0)) as yfxmoney,
        sum(IF (status = 0, fxmoney, 0)) as wfxmoney,
        sum(fxintegral) as fxintegral,
        sum(IF (status = 9, fxintegral, 0)) as yfxintegral,
        sum(IF (status = 0, fxintegral, 0)) as wfxintegral,
        count(*) as fxorder,
        count(status=9 or null) as yfxorder,
        count(status=0 or null) as wfxorder"))
        ->first();
        return $v;
    }
    // 获取下线
    public function getInfo($data){
        $CommissionOrder_model=new CommissionOrder();
        foreach($data as $k=>$v){
            $down=$this->getAllDown($v['id']);

            $v['down1']=$down['down1'];
            $v['down2']=$down['down2'];
            $v['down3']=$down['down3'];
            $v['down_num1']=count($down['down1']);
            $v['down_num2']=count($down['down2']);
            $v['down_num3']=count($down['down3']);

            $v['tongji']=$CommissionOrder_model->where('user_id',$v['id'])
            ->select(DB::raw("sum(fxmoney) as fxmoney,
            sum(IF (status = 9, fxmoney, 0)) as yfxmoney,
            sum(IF (status = 0, fxmoney, 0)) as wfxmoney,
            sum(fxintegral) as fxintegral,
            sum(IF (status = 9, fxintegral, 0)) as yfxintegral,
            sum(IF (status = 0, fxintegral, 0)) as wfxintegral,
            count(*) as fxorder,
            count(status=9 or null) as yfxorder,
            count(status=0 or null) as wfxorder"))
            ->first();

            $data[$k] = $v;
        }
        return $data;
    }
    public function getAllDown($u_id,$dp_cengji=1,$cengji=3){
        $users_model=new Users();
        $agents =$users_model->where('inviter_id',$u_id)->get();
        $ids = array();
        $openids = array();
        $users = array();
        $down1=array();
        $down2=array();
        $down3=array();

        foreach ($agents as $val) {
            $ids[] = $val['id'];
            $openids[] = $val['open_id'];
            $users[$val['id']] = $val;


            if($dp_cengji==1){
                $down1[]=$val;
            }else if($dp_cengji==2){
                $down2[]=$val;
            }else if($dp_cengji==3){
                $down3[]=$val;
            }

            if ($cengji>=$dp_cengji) {
                $d=(int)$dp_cengji+1;
                $arr = $this->getAllDown($val['id'],$d);
                if ($arr) {
                    $ids = array_merge($ids, $arr['ids']);
                    $openids = array_merge($openids, $arr['openids']);
                    $users = array_merge($users, $arr['users']);
                    $down1 = array_merge($down1, $arr['down1']);
                    $down2 = array_merge($down2, $arr['down2']);
                    $down3 = array_merge($down3, $arr['down3']);
                }
            }
        }

        return array(
            'ids' => $ids,
            'openids' => $openids,
            'users' => $users,
            'down1'=>$down1,
            'down2'=>$down2,
            'down3'=>$down3,
        );
    }
    public function add_fxorder($orderid){
        $order_model=new Order();
        $order=$order_model->where('id',$orderid)->first();
       // return $order;
        $Config_model=new Config();
        $distribution = $Config_model->where('name','distribution')->first();
        if(empty($distribution)){
            return;
        }
        $data=array(
            'orderid'=>$orderid,
            'status'=>0,
            'xd_uid'=>$order['user_id'],
            'distribution'=>$distribution['val'],
            'real_price'=>$order['order_price'],
            'content'=>'分销返利',
            'add_time'=>time(),
        );

        $rate_set=json_decode($distribution['val']);

        $users_model=new Users();
        $user=$users_model->where('id',$order['user_id'])->first();
        //一级
        if($user['inviter_id']>0){
            $agent=$users_model->where('id',$user['inviter_id'])->first();
            if(!empty($agent)){
                $rate=$rate_set->distribution;
                $pay_type=$rate_set->status==1?1:2;
                $pay_money= $rate*$data['real_price']/100;
                $this->add_fxorder_data($data,1,$agent['id'],$rate,$pay_type,$pay_money);
                //二级
                if($agent['inviter_id']>0){
                    $agent1=$users_model->where('id',$agent['inviter_id'])->first();
                    if(!empty($agent1)){
                        $rate=$rate_set->distribution_1;
                        $pay_type=$rate_set->status==1?1:2;
                        $pay_money= $rate*$data['real_price']/100;
                        $this->add_fxorder_data($data,2,$agent1['id'],$rate,$pay_type,$pay_money);
                         //三级
                        if($agent1['inviter_id']>0){
                            $agent2=$users_model->where('id',$agent1['inviter_id'])->first();
                            if(!empty($agent2)){
                                $rate=$rate_set->distribution_2;
                                $pay_type=$rate_set->status==1?1:2;
                                $pay_money= $rate*$data['real_price']/100;
                                $this->add_fxorder_data($data,3,$agent2->id,$rate,$pay_type,$pay_money);
                            }
                        }

                    }
                }

            }
        }
        return 1;
    }
    public function add_fxorder_data($data,$cengji,$user_id,$rate,$pay_type,$pay_money){
        $CommissionOrder_model=new CommissionOrder();
        $data['cengji']=$cengji;
        $data['user_id']=$user_id;
        $data['rate']=$rate;
        $data['pay_type'] =$pay_type;
        if($pay_type==1){
            $data['fxmoney'] =$pay_money;
        }else if($pay_type==2){
            $data['fxintegral'] =$pay_money;
        }

        $CommissionOrder_model->addlog($data);
    }
}
