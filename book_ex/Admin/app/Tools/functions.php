<?php

// 递归无线树状结构
function getTree($data,$pid=0,$lev=0){
	static $arr = [];
	foreach($data as $v){
		if($v['pid']==$pid){
			$v['lev'] = $lev;
			$arr[] = $v;
			getTree($data,$v['id'],$lev+1);
		}
	}
	return $arr;
}

// 递归无线树状结构
function getAreaTree($data,$pid=0,$lev=0){
	static $arr = [];
	foreach($data as $v){
		if($v['pid']==$pid){
			$v['lev'] = $lev;
			$arr[] = $v;
			getAreaTree($data,$v['area_id'],$lev+1);
		}
	}
	return $arr;
}

// 递归无线树状结构
function getChild($data,$pid=0,$lev=0){
	$arr = [];
	foreach($data as $v){
		if($v['pid']==$pid){
			$v['lev'] = $lev;
            $rs = getChild($data,$v['id'],$lev+1);
            if(!empty($rs)){
                $v['children'] = $rs;
            }
			$arr[] = $v;
		}
	}
	return $arr;
}

// 地区递归无线树状结构
function getAreaChild($data,$pid=0,$lev=0){
	$arr = [];
	foreach($data as $v){
		if($v['pid']==$pid){
			$v['lev'] = $lev;
            $rs =  getAreaChild($data,$v['area_id'],$lev+1);
            if(!empty($rs)){
                $v['children'] = $rs;
            }
			$arr[] = $v;
		}
	}
	return $arr;
}

// 地区递归无线树状结构 无子数据  有标识
function getAreaChildNode($data,$pid=0,$lev=0){
	$arr = [];
	foreach($data as $v){
		if($v['pid']==$pid){
			$v['lev'] = $lev;
            $rs =  getAreaChildNode($data,$v['area_id'],$lev+1);
            if(!empty($rs)){
                $v['hasChildren'] = true;
            }
			$arr[] = $v;
		}
	}
	return $arr;
}

// 获取配置文件内容
function get_format_config($data){
	$res = [];
	foreach($data as $v){
		$res[$v['name']] = $v['val'];
	}
	return $res;
}

// 获取指定图片缩略图
function get_format_image($data,$size=400){
    if(is_array($data)){
        $list = [];
        foreach($data as $v){
            $type = substr($v,strripos($v,'.'));
            $str = substr($v,0,strripos($v,'.'));
            $list[] = $str.'_'.$size.$type;
        }
        return $list;
    }else{
        $type = substr($data,strripos($data,'.'));
        $str = substr($data,0,strripos($data,'.'));
        $small = $str.'_'.$size.$type;
        return $small;
    }
}

// 获取商品列表的 缩略图
function get_format_image_goods_list($goods_list,$size=400){
    foreach($goods_list as $k=>$v){
        $v['image'] = get_format_image($v['goods_master_image'],$size);
        $goods_list[$k] = $v;
    }
    return $goods_list;
}

// 创建随机数
function make_rand(){
    $rand = date('YmdHis').mt_rand(1000,9999);
    return $rand;
}

// 循环获取订单状态
function get_order_status($data){
    foreach($data as $k=>$v){
        $v['cn_status_color'] = '#ca151e';
        if($v['order_status'] == 2){
            $v['cn_status'] = '取消订单';
        }elseif($v['refund'] == 1){
            $v['cn_status'] = '售后处理';
        }elseif($v['pay_status'] == 0 && $v['refund_status'] == 0){
            $v['cn_status'] = '等待支付';
        }elseif($v['pay_status'] == 1 && $v['delivery_status'] == 0 && $v['refund_status'] == 0){
            $v['cn_status'] = '等待发货';
        }elseif($v['pay_status'] == 1 && $v['delivery_status'] == 1 && $v['order_status'] == 0 && $v['refund_status'] == 0 && $v['is_hexiao'] == 1 ){
            $v['cn_status'] = '等待核销';
        }elseif($v['pay_status'] == 1 && $v['delivery_status'] == 1 && $v['order_status'] == 0 && $v['refund_status'] == 0){
            $v['cn_status'] = '等待收货';
        }elseif($v['pay_status'] == 1 && $v['delivery_status'] == 1 && $v['order_status'] == 1 && $v['comment_status'] == 0 && $v['refund_status'] == 0){
            $v['cn_status'] = '等待评论';
        }elseif($v['pay_status'] == 1 && $v['delivery_status'] == 1 && $v['order_status'] == 1 && $v['comment_status'] == 1 && $v['refund_status'] == 0){
            $v['cn_status'] = '订单完成';
            $v['cn_status_color'] = '#67c23a';
        }
        $data[$k] = $v;
    }
    return $data;
}

// 获取订单状态
function get_order_status_one($v){

        $v['cn_status_color'] = '#ca151e';
        if($v['order_status'] == 2){
            $v['cn_status'] = '取消订单';
        }elseif($v['refund'] == 1){
            $v['cn_status'] = '售后处理';
        }elseif($v['pay_status'] == 0 && $v['refund_status'] == 0){
            $v['cn_status'] = '等待支付';
        }elseif($v['pay_status'] == 1 && $v['delivery_status'] == 0 && $v['refund_status'] == 0){
            $v['cn_status'] = '等待发货';
        }elseif($v['pay_status'] == 1 && $v['delivery_status'] == 1 && $v['order_status'] == 0 && $v['refund_status'] == 0 && $v['is_hexiao'] == 1 ){
            $v['cn_status'] = '等待核销';
        }elseif($v['pay_status'] == 1 && $v['delivery_status'] == 1 && $v['order_status'] == 0 && $v['refund_status'] == 0){
            $v['cn_status'] = '等待收货';
        }elseif($v['pay_status'] == 1 && $v['delivery_status'] == 1 && $v['order_status'] == 1 && $v['comment_status'] == 0 && $v['refund_status'] == 0){
            $v['cn_status'] = '等待评论';
        }elseif($v['pay_status'] == 1 && $v['delivery_status'] == 1 && $v['order_status'] == 1 && $v['comment_status'] == 1 && $v['refund_status'] == 0){
            $v['cn_status'] = '订单完成';
            $v['cn_status_color'] = '#67c23a';
        }

    return $v;
}

// 订单状态 条件
function get_order_params($name){
    $where = [];
    switch($name){
        case 'NOPAY': // 未支付
            $where = [
                ['pay_status', 0],['order_status','<',2],['refund_status',0],
            ];
        break;
        case 'WAITSEND': // 等待发货
            $where = [
                'pay_status' => 1,
                'delivery_status' => 0,
                'refund_status' => 0,
            ];
        break;
        case 'WAITREC': // 等待收货
            $where = [
                'pay_status' => 1,
                'delivery_status' => 1,
                'order_status' => 0,
                'refund_status' => 0,
            ];
        break;
        case 'WAITCOMMENT': // 等待评论  // 等待与评论说明订单完成
            $where = [
                'pay_status' => 1,
                'delivery_status' => 1,
                'order_status' => 1,
                'comment_status' => 0,
                'refund_status' => 0,
            ];
        break;
        case 'COMPLETE': // 订单完成
            $where = [
                'pay_status' => 1,
                'delivery_status' => 1,
                'comment_status' => 1,
                'order_status' => 1,
                'refund_status' => 0,
            ];
        break;
        case 'CLOSE': // 取消订单
            $where = [
                'order_status' => 2,
            ];
        break;
        case 'REFUND': // 售后处理
            $where = [
                'refund' => 1,
            ];
        break;
    }
    return $where;
}

// 订单状态 条件
function get_integral_order_params($name){
    $where = [];
    switch($name){
        case 'WAITSEND': // 等待发货
            $where = [
                'pay_status' => 1,
                'delivery_status' => 0,
            ];
        break;
        case 'WAITREC': // 等待收货
            $where = [
                'pay_status' => 1,
                'delivery_status' => 1,
                'order_status' => 0,
            ];
        break;
        case 'COMPLETE': // 订单完成
            $where = [
                'pay_status' => 1,
                'delivery_status' => 1,
                'order_status' => 1,
            ];
        break;
        case 'CLOSE': // 取消订单
            $where = [
                'order_status' => 2,
            ];
        break;
    }
    return $where;
}

// 循环获取订单状态
function get_integral_order_status($data){
    foreach($data as $k=>$v){
        $v['cn_status_color'] = '#ca151e';
        if($v['order_status'] == 2){
            $v['cn_status'] = '取消订单';
        }elseif($v['pay_status'] == 1 && $v['delivery_status'] == 0){
            $v['cn_status'] = '等待发货';
        }elseif($v['pay_status'] == 1 && $v['delivery_status'] == 1 && $v['order_status'] == 0 && $v['is_hexiao']==1){
            $v['cn_status'] = '等待核销';
        }elseif($v['pay_status'] == 1 && $v['delivery_status'] == 1 && $v['order_status'] == 0 ){
            $v['cn_status'] = '等待收货';
        }elseif($v['pay_status'] == 1 && $v['delivery_status'] == 1 && $v['order_status'] == 1){
            $v['cn_status'] = '订单完成';
            $v['cn_status_color'] = '#67c23a';
        }
        $data[$k] = $v;
    }
    return $data;
}

// 获取订单状态
function get_integral_order_status_one($v){
    $v['cn_status_color'] = '#ca151e';
    if($v['order_status'] == 2){
        $v['cn_status'] = '取消订单';
    }elseif($v['pay_status'] == 1 && $v['delivery_status'] == 0 ){
        $v['cn_status'] = '等待发货';
    }elseif($v['pay_status'] == 1 && $v['delivery_status'] == 1 && $v['order_status'] == 0 ){
        $v['cn_status'] = '等待收货';
    }elseif($v['pay_status'] == 1 && $v['delivery_status'] == 1 && $v['order_status'] == 1 ){
        $v['cn_status'] = '订单完成';
        $v['cn_status_color'] = '#67c23a';
    }
    return $v;
}

/**
 * 生成宣传海报
 * @param array  参数,包括图片和文字
 * @param string  $filename 生成海报文件名,不传此参数则不生成文件,直接输出图片
 * @return [type] [description]
 */
function createPoster($config=array(),$filename=""){
    //如果要看报什么错，可以先注释调这个header
    // if(empty($filename)) header("content-type: image/png");
    $imageDefault = array(
      'left'=>0,
      'top'=>0,
      'right'=>0,
      'bottom'=>0,
      'width'=>100,
      'height'=>100,
      'opacity'=>100
    );
    $textDefault = array(
      'text'=>'',
      'left'=>0,
      'top'=>0,
      'fontSize'=>32,       //字号
      'fontColor'=>'255,255,255', //字体颜色
      'angle'=>0,
    );
    $background = $config['background'];//海报最底层得背景
    //背景方法
    $backgroundInfo = getimagesize($background);
    $backgroundFun = 'imagecreatefrom'.image_type_to_extension($backgroundInfo[2], false);
    $background = $backgroundFun($background);
    $backgroundWidth = imagesx($background);  //背景宽度
    $backgroundHeight = imagesy($background);  //背景高度
    $imageRes = imageCreatetruecolor($backgroundWidth,$backgroundHeight);
    $color = imagecolorallocate($imageRes, 0, 0, 0);
    imagefill($imageRes, 0, 0, $color);
    // imageColorTransparent($imageRes, $color);  //颜色透明
    imagecopyresampled($imageRes,$background,0,0,0,0,imagesx($background),imagesy($background),imagesx($background),imagesy($background));
    //处理了图片
    if(!empty($config['image'])){
      foreach ($config['image'] as $key => $val) {
        $val = array_merge($imageDefault,$val);
        $info = getimagesize($val['url']);
        $function = 'imagecreatefrom'.image_type_to_extension($info[2], false);
        if($val['stream']){   //如果传的是字符串图像流
          $info = getimagesizefromstring($val['url']);
          $function = 'imagecreatefromstring';
        }
        $res = $function($val['url']);
        $resWidth = $info[0];
        $resHeight = $info[1];
        //建立画板 ，缩放图片至指定尺寸
        $canvas=imagecreatetruecolor($val['width'], $val['height']);
        imagefill($canvas, 0, 0, $color);
        //关键函数，参数（目标资源，源，目标资源的开始坐标x,y, 源资源的开始坐标x,y,目标资源的宽高w,h,源资源的宽高w,h）
        imagecopyresampled($canvas, $res, 0, 0, 0, 0, $val['width'], $val['height'],$resWidth,$resHeight);
        $val['left'] = $val['left']<0?$backgroundWidth- abs($val['left']) - $val['width']:$val['left'];
        $val['top'] = $val['top']<0?$backgroundHeight- abs($val['top']) - $val['height']:$val['top'];
        //放置图像
        imagecopymerge($imageRes,$canvas, $val['left'],$val['top'],$val['right'],$val['bottom'],$val['width'],$val['height'],$val['opacity']);//左，上，右，下，宽度，高度，透明度
      }
    }
    //处理文字
    if(!empty($config['text'])){
      foreach ($config['text'] as $key => $val) {
        $val = array_merge($textDefault,$val);
        list($R,$G,$B) = explode(',', $val['fontColor']);
        $fontColor = imagecolorallocate($imageRes, $R, $G, $B);
        $val['left'] = $val['left']<0?$backgroundWidth- abs($val['left']):$val['left'];
        $val['top'] = $val['top']<0?$backgroundHeight- abs($val['top']):$val['top'];
        imagettftext($imageRes,$val['fontSize'],$val['angle'],$val['left'],$val['top'],$fontColor,$val['fontPath'],$val['text']);
      }
    }
    //生成图片
    if(!empty($filename)){
      $res = imagejpeg ($imageRes,$filename,90); //保存到本地
      imagedestroy($imageRes);
      if(!$res) return false;
      return $filename;
    }else{
      imagejpeg ($imageRes);     //在浏览器上显示
      imagedestroy($imageRes);
    }
  }
?>
