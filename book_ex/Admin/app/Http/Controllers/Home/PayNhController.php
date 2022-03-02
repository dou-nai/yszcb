<?php
namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Models\Config;
use App\Models\Order;
use App\Abcpay\pay;
use App\Tools\Helper;

class PayNhController extends BaseController
{

    public function pay(Request $req,Config $config_model,Order $order_model,Helper $helperObj,pay $pay_model){

        $payment_name = $req->payment_name??'';  // 获取支付方式
        $order_no = $req->order_no??''; // 订单号

        if(empty($payment_name) || empty($order_no)){
            return $this->error_msg('支付方法错误或订单号非法');
        }

        $pay_order_info = [];  // 传入第三方支付的订单数据
        $order_list = $order_model->where('order_no',$order_no)->get();// 数据库订单信息

        // 支付订单信息
        $pay_order_info['out_trade_no'] = $order_list[0]['order_no'].'|'.$payment_name;
        $pay_order_info['total_amount'] = 0;
        foreach($order_list as $v){
            $pay_order_info['total_amount'] +=  $v['total_price']-$v['balance'];
        }
        $pay_order_info['subject'] = $order_list[0]['order_name'];

        if(empty($order_list)){
            return $this->error_msg('订单不存在！');
        }

        $data=array(
            'PayTypeID'=>'ImmediatePay',//
            'OrderDate'=>date('Y/m/d',time()),//
            'OrderTime'=>date('H:i:s',time()),//
            'orderTimeoutDate'=>'',
            'OrderNo'=>$order_no,//
            'CurrencyCode'=>'156',//
            'PaymentRequestAmount'=>$pay_order_info['total_amount'],
            'Fee'=>'',
            'AccountNo'=>$pay_order_info['out_trade_no'],
            'OrderDesc'=>'订单号:'.$order_list[0]['id'],
            'OrderURL'=>'',
            'ReceiverAddress'=>'',
            'InstallmentMark'=>'0',//
            'InstallmentCode'=>'',
            'InstallmentNum'=>'',
            'CommodityType'=>'0410',//
            'BuyIP'=>'',
            'ExpiredDate'=>'',
            'PaymentType'=>'A',//
            'PaymentLinkType'=>'1',//
            'UnionPayLinkType'=>'0',//
            'ReceiveAccount'=>'',
            'ReceiveAccName'=>'',
            'NotifyType'=>'1',//
            'ResultNotifyURL'=>'http://nh1.boyaltd.cn/MerchantResult.php',//
            'MerchantRemarks'=>'',
            'ReceiveMark'=>'',
            'ReceiveMerchantType'=>'',
            'IsBreakAccount'=>'0',//
            'SplitAccTemplate'=>'',
            'SplitMerchantID'=>array(),
            'SplitAmount'=>array()
         );

        $rs=$pay_model->getPay($data);

        return $this->success_msg('ok',$rs);

    }

    public function paytest(Request $req,Config $config_model,Order $order_model,Helper $helperObj,pay $pay_model)
    {
        $payment_name = $req->payment_name??'';  // 获取支付方式
        $order_no = $req->order_no??''; // 订单号

        if(empty($payment_name) || empty($order_no)){
            return $this->error_msg('支付方法错误或订单号非法');
        }

        $pay_order_info = [];  // 传入第三方支付的订单数据
        $order_list = $order_model->where('order_no',$order_no)->get();// 数据库订单信息

        // 支付订单信息
        $pay_order_info['out_trade_no'] = $order_list[0]['order_no'].'|'.$payment_name;
        $pay_order_info['total_amount'] = 0;
        foreach($order_list as $v){
            $pay_order_info['total_amount'] +=  $v['total_price']-$v['balance'];
        }
        $pay_order_info['subject'] = $order_list[0]['order_name'];

        if(empty($order_list)){
            return $this->error_msg('订单不存在！');
        }

        return $this->success_msg('ok',$rs);
    }
}
