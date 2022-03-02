<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Tools\Commission;

class CommissionController extends NotokenController//BaseController
{
    //获取下线接口
    public function getInfoOne(Request $req){
        $Commission_model=new Commission();
        $data=$Commission_model->getInfoOne($req->user_id);
        return $this->success_msg('ok',$data);
    }
    public function getPoster(Request $req){//说明:https://www.jb51.net/article/136425.htm
        //$url='http://qwshopapi.cmx666.cn';
        $url=$req->server('HTTP_HOST');
        //dd($req->server('HTTP_HOST'));

        $user_id=$req->user_id??0;
        $qrcode=$req->qrcode??$url.'/Common/qrcode.png';

        //生成带有二维码的海报
        $config = array(
            'image'=>array(
              array(
                'url'=>$qrcode,     //二维码资源
                'stream'=>0,
                'left'=>95,
                'top'=>-216,
                'right'=>0,
                'bottom'=>0,
                'width'=>178,
                'height'=>178,
                'opacity'=>100
              )
            ),
            'background'=>$url.'/Common/poster_bg.png'          //背景图
          );
          $filename = 'poster_'.time().'.jpg';

          $returnPath = '/wxmini_poster/'.$user_id;
          $filepath = public_path($returnPath);

          if (!file_exists($filepath)) {
            mkdir($filepath,  0777, true);
         }
         createPoster($config,$filepath.'/'.$filename);

          return $url."".$returnPath."/".$filename;
          //echo createPoster($config);
    }
}
