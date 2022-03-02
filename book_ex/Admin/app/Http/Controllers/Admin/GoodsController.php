<?php

namespace App\Http\Controllers\Admin;
use App\Models\GoodsCard;
//use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; // 事物用到
use App\Models\Goods;
use App\Tools\Uploads;
use App\Models\GoodsBrand;
use App\Models\GoodsSpec;
use App\Models\AttrSpec;
use App\Models\Config;
use App\Models\FreightTemplate;
use App\Models\GoodsClass;
use PHPExcel_IOFactory;//phpexcel依赖包
use PHPExcel;//phpexcel依赖包
use App\Models\StoreGoodsClass;
use App\Models\Store;
use App\Imports\GoodscImport;
use Excel;
use IOFactory;
use PHPExcel_Cell;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;
// use Maatwebsite\Excel\Concerns\FromQuery;
// use  Maatwebsite\Excel\Concerns\WithHeadings;


class GoodsController extends BaseController
{
    public function index(Request $req,Goods $goods_model){

        if(!empty($req->goods_name)){
            $goods_model = $goods_model->where('goods_name','like','%'.$req->goods_name.'%');
        }
        if(isset($req->goods_status) && $req->goods_status!=''){
            $goods_model = $goods_model->where('goods_status',$req->goods_status);
        }
        $list = $goods_model->with('spec')->orderBy('id','desc')->paginate(20);
        return $this->success_msg('Success',$list);
    }

    // 指定上架
    public function goods_status(Request $req,Goods $goods_model){
        $id = $req->id;
        $goods_info = $goods_model->find($id);
        $goods_status = $goods_info['goods_status']==1?0:1;
        $goods_model->where('id',$id)->update(['goods_status'=>$goods_status]);
        return $this->success_msg();
    }

    // 指定首页
    public function goods_index(Request $req,Goods $goods_model){
        $id = $req->id;
        $goods_info = $goods_model->find($id);
        $goods_status = $goods_info['is_index']==1?0:1;
        $goods_model->where('id',$id)->update(['is_index'=>$goods_status]);
        return $this->success_msg();
    }

    // 指定审核通过
    public function goods_verify_change(Request $req,Goods $goods_model){
        $id = $req->id;
        $status = $req->status??0;
        $goods_model->where('id',$id)->update(['goods_verify'=>$status]);
        return $this->success_msg();
    }

    // 待审核列表
    public function goods_verify(Goods $goods_model){
        $list = $goods_model->with('spec')->where('goods_verify',2)->orderBy('id','desc')->paginate(20);
        return $this->success_msg('Success',$list);
    }

      // 添加商品
      public function add(Request $req,Goods $goods_model,GoodsCard $goodsCard,GoodsBrand $goods_brand_model,GoodsSpec $goods_spec_model,Config $config_model,GoodsClass $goods_class_model,StoreGoodsClass $store_goods_class_model, Store $store_model){
        if(!$req->isMethod('post')){
            $class_id = intval($_GET['class_id']);
            try{
                $class_list2 = $goods_class_model->find($class_id);
                $class_list1 = $goods_class_model->find($class_list2['pid']);
                $class_list0 = $goods_class_model->find($class_list1['pid']);
                //$data['class_list'][] = $class_list0;
                //$data['class_list'][] = $class_list1;
                if(!empty($class_list2)){
                    $data['class_list'][] = $class_list2;
                }else{
                    $data['class_list']=[];
                }

                if(empty($data['class_list'][0]) || empty($data['class_list'][1]) || empty($data['class_list'][2])){
                   // return $this->error_msg('栏目不能为空');
                }
            }catch(\Exception $e){
                return $this->error_msg($e->getMessage());
            }

            $data['goods_brand_list'] = $goods_brand_model->get();
            $data['store_goods_class'] = $store_goods_class_model->get();
            $data['store_list'] =$store_model->take(150)->get();
            return $this->success_msg('Success',$data);
        }
        $user_info = auth()->user();

        $goods_images = implode(',',$req->file_list);                           // 商品图片
        $config_info = get_format_config($config_model->get());                 // 后台配置的查询
        $goods_verify = empty($config_info['goods_verify'])?1:2;                // 根据配置生成商品状态
        $chose_spec_id = '';
        if(isset($req->chose_spec)){                                            // 选择的规格 非使用属性
            foreach($req->chose_spec as $v){
                if($chose_spec_id == ''){
                    $chose_spec_id = $v['id'];
                }else{
                    $chose_spec_id .= ','.$v['id'];
                }
            }
        }

        $chose_attr = '';                                                       // 选择属性
        if(!empty($req->chose_attr)){
            $chose_attr = implode(',',$req->chose_attr);
        }

        $postData = [
            'store_id'               =>  intval($req->store_id),               // 商家ID
            'cid'                   =>  intval($req->cid),                      // 分类ID
            'bid'                   =>  intval($req->bid),                      // 品牌ID
            'goods_no'              =>  $req->goods_no??'',                     // 商品编号
            'goods_name'            =>  $req->goods_name,                       // 商品名
            'goods_subname'         =>  $req->goods_subname??'',                // 商品副标题
            'goods_price'           =>  abs(floatval($req->goods_price)),       // 商品价格
            'goods_market_price'    =>  floatval($req->goods_market_price),     // 商品市场价格
            'goods_num'             =>  abs(intval($req->goods_num)),           // 商品库存
            'goods_images'          =>  $goods_images,                          // 商品图片
            'goods_master_image'    =>  $req->goods_master_image,               // 商品主图片
            'goods_freight'         =>  abs(floatval($req->goods_freight)),     // 商品运费
            'freight_id'            =>  $req->freight_id??0,                    // 商品运费模版
            'chose_spec_id'         =>  $chose_spec_id,                         // 选择的规格 (为了方便编辑存储)
            'chose_attr'            =>  $chose_attr,                            // 选择的属性 (为了方便编辑存储)
            'is_groupbuy'           =>  $req->is_groupbuy??0,                   // 是否参加拼团
            'groupbuy_price'        =>  $req->groupbuy_price??0,                // 拼团商品价格
            'groupbuy_num'          =>  $req->groupbuy_num??0,                  // 拼团需要人数
            'goods_status'          =>  $req->goods_status,                     // 商品状态 上架 下架
            'goods_verify'          =>  $goods_verify,                          // 商品审核状态 1 通过 0 审核失败 2 审核中
            'goods_content'         =>  empty($req->content)?'':$req->content,  // 商品内容
            'store_goods_class'     =>  $req->store_goods_class??0,             // 自定义分类
            'is_top'                =>  $req->is_top,                           // 是否置顶
            'is_virtual'                =>  $req->is_virtual,                                       // 是否虚拟产品
            'add_time'              =>  time(),
            'edit_time'             =>  time(),
            'shop_num'           => $req->shop_num,
            'author'                => $req->author,
            'edition'               => $req->edition,
            'year_publication'      => $req->year_publication
        ];
        DB::beginTransaction();
        try{
            $goods_id = $goods_model->insertGetId($postData); // 插入商品表

            if($postData["is_virtual"]==1) {        //插入虚拟产品的卡号
                $card_arr = explode(",", $req->card_list);
                $cards = [];
                foreach ($card_arr as $item) {
                    $card = [];
                    $card['goods_id'] = $goods_id;

                    $_item=$item.mt_rand(10,99);
                    $_item=$_item.$this->crc16($_item);

                    $card['sn'] = $_item;
                    $card['status'] = 0;
                    $cards[] = $card;
                }

                if (!empty($cards)) {
                    $goodsCard->insert($cards); // 插入数据
                }
            }

            // 循环插入属性
            if(isset($req->spec_attr)){
                $spec_data = [];
                foreach($req->spec_attr as $v){
                    $spec_info = [];
                    $spec_info['goods_id'] = $goods_id;
                    $spec_info['spec_name'] = $v['attr_str'];
                    $spec_info['goods_price'] = abs(floatval($v['price']));
                    $spec_info['goods_market_price'] = abs(floatval($v['market_price']));
                    $spec_info['goods_num'] = abs(intval($v['num']));
                    $spec_data[] = $spec_info;
                }

                if(!empty($spec_data)){
                    $goods_spec_model->insert($spec_data); // 插入数据
                }
            }

            DB::commit(); // 提交事物
            return $this->success_msg('Success');
        }catch(\Exception $e){
            DB::rollBack(); // 回滚
            return $this->error_msg($e->getMessage());
        }

    }

    // 编辑商品
    public function edit(Request $req,Goods $goods_model,GoodsCard $goodsCard,GoodsBrand $goods_brand_model,GoodsSpec $goods_spec_model,Config $config_model,AttrSpec $attr_spec_model,GoodsClass $goods_class_model,FreightTemplate $freight_template_model,StoreGoodsClass $store_goods_class_model,Store $store_model,$id){
        if(!$req->isMethod('post')){
            $data['goods_brand_list'] = $goods_brand_model->get();
            $data['store_goods_class'] = $store_goods_class_model->get();
            $data['store_list'] =$store_model->take(150)->get();

            $data['info'] = $goods_model->with('spec')->find($id);
            if(!empty($data['info']['freight_id'])){
                $data['info']['freight_chose'] = 1;
                $data['freight_info'] = $freight_template_model->find($data['info']['freight_id']);
            }
            $data['chose_spec_list'] = [];
            $data['chose_attr'] = [];
            if(!empty($data['info']['chose_attr'])){
                $data['chose_spec_list'] = $attr_spec_model->whereIn('id',explode(',',$data['info']['chose_spec_id']))->get();
                $data['chose_attr'] = explode(',',$data['info']['chose_attr']);
            }

            // 获取该产品的规格属性
            $spec_list = $goods_spec_model->where('goods_id',$id)->get();
            $data['spec_list'] = [];
            foreach($spec_list as $v){
                $spec_list_info = [];
                $spec_list_info['attr_str'] = $v['spec_name'];
                $spec_list_info['price'] = $v['goods_price'];
                $spec_list_info['market_price'] = $v['goods_market_price'];
                $spec_list_info['num'] = $v['goods_num'];
                $spec_list_info['is_chose'] = true;
                $spec_list_info['attr'] = explode(' ',$v['spec_name']);
                $data['spec_list'][] = $spec_list_info;
            }

            $class_id = intval($data['info']['cid']);
            try{
                $class_list2 = $goods_class_model->find($class_id);
                $class_list1 = $goods_class_model->find($class_list2['pid']);
                $class_list0 = $goods_class_model->find($class_list1['pid']);
                //$data['class_list'][] = $class_list0;
                //$data['class_list'][] = $class_list1;
                if(!empty($class_list2)){
                    $data['class_list'][] = $class_list2;
                }else{
                    $data['class_list']=[];
                }

                if(empty($data['class_list'][0]) || empty($data['class_list'][1]) || empty($data['class_list'][2])){
                    //return $this->error_msg('栏目不能为空');
                }
            }catch(\Exception $e){
                return $this->error_msg($e->getMessage());
            }

            return $this->success_msg('Success',$data);
        }
        $user_info = auth()->user();

        $goods_images = implode(',',$req->file_list);                           // 商品图片
        $config_info = get_format_config($config_model->get());                 // 后台配置的查询
        $goods_verify = empty($config_info['goods_verify'])?1:2;                // 根据配置生成商品状态
        $chose_spec_id = '';                                                    // 选择的规格 非使用属性
        if(isset($req->chose_spec)){                                            // 选择的规格 非使用属性
            foreach($req->chose_spec as $v){
                if($chose_spec_id == ''){
                    $chose_spec_id = $v['id'];
                }else{
                    $chose_spec_id .= ','.$v['id'];
                }
            }
        }
        $chose_attr = '';                                                       // 选择属性
        if(!empty($req->chose_attr)){
            $chose_attr = implode(',',$req->chose_attr);
        }

        $postData = [
            'store_id'               =>  intval($req->store_id),               // 商家ID
            'cid'                   =>  intval($req->cid),                                  // 分类ID
            'bid'                   =>  intval($req->bid),                                  // 品牌ID
            'goods_no'              =>  $req->goods_no??'',                                 // 商品编号
            'goods_name'            =>  $req->goods_name,                                   // 商品名
            'goods_subname'         =>  $req->goods_subname??'',                            // 商品副标题
            'goods_price'           =>  abs(floatval($req->goods_price)),                   // 商品价格
            'goods_market_price'    =>  floatval($req->goods_market_price),                 // 商品市场价格
            'goods_num'             =>  abs(intval($req->goods_num)),                       // 商品库存
            'goods_images'          =>  $goods_images,                                      // 商品图片
            'goods_master_image'    =>  $req->goods_master_image,                           // 商品主图片
            'goods_freight'         =>  abs(floatval($req->goods_freight)),                 // 商品运费
            'freight_id'            =>  $req->freight_id??0,                                // 商品运费模版
            'chose_spec_id'         =>  $chose_spec_id,                                     // 选择的规格 (为了方便编辑存储)
            'chose_attr'            =>  $chose_attr,                                        // 选择的属性 (为了方便编辑存储)
            'is_groupbuy'           =>  $req->is_groupbuy??0,                               // 是否参加拼团
            'groupbuy_price'        =>  $req->groupbuy_price??0,                            // 拼团商品价格
            'groupbuy_num'          =>  $req->groupbuy_num??0,                              // 拼团需要人数
            'goods_status'          =>  $req->goods_status,                                 // 商品状态 上架 下架
            'goods_verify'          =>  $goods_verify,                                      // 商品审核状态 1 通过 0 审核失败 2 审核中
            'goods_content'         =>  empty($req->goods_content)?'':$req->goods_content,  // 商品内容
            'store_goods_class'     =>  $req->store_goods_class??0,                         // 自定义分类
            'is_top'                =>  $req->is_top,                                       // 是否置顶
            'is_virtual'            =>  $req->is_virtual,                                       // 是否虚拟产品
            'edit_time'             =>  time(),
            'shop_num'           => $req->shop_num,
            'author'                => $req->author,
            'edition'               => $req->edition,
            'year_publication'      => $req->year_publication
        ];
        DB::beginTransaction();
        try{
            $goods_model->where('id',$id)->update($postData); // 修改商品表

            $goods_spec_model->where('goods_id',$id)->delete(); // 删除原来的数据重新插入规格数据


            // 循环插入属性
            if(isset($req->spec_attr)){
                $spec_data = [];
                foreach($req->spec_attr as $v){
                    $spec_info = [];
                    $spec_info['goods_id'] = $id;
                    $spec_info['spec_name'] = $v['attr_str'];
                    $spec_info['goods_price'] = abs(floatval($v['price']));
                    $spec_info['goods_market_price'] = abs(floatval($v['market_price']));
                    $spec_info['goods_num'] = abs(intval($v['num']));
                    $spec_data[] = $spec_info;
                }

                if(!empty($spec_data)){
                    $goods_spec_model->insert($spec_data); // 插入数据
                }
            }

            DB::commit(); // 提交事物
            return $this->success_msg('Success');
        }catch(\Exception $e){
            DB::rollBack(); // 回滚
            return $this->error_msg($e->getMessage());
        }
    }

    // 删除商品
    public function del(Request $req,Goods $goods_model,GoodsSpec $goods_spec_model){
        $id = $req->id;
        $user_info = auth()->user();
        $ids = explode(',',$id);
        $goods_model->destroy($ids);
        $goods_spec_model->whereIn('goods_id',$ids)->delete();
        return $this->success_msg();
    }

    // 商品图片上传
    public function goods_upload(){
        $uploads = new Uploads;
        $rs = $uploads->uploads(['filepath'=>'goods/']);
        if($rs['status']){
            return $this->success_msg('Success',$rs['path']);
        }else{
            return $this->error_msg($rs['msg']);
        }
    }

    //Excel表格上传处理函数
    public function excel_upload(){
        $uploads = new Uploads;
        $rs = $uploads->uploads(['filepath'=>'excel/']);
        if($rs['status']){
            return $this->success_msg('Success',$rs['path']);
        }else{
            return $this->error_msg($rs['msg']);
        }
    }

    public function add_excel(Request $req){
         ini_set('max_execution_time', 3000);
         set_time_limit(0);

        ini_set("memory_limit", "1024M");
    //     //$filePathE = asset('/storage/app/'.$req->file_list[0]);
    //     //获取地址
        $pathf = storage_path('app\\'.$req->file_list[0]);

    //     //读取excel表格
    //     $data = Excel::toArray($req, $pathf);
    //     //dd($data);
    //     //表头设置
    //     $data = $this->addAlls($data);

        // //批量插入数据
        // $data = DB::table('goodsc')->insert($data);
        // if($data==true){
        //     return $this->success_msg('Success');
        // }else{
        //     return $this->error_msg('插入失败');
        // }
        //dd($data);


        //使用phpexcel逻辑处理

        //use 引入可能不成功，因此使用路径方式的方式引入phexcel依赖包base_path() 函数用于获取根目录
        require_once(base_path() . '/app/libs/PHPExcel/Classes/PHPExcel.php');
        require_once(base_path() . '/app/libs/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php');
        $phpexcel = new  \PHPExcel();
        $objReader = new \PHPExcel_Reader_Excel2007();
        // $objPHPExcel = $objReader ->load($filename);
        $re =$this->read($pathf,'utf-8');

        //表头设置
        //dd($re);
        $data = $this->addAlls($re);
       //dd($data);
        //批量插入数据
        $data = DB::table('goodsc')->insert($data);
        if($data==true){
            return $this->success_msg('Success');
        }else{
            return $this->error_msg('插入失败');
        }
       // dd($data);
    }

    public function dataUp(Array $data){
        for($i=0;$i<count($data,1);$i++){
            $item = $data[$i];
            dd($item);
        }
    }

    public function import()
    {
        Excel::import(new UsersImport, 'users.xlsx');

        return redirect('/')->with('success', 'All good!');
    }


    //表头设置
    public function addAlls(Array $data)
    {
        //$highestColumnIndex =PHPExcel_Cell::columnIndexFromString($highestColumn);
        $arr=array();
        $row=array();
        $i=0;
        for($ia=1;$ia<count($data,1);$ia++)
        //var_dump(count($data,1));
        //dd($data);
        {
            if(!empty($data[$ia])&&$data[$ia]!=null){
                $i++;
                //dd($data);
            $item = $data[$ia];
        $row= [
            'goods_no'=>$item[0],
            'goods_name'=>$item[1],
            'goods_price'=>$item[2],
            'press_id'=>$item[3],
            'bookbinding'=>$item[4],
            'author'=>$item[5],
            'translate'=>$item[6],
            'classify_one'=>$item[7],
            'classify_two'=>$item[8],
            'classify_three'=>$item[9],
            'classify_top_ten'=>$item[10],
            'object_classify'=>$item[11],
            'booklist_id'=>$item[12],
            'publish_year'=>$item[13],
            'publish_month'=>$item[14],
            'edition'=>$item[15],
            'series_name'=>$item[16],
            'publish_state'=>$item[17],
            'language'=>$item[18],
            'pagination'=>$item[19],
            'cipno'=>$item[20],
            'marketing_classify_one'=>$item[21],
            'marketing_classify_two'=>$item[22],
            'reader_classify'=>$item[23],
            'catalogue'=>$item[24],
            'wonderful'=>$item[25],
            'serial'=>$item[26],
            'theme'=>$item[27],
            'goods_images'=>$item[28],
            //'goods_master_image'=>$item[$num]['29'],
            //['add_time'=>$data[6]['is_type']],
          ];
          array_push($arr,$row);
        }
        }
        var_dump($i);
            return $arr;

    }

    public function read($filename,$encode='utf-8')
    {

        include_once('../app/libs/PHPExcel/Classes/PHPExcel/IOFactory.php');
        require_once(base_path() . '/app/libs/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php');
        //include_once(base_path() . '/app/libs/PHPExcel/Classes/PHPExcel/IOFactory.php');

        //$this->load ->library('PHPExcel/IOFactory');
        $objReader = \PHPExcel_IOFactory::createReader('Excel5');
        $objReader = new \PHPExcel_Reader_Excel2007();
        //$objReader = \IOFactory::createReader('Excel5');
        $objPHPExcel = $objReader ->load($filename); //加载文件
        // $sheet = $objExcel ->getSheet(0);
        // dd($sheet);//读取文件


        // // if ($extension =='xlsx') {
        // //     $objReader = new PHPExcel_Reader_Excel2007();
        // //     $objExcel = $objReader ->load($file);
        // //     dd($objExcel);
        // // } else if ($extension =='xls') {
        // //     $objReader = new PHPExcel_Reader_Excel5();
        // //     $objExcel = $objReader ->load($file);
        // // } else if ($extension=='csv') {
        // //     $PHPReader = new PHPExcel_Reader_CSV();

        // //     //默认输入字符集
        // //     $PHPReader->setInputEncoding('GBK');

        // //     //默认的分隔符
        // //     $PHPReader->setDelimiter(',');

        // //     //载入文件
        // //     $objExcel = $PHPReader->load($file);
        // // }




        // $objReader->setReadDataOnly(true);

        //$objPHPExcel= $objReader->load($filename);

        $objWorksheet= $objPHPExcel->getActiveSheet();

        //取总行数
        $highestRow =$objWorksheet->getHighestRow();

        //echo$highestRow;die;

        $highestColumn = $objWorksheet->getHighestColumn();

        //echo$highestColumn;die;
        //获取列数
        $highestColumnIndex =PHPExcel_Cell::columnIndexFromString($highestColumn);

        $excelData =array();


        for($row = 2;$row <= $highestRow; $row++) {

            for ($col= 0; $col < $highestColumnIndex; $col++) {
                   $excelData[$row][]=(string)$objWorksheet->getCellByColumnAndRow($col,$row)->getValue();
             }

        }
        return $excelData;
        }






}
