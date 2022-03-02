<?php

namespace App\Tools;

use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use App\Models\Config;
use Illuminate\Http\Request;

class Uploads_file extends Controller{

    private $ossClient = null;
    private $req = null;
    private $data = [];

    private $resp = [
        'status'        =>  false,
        'path'          =>  '',
        // 'thumb_path'    =>  '',
    ];

    private $allow = ['jpeg','png','gif','jpg','mp4','xlsx'];
    private $water = ''; // 水印图片地址
    private $dir = '/shop/';

    private $width = 800;
    private $height = 800;

    /**
     *
     * @author hg
     * $allow  允许上传类型
     * $water  水印地址 不为空则加水印
     * $width $height 默认图片的宽高
     * $is_oss 是否oss上传
     * $filepath 自定义目录存储 空则系统自定义 要带尾部 /
     *
     */


    // 上传文件方法
    public function uploads($data = []){

        $this->data = $data;

        // 默认name
        $name = 'file';

        // 如果没有name默认file
        if(isset($data['name'])){
            $name = $data['name'];
        }

        $this->data['name'] = $name;

        // 如果没有文件则返回false
        if(!request()->hasFile($name)){
            $this->resp['msg'] = '无文件上传！';
            return $this->resp;
        }

        $config_model = new Config;
        $config_list = $config_model->get();
        $config_list_format = get_format_config($config_list);
        $this->data['config_list_format'] = $config_list_format;

        $ali_oss_config = $config_list_format['alioss'];
        $config_alioss_format = json_decode($ali_oss_config,true);
        $this->data['config_alioss_format'] = $config_alioss_format;


        // 判断是本地上传还是OSS
        if(isset($config_alioss_format['status']) && !empty($config_alioss_format['status'])){

            $this->OssClient = new \OSS\OssClient($config_alioss_format['access_key'], $config_alioss_format['secret_access'], $config_alioss_format['endpoint']);

            $returnPath = '/Uploads';
            $filepath = public_path($returnPath);


            $this->data['oss_real_path'] = $this->dir.date('Y_m_d');
            $this->data['real_path'] = $filepath;

            // 判断是否又自定义路径
            if(isset($this->data['filepath'])){
                $this->data['real_path'] = $filepath;
                $this->data['oss_real_path'] = $this->dir.$data['filepath'].date('Y_m_d');
            }

        }else{
            $returnPath = '/Uploads/'.date('Y_m_d');

            // 文件存储路径
            $filepath = public_path($returnPath);

            // 判断是否又自定义路径


            if(isset($this->data['filepath'])){
                $returnPath = '/Uploads/'.$data['filepath'].date('Y_m_d');
                $filepath = public_path($returnPath);
            }

            $this->data['oss_real_path'] = '';
            $this->data['real_path'] = $filepath;
            $this->data['return_path'] = $returnPath;


        }

        if (!file_exists($this->data['real_path'])) {
            mkdir($this->data['real_path'],  0777, true);
        }

        // 判断是多文件上传还是单文件
        if(isset($data['is_many'])){
            return $this->upload_many();
        }else{
            return $this->upload_one();
        }

    }
    // 单文件上传
    private function upload_one(){

        // 获取上传文件
        $files = request()->file($this->data['name']);//获取上传文件
        $ext = strtolower($files->getClientOriginalExtension()); // 获取后缀

        if(!in_array(strtolower($files->getClientOriginalExtension()), $this->allow)){
            $this->resp['msg'] = $files->getClientOriginalExtension().' - 不允许上传';
            return $this->resp;
        }

        // 文件名字
        $filename = time().mt_rand(1000,9999);

        // 判断是否又自定义名字
        if(isset($this->data['filename'])){
            $filename = $this->data['filename'];
        }

        // 保存文件全路径
        $real_path = $this->data['real_path'].'/'.$filename.'.'.$ext;
        $real_path_400 = $this->data['real_path'].'/'.$filename.'_400.'.$ext;
        $real_path_200 = $this->data['real_path'].'/'.$filename.'_200.'.$ext;

        $oss_real_path = $this->data['oss_real_path'].'/'.$filename.'.'.$ext;
        $oss_real_path_400 = $this->data['oss_real_path'].'/'.$filename.'_400.'.$ext;
        $oss_real_path_200 = $this->data['oss_real_path'].'/'.$filename.'_200.'.$ext;

        // 实例化扩展 使用gd
       // $manager = new ImageManager(['driver' => 'gd']);

        //$obj = $manager->make($files);

       // $obj->save($real_path_200); // 保存文件
       $files->move($real_path_200,$filename);

        if(isset($this->data['config_alioss_format']['status']) && !empty($this->data['config_alioss_format']['status'])){
            try {
                $ossInfo = $this->OssClient->uploadFile($this->data['config_alioss_format']['bucket'],ltrim($oss_real_path,'/'),$real_path);
                $this->OssClient->uploadFile($this->data['config_alioss_format']['bucket'],ltrim($oss_real_path_400,'/'),$real_path_400);
                $this->OssClient->uploadFile($this->data['config_alioss_format']['bucket'],ltrim($oss_real_path_200,'/'),$real_path_200);
                unlink($real_path);unlink($real_path_400);unlink($real_path_200); // 删除本地图片
                $this->resp['path'] = $ossInfo['info']['url'];
                $this->resp['status'] = true;
            } catch (OssException $e) {
                $this->resp['msg'] = $e->getMessage();
            }
        }else{
            $this->resp['path'] = $this->data['config_list_format']['web_url'].$this->data['return_path'].'/'.$filename.'.'.$ext;;
            $this->resp['status'] = true;
            $this->resp['local'] = $real_path_200;
        }
        return $this->resp;

    }

    // 多文件上传
    private function upload_many(){
        $this->resp['path'] = [];
        $files = request()->file($this->data['name']);

        foreach($files as $v){
            $ext = strtolower($v->getClientOriginalExtension()); // 获取后缀
            if(!in_array($ext, $this->allow)){
                $this->resp['msg'] = $ext.' - 不允许上传';
                return $this->resp;
            }

            // 文件名字
            $filename = time().mt_rand(1000,9999);

            // 判断是否又自定义名字
            if(isset($this->data['filename'])){
                $filename = $this->data['filename'];
            }

            // 保存文件全路径
            $real_path = $this->data['real_path'].'/'.$filename.'.'.$ext;
            $real_path_400 = $this->data['real_path'].'/'.$filename.'_400.'.$ext;
            $real_path_200 = $this->data['real_path'].'/'.$filename.'_200.'.$ext;

            $oss_real_path = $this->data['oss_real_path'].'/'.$filename.'.'.$ext;
            $oss_real_path_400 = $this->data['oss_real_path'].'/'.$filename.'_400.'.$ext;
            $oss_real_path_200 = $this->data['oss_real_path'].'/'.$filename.'_200.'.$ext;

            // 实例化扩展 使用gd
            $manager = new ImageManager(['driver' => 'gd']);

            $obj = $manager->make($v);

            //  判断是否缩略图
            if(isset($this->data['is_thumb'])){

                // 判断是否有自定义宽高
                if(isset($this->data['width']) && isset($this->data['height'])){
                    $this->width = $this->data['width'];
                    $this->height = $this->data['height'];
                }

                $width = $manager->make($v)->width();
                $height = $manager->make($v)->height();

                if($width > $this->width || $height > $this->height){
                    $width = $this->width;
                    $height = $this->height;
                }

                $obj = $obj->resize($width,$height,function($v){
                    $v->aspectRatio();  // 这个应该是同比例缩减
                })->resizeCanvas($width,$height);

                // 是否加水印
                if(isset($this->data['is_water'])){
                    $obj = $obj->insert($this->data['is_water'],'bottom-right', 10, 10);
                }

                $obj->save($real_path); // 保存文件

                if(isset($this->data['config_alioss_format']['status']) && !empty($this->data['config_alioss_format']['status'])){
                    try {
                        $ossInfo = $this->OssClient->uploadFile($this->data['config_alioss_format']['bucket'],ltrim($oss_real_path,'/'),$real_path);
                        unlink($real_path);
                        $this->resp['path'][] = $ossInfo['info']['url'];
                        $this->resp['status'] = true;
                    } catch (OssException $e) {
                        $this->resp['msg'] = $e->getMessage();
                    }
                }else{
                    $this->resp['path'][] = $this->data['config_list_format']['web_url'].$this->data['return_path'].'/'.$filename.'.'.$ext;;
                    $this->resp['status'] = true;
                }

            }else{
                $width = $manager->make($v)->width();
                $height = $manager->make($v)->height();

                if($width > $this->width || $height > $this->height){
                    $width = $this->width;
                    $height = $this->height;
                }

                $obj = $obj->resize($width,$height,function($vo){
                    $vo->aspectRatio();  // 这个应该是同比例缩减
                })->resizeCanvas($width,$height);

                // 是否加水印
                if(isset($this->data['is_water'])){
                    $obj = $obj->insert($this->data['is_water'],'bottom-right', 10, 10);
                }

                $obj->save($real_path); // 保存文件

                // 存储400px 图片
                $obj = $obj->resize(400,400,function($vo){
                    $vo->aspectRatio();  // 这个应该是同比例缩减
                })->resizeCanvas(400,400);

                // 是否加水印
                if(isset($this->data['is_water'])){
                    $obj = $obj->insert($this->data['is_water'],'bottom-right', 10, 10);
                }

                $obj->save($real_path_400); // 保存文件

                // 存储200px 图片
                $obj = $obj->resize(200,200,function($vo){
                    $vo->aspectRatio();  // 这个应该是同比例缩减
                })->resizeCanvas(200,200);

                // 是否加水印
                if(isset($this->data['is_water'])){
                    $obj = $obj->insert($this->data['is_water'],'bottom-right', 10, 10);
                }

                $obj->save($real_path_200); // 保存文件

                if(isset($this->data['config_alioss_format']['status']) && !empty($this->data['config_alioss_format']['status'])){
                    try {
                        $ossInfo = $this->OssClient->uploadFile($this->data['config_alioss_format']['bucket'],ltrim($oss_real_path,'/'),$real_path);
                        $this->OssClient->uploadFile($this->data['config_alioss_format']['bucket'],ltrim($oss_real_path_400,'/'),$real_path_400);
                        $this->OssClient->uploadFile($this->data['config_alioss_format']['bucket'],ltrim($oss_real_path_200,'/'),$real_path_200);
                        unlink($real_path);unlink($real_path_400);unlink($real_path_200); // 删除本地图片
                        $this->resp['path'][] = $ossInfo['info']['url'];
                        $this->resp['status'] = true;
                    } catch (OssException $e) {
                        $this->resp['msg'] = $e->getMessage();
                    }
                }else{
                    $this->resp['path'][] = $this->data['config_list_format']['web_url'].$this->data['return_path'].'/'.$filename.'.'.$ext;;
                    $this->resp['status'] = true;
                }

            }

        }
        return $this->resp;
    }

}
