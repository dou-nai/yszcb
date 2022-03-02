<?php

namespace App\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Endroid\QrCode\QrCode;  // 生成二维码
use App\Models\Config;
use GuzzleHttp\Client;

/**
 * Helper
 *
 * @Description 辅助类
 * @author hg <www.qingwuit.com>
 */
class Helper extends Controller{

    // 生成二维码
    public function create_qrcode($str='',$path='wxpay_qr_code/',$qr_name=''){
        $qrCode = new QrCode($str);
        // Save it to a file
        if(empty($qr_name)){
            $qr_name = time().mt_rand(10000,99999);
        }
        $paths = '/Uploads/'.$path.$qr_name.'.png';
        $paths2 = '/Uploads/'.$path;
        $filepath = public_path($paths);
        $filepath2 = public_path($paths2);
        if (!file_exists($filepath2)) {
            mkdir($filepath2,  0777, true);
        }
        $qrCode->writeFile($filepath);
        return $paths;
    }

    // 合成图片
    function create_poster($template,$url,$x,$y,$user_id='0'){

        //合成带logo的二维码图片跟 模板图片--------------start
        $path_1 = $template;
        $path_2 = $url;
        $image_1 = imagecreatefrompng($path_1);
        $image_2 = imagecreatefrompng($path_2);
        $image_3 = imageCreatetruecolor(imagesx($image_1),imagesy($image_1));
        $color = imagecolorallocate($image_3, 255, 255, 255);
        imagefill($image_3, 0, 0, $color);
        imageColorTransparent($image_3, $color);
        imagecopyresampled($image_3, $image_1, 0, 0, 0, 0, imagesx($image_1), imagesy($image_1), imagesx($image_1), imagesy($image_1));

        // 创建一个132的模版
        $image_5 = imagecreatetruecolor(132,132);
        list($width_orig,$height_orig) = getimagesize($path_2);
        $width = (132/$height_orig)*$width_orig;
        $height = (132 / $width_orig)*$height_orig;
        imagecopyresampled($image_5,$image_2,0,0,0,0,$width,$height,$width_orig,$height_orig); // 调整到同比例到132 的宽高合并到画布上 生成img5

        imagecopymerge($image_3, $image_5, $x, $y,0, 0, imagesx($image_5), imagesy($image_5), 100);
        //合成带logo的二维码图片跟 模板图片--------------end

        //输出到本地文件夹
        $fileName='poster';//md5(basename($template).$url);
        $EchoPath='/Uploads/inviter/'.$user_id.'/'.$fileName.'.png';
        imagepng($image_3,public_path($EchoPath));
        imagedestroy($image_3);
        //返回生成的路径
        return $EchoPath;
    }


    // 获取快递信息
    public function get_freight_info($no,$type=''){
        $config_model = new Config();
        $config_list = $config_model->get();
        $config_format = get_format_config($config_list);
        $host = "https://wuliu.market.alicloudapi.com";//api访问链接
        $path = "/kdi";//API访问后缀
        $method = "GET";
        $appcode = $config_format['freight_key'];//替换成自己的阿里云appcode
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $querys = "no=".$no."&type=".$type;  //参数写在这里
        $bodys = "";
        $url = $host . $path . "?" . $querys;//url拼接

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        //curl_setopt($curl, CURLOPT_HEADER, true); 如不输出json, 请打开这行代码，打印调试头部状态码。
        //状态码: 200 正常；400 URL无效；401 appCode错误； 403 次数用完； 500 API网管错误
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        return curl_exec($curl);
    }

    // 发送http请求
    public function http_send($url,$data=[],$method="GET"){
        $client = new Client();
        $params = [];
        if($method == 'GET'){
            $params['query'] = $data;
        }else{
            $params['form_params'] = $data;
        }
        $response = $client->request($method,$url,$params);

        return $response->getBody();
    }

    //自己建个服务类，把下面方法放到服务类里面静态去调用
public static function httpRequest($url, $post_data='', $method='GET'){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        if($method=='POST')
        {
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($post_data != '')
            {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
            }
        }

        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    public function getWxAccessToken()
    {

        $app_id     = 'wxa1151a751ddafe1f';
        $app_secret = '4b35d354a07c4243bfcdabb883a50563';
        if (!$app_id || !$app_secret) {
            return [false, '获取小程序配置失败!'];
        }

        if (\Cache::get('access_token_' . $app_id)) {
            return [true, \Cache::get('access_token_' . $app_id)];
        } else {
            $third_api = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $app_id . "&secret=" . $app_secret;
            $result    = $this->httpRequest($third_api);


            $json      = json_decode($result, true);

            if (!isset($json['access_token']) && !$json['access_token']) {
                return [false, '获取access_token失败'];
            }
            $access_token = $json['access_token'];
            \Cache::put('access_token_' . $app_id, $access_token, 120);
            return [true, $access_token];
        }
    }
     /**
     * 获取 带参小程序码
     * @return mixed
     */
    public function getCodeUnlimit($params = array()){


        if(empty($params) || !is_array($params)) {
            return [-1, '参数错误(params)'];
        }
        if(empty($params['scene']) || empty($params['page'])) {
            return [-1, '参数错误(scenepage)'];
        }

        $accessToken = $this->getWxAccessToken();

        if(!$accessToken[0]) {
            return [-1, $accessToken[1]];
        }

        $request = $this->httpRequest('https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=' . $accessToken[1], json_encode($params),'POST');

        $imgBase64Code = "data:image/jpeg;base64," . base64_encode($request);
       // echo  '<img src="'.$imgBase64Code.'">'; die;

        return $imgBase64Code;
    }

}
