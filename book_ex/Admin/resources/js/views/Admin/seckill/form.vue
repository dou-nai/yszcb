<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>活动编辑</div>
                </div>

                <div class="admin_main_block_right">
                    <div><el-button icon="el-icon-back" @click="$router.go(-1)">返回</el-button></div>
                </div>
            </div>

            <div class="admin_form_main">
                <el-form  label-width="100px" ref="info" :model="info">
                    <el-form-item label="活动名称" prop="name" :rules="[{required:true,message:'活动名称不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.name"></el-input></el-form-item>
                    <el-form-item label="所在展馆" prop="hall" :rules="[{required:true,message:'所在展馆不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.hall"></el-input></el-form-item>
                    <el-form-item label="嘉宾" prop="guest" :rules="[{required:true,message:'嘉宾不能为空',trigger: 'blur' }]"><el-input type="textarea" placeholder="请输入内容" v-model="info.guest"></el-input></el-form-item>
                    <el-form-item label="主办方" prop="sponsor" :rules="[{required:true,message:'主办方不能为空',trigger: 'blur' }]"><el-input type="textarea" placeholder="请输入内容" v-model="info.sponsor"></el-input></el-form-item>
                     <el-form-item label="活动主图" prop="thumb" class="width_auto is-required">
                        <el-upload :action="$api.integralUpload" :headers="upload_headers" list-type="picture-card" ref="upload" :file-list="file_list2" :limit="5" :multiple="true"  :on-success="handleAvatarSuccess" :on-exceed="onExceed" >
                            <!-- <i  class="el-icon-plus avatar-uploader-icon"></i> -->
                            <i slot="default" class="el-icon-plus"></i>
                            <div slot="file" slot-scope="{file}">
                                <img class="el-upload-list__item-thumbnail" :src="file.url" alt="" >
                                <div class="is_master" :style="get_master_image(file)"><i class="el-icon-finished"> 主图</i></div>
                                <span class="el-upload-list__item-actions">
                                    <span v-if="!disabled" class="el-upload-list__item-delete" @click="setMaster(file)" >
                                        <i class="el-icon-finished"></i>
                                    </span>
                                    <span v-if="!disabled" class="el-upload-list__item-delete" @click="handlePictureCardPreview(file)" >
                                        <i class="el-icon-zoom-in"></i>
                                    </span>
                                    <span v-if="!disabled" class="el-upload-list__item-delete" @click="handleRemove(file)" >
                                        <i class="el-icon-delete"></i>
                                    </span>
                                </span>
                            </div>
                        </el-upload>
                    </el-form-item>
                    <el-form-item label="活动内容" class="width_auto_70">
                        <wangeditor @goods_content="seckill_content" :contents="info.content"></wangeditor>
                    </el-form-item>
                     <el-form-item class="width_auto"><el-divider content-position="left">选择展厅</el-divider></el-form-item>
                    <el-form-item class="width_auto">
                        <div class="div_apis" v-for="(v,k) in info.brands_list" :key="k" >
                            <el-checkbox style="float:left" v-model="brands" :label="v.id" border>{{v.name}}</el-checkbox>
                            <div class="apis"><el-tag type="info">地址:{{v.address}}</el-tag></div>
                             <el-tag type="success" style="margin-left:15px;">牌号:{{v.brand_num}}</el-tag>
                        </div>

                    </el-form-item>
                    <el-form-item label="开始时间">
                        <el-date-picker format="yyyy-MM-dd HH:mm" v-model="info.start_time" type="datetime" ></el-date-picker>

                    </el-form-item>
                    <el-form-item label="结束时间">
                        <el-date-picker format="yyyy-MM-dd HH:mm" v-model="info.end_time" type="datetime" ></el-date-picker>

                    </el-form-item>
                     <el-form-item label="是否启用" prop="enable">
                        <el-switch v-model="info.enable" active-color="#13ce66"  active-value=1 inactive-value=0 ></el-switch>
                    </el-form-item>

                    <el-form-item>
                        <el-button type="primary" @click="submitForm('info')">提交</el-button>
                        <!-- <el-button @click="resetForm('info')">重置</el-button> -->
                    </el-form-item>
                </el-form>
            </div>
        </div>
    </div>
</template>

<script>
import wangeditor from "@/components/Seller/wangeditor.vue"
export default {
    components: {
        wangeditor,
    },
    props: {},
    data() {
      return {
          edit_id:0,
          info:{
              name:"",
              start_time:"",
              end_time:"",
              content:'',
              goods_master_image:'',
              enable:'1',
              goods_images:'',
              brands_list:[]
          },
          list:[],
          upload_headers:{},
          file_list:[],
          file_list2:[], // element 内置file_list
          dialogVisible:false,// 是否打开预览
          dialogImageUrl:'', // 预览
          disabled:false,
          brands:[]
      };
    },
    watch: {},
    computed: {},
    methods: {
        handleAvatarSuccess(res) {
            if(this.file_list.length==0){
                this.info.goods_master_image = res.data;
            }
            this.file_list.push(res.data);
            this.$forceUpdate();
        },
        handlePictureCardPreview:function(file){
            this.dialogImageUrl = file.url;
            this.dialogVisible = true;
        },
        // 文件删除
        handleRemove:function(file){
            // console.log(file,this.$refs.upload);
            this.$refs.upload.handleRemove(file);
            let index = this.file_list.indexOf(file.url);
            this.file_list.splice(index,1);
        },
        // 设置主图
        setMaster:function(file){
            this.info.goods_master_image = file.response.data;
        },
        // 上传超过限制
        onExceed:function(){
            this.$message.error('图片不能超过5个');
        },
         get_master_image:function(file){
            if(file.response != undefined){
                if(file.response.data == this.info.goods_master_image){
                    return 'display:block';
                }else{
                    return 'display:none';
                }
            }
        },

        // 富文本编辑内容变化
        seckill_content:function(data){
             console.log(data);
            this.info.content = data;
           // this.info.adv_content = data;
            this.$forceUpdate();

            //this.info.content = data;
            //console.log(this.info.content);
        },
        resetForm:function(e){
            this.$refs[e].resetFields();
            this.brands = [];
        },
        submitForm:function(e){
            let _this = this;

            // 验证表单
            this.$refs[e].validate(function(res){
                if(res){
                     // 图片 验证
                    if(_this.file_list.length<=0){
                        return _this.$message.error('主图至少上传一张');
                    }
                    //  判断是Add 或者 Edit
                    let url = _this.$api.addSeckill;
                    if(_this.edit_id>0){
                        url = _this.$api.editSeckill+_this.edit_id;
                    }
                    _this.info.file_list = _this.file_list; // 图片

                    _this.info['brands'] = _this.brands;
                    delete _this.info['brands_list'];

                    // Http 请求
                    _this.$post(url,_this.info).then(function(res){
                        if(res.code == 200){
                            if(_this.edit_id>0){
                                  _this.$message.success("编辑成功");
                            }else{
                                 _this.$message.success("添加成功");
                            }
                            _this.$router.go(-1);
                        }else{
                            if(_this.edit_id>0){
                                  _this.$message.success("编辑失败");
                            }else{
                                 _this.$message.success("添加失败");
                            }
                        }
                    });
                }
            });
        },
        get_seckill_info:function(){
            let _this = this;
            this.$get(this.$api.editSeckill+this.edit_id).then(function(res){

                _this.info = res.data;

                _this.list = res.data.list;

                _this.file_list = _this.info.goods_images.split(',');
               _this.info.goods_images.split(',').forEach(imgRes=>{
                    _this.file_list2.push({name:'',url:imgRes});
                });
                let brands = [];
                if(res.data.brands.length>0){
                    res.data.brands.forEach(brandsRes => {
                        brands.push(brandsRes['id']);
                    });
                    _this.brands = brands;
                }
                console.log( _this.info);
            })

        },
         add_seckill_info:function(){
            let _this = this;
            this.$get(this.$api.addSeckill).then(function(res){
                _this.info.brands_list = res.data.brands_list;
               // _this.info.enable='1';
                console.log(_this.info);
            })
        },
    },
    created() {
        this.upload_headers.Authorization = 'Bearer '+localStorage.getItem('token'); // 要保证取到
        // 判断是否是编辑
        if(!this.$isEmpty(this.$route.params.id)){
            this.edit_id = this.$route.params.id;
        }

        if(this.edit_id>0){
            this.get_seckill_info();
        }else{
            this.add_seckill_info();
        }

    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.line{text-align: center}

.menus_list{
    float: left;
    margin-right: 15px;
    margin-bottom: 15px;
}
.apis{
    margin-left: 15px;
    float: left;
}
.content{
    float: left;
    margin-left: 15px;
}
.div_apis{
    margin-bottom: 15px;
    float: left;
    width: 50%;
}
.div_apis:after{
    content:'';
    display: block;
    clear:both;
}
</style>
