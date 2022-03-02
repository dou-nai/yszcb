<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MoneyLog;

class CommissionOrder extends Model
{
    protected $table = "commission_order"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;
    protected $fillable = ['cengji','orderid','status','fxmoney', 'fxintegral','xd_uid','user_id','distribution','rate','real_price','content','add_time','pay_time','pay_type','mlog_id'];

    // 资金变更
    /**
     * money_change function
     *
     * @param [type] $name  // 类型  如：订单支付  资金冻结
     * @param [type] $type  // 修改字段 如：money  freeze_money  integral
     * @param [type] $money  // 金额 10   -10
     * @param array $user_info  // 用户信息
     * @param string $info  // 描述
     * @param string $pay_type // 支付类型  余额支付
     * @return void
     * @Description  用户资金变更
     * @author hg <qwshop.cmx666.cn>
     */
    public function money_change($name,$type,$money,$user_info=[],$info='',$pay_type='余额支付'){
        $money_log_model = new MoneyLog();

        // 没有传用户信息直接取 当前用户
        if(empty($user_info)){
            $user_info = auth()->user();
        }

        $make_rand = make_rand();

        // 资金变更日志
        $log_data = [
            'log_no'        =>  $make_rand,
            'user_id'       =>  $user_info['id'],
            'nickname'      =>  $user_info['nickname'],
            'name'          =>  $name,
            $type           =>  $money,
            'pay_type'      =>  $pay_type,
            'info'          =>  $info,
        ];

        if($money>0){
            $rs = $this->where('id',$user_info['id'])->increment($type,abs($money));
        }else{
            $rs = $this->where('id',$user_info['id'])->decrement($type,abs($money));
        }

        if($rs){
            $money_log_model->addLog($log_data); // 插入日志
            return true;
        }else{
            return false;
        }


    }


    public function addlog($data){
        return $this->insert($data);
    }

    // 关联到下单人
    public function xd_user(){
    	return $this->hasOne('App\Models\Users','id','xd_uid');
    }
    // 关联到返利人
    public function fl_user(){
    	return $this->hasOne('App\Models\Users','id','user_id');
    }
    // 关联到订单
    public function order_info(){
    	return $this->hasOne('App\Models\Order','id','orderid');
    }
    // 关联到资金记录
    public function money_log(){
    	return $this->hasOne('App\Models\MoneyLog','id','mlog_id');
    }
}
