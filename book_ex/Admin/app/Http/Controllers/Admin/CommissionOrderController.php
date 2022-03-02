<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Tools\Helper;

class CommissionOrderController extends BaseController
{
    public function index(Request $req,Order $order_model){

        // 条件
        $params = json_decode($req->params,true);
        if($params['times'] != null && count($params['times'])>0){
            $order_model = $order_model->where('add_time','>=',strtotime($params['times'][0]))->where('add_time','<=',strtotime($params['times'][1]));
        }
        if(!empty($params['type'])){
            $order_model = $order_model->where(get_order_params($params['type']));
        }
        if(!empty($params['order_no'])){
            $order_model = $order_model->where('order_no',$params['order_no']);
        }

        $list = $order_model->with(['commission_order'])->orderBy('id','desc')->paginate(20)->toArray();

        $list['data'] = get_order_status($list['data']);
        return $this->success_msg('Success',$list);
    }
}
