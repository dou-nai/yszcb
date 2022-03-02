<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GoodsBrand;
use App\Tools\Uploads;
use App\Models\Config;
use EasyWeChat\Factory;
class GoodsBrandController extends BaseController
{
    public function index(Request $req,GoodsBrand $goods_brand_model){
        $list = $goods_brand_model->orderBy('is_sort','desc')->paginate(20);
        return $this->success_msg('Success',$list);
    }

    public function add(Request $req,GoodsBrand $goods_brand_model){
        if(!$req->isMethod('post')){
    		return $this->success_msg('Success',[]);
    	}

    	$data = [
            'name' => $req->name,
            'address'=>$req->address,
            'brand_num'=>$req->brand_num,
    		'thumb' => empty($req->thumb)?'':$req->thumb,
    		'is_sort' => intval($req->is_sort),
    	];

    	$goods_brand_model->insert($data);
    	return $this->success_msg();
    }

    public function edit(Request $req,GoodsBrand $goods_brand_model,$id){
        if(!$req->isMethod('post')){
            $info = $goods_brand_model->find($id);
    		return $this->success_msg('Success',$info);
    	}

    	$data = [
            'name' => $req->name,
            'address'=>$req->address,
            'brand_num'=>$req->brand_num,
    		'thumb' => empty($req->thumb)?'':$req->thumb,
    		'is_sort' => intval($req->is_sort),
    	];

    	$goods_brand_model->where('id',$id)->update($data);
    	return $this->success_msg();
    }

    public function del(Request $req,GoodsBrand $goods_brand_model){
        $id = $req->id;
        $ids = explode(',',$id);
        $goods_brand_model->destroy($ids);
        return $this->success_msg();
    }

    public function goods_barnd_upload(){
        $uploads = new Uploads;
        $rs = $uploads->uploads(['is_thumb'=>1,'filepath'=>'goods_barnd/']);
        if($rs['status']){
            return $this->success_msg('Success',$rs['path']);
        }else{
            return $this->error_msg($rs['msg']);
        }
    }

    // 拉取小程序统计数据
    public function statistics(Request $req,Config $config_model){
        // if($req['0']==20201214){
        //     dd($req['0']);
        // }else{
        //     dd($req);
        //     //$data1 =  $app->data_cube->dailyVisitTrend($fromDate, $fromDate);
        // }
        //dd($req['0']);
        //$det = $this->get_format_data($req['0']);
        //dd(date("Y-m-d",$req['0']));
        $fromDate = $req['0'];

        $config_format = $config_model->get_format_config();
        $config_info = json_decode($config_format['oauth_config'],true);
        $config = [
            //'app_id' => $config_info['appid'],
            //'secret' => $config_info['appsecret'],
            // 'oauth' => [
            //     'scopes'   => ['snsapi_login'],
            //     'callback' => '/user/index',
            // ],
             'app_id' => 'wx89eed701638e3a19',
             'secret' => '87d0bd5eddca37d52eebb00ff1b7ab6a',

            // 下面为可选项
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',

            'log' => [
                'level' => 'debug',
                'file' => __DIR__.'/wechat.log',

            ],
            'payment' => [
                'merchant_id'        => '1563278241',
                'key'                => 'xmkjhhwl1234567890xmkjhhwl123456',
                'cert_path'          => 'D:\UPUPW_ANK_W64\Modules\PHPX\PHP73\cacert.pem',
                //'key_path'           => 'path/to/your/key',
                // ...
            ],
            //必须添加部分
            'guzzle' => [ // 配置
                'verify' => false,
                'timeout' => 4.0,
            ],

        ];
        $app = Factory::miniProgram($config);
        //访问日趋势限定查询1天数据，即 $from 要与 $to 相同；
        //dd();

        if($req['0']==99999999){
            //默认日期前一天
            $dateai=20201213;

            //定义数
            $list=[];

            for($hij=1;$hij<=15;$hij++){
                //一维数组下标
                $keyi=$hij+1;
                //发起请求
                $keyi =  $app->data_cube->dailyVisitTrend($dateai+$hij, $dateai+$hij);
                //请求结果将结果压入栈
                array_push($list,$keyi);
            }

            if(!empty($list['0']['errcode'])){
                return $this->error_msg('err',$list);
            }else{
                return $this->success_msg('ok',$list);
            }
        }else{
            //dd($req);

            //定义数组
            $list=[];

            for($hij=1;$hij<=$req['0']+1;$hij++){
                //echo $req[$hij];
                //echo "<br>";
                //一维数组下标
                $keyi=$hij+1;
                //发起请求
                $keyi =  $app->data_cube->dailyVisitTrend($req[$hij], $req[$hij]);
                //请求结果将结果压入栈
               // echo $hij;
              // dd($keyi);
                if(!isset($keyi["errcode"])){
                    array_push($list,$keyi);
                }else{
                    //return;

                }

                //array_push($list,$keyi);
            }

            if(empty($list)){
                return $this->error_msg('err',$list);
            }else{
                return $this->success_msg('ok',$list);
            }

        }


    }

    public function get_format_data($data)
    {
                $year  = substr($data,0,4);
                $month = substr($data,5,2);
             $day = substr($data,8,2);
                $h = substr($data,11,2);
                $i = substr($data,14,2);
               $s = substr($data,17,2);
               return mktime($h,$i,$s,$month,$data,$year);
           }
}
