<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>视频添加</div>
                </div>

                <div class="admin_main_block_right">
                    <div><el-button icon="el-icon-back" @click="$router.go(-1)">返回</el-button></div>
                </div>
            </div>

            <div class="admin_form_main">

                <el-form  label-width="100px" ref="info" :model="info">
                    <el-form-item label="视频标题" prop="goods_name" :rules="[{required:true,message:'视频标题不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.goods_name"></el-input></el-form-item>
                    <!-- <el-form-item label="视频地址" prop="video_address" :rules="[{required:true,message:'视频描述不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" type="textarea" v-model="info.video_address"></el-input></el-form-item> -->

                    <el-form-item label="选择视频" prop="thumb" class="width_auto is-required">

                        <el-upload
                        :action="$api.integralUpload"
                        :headers="upload_headers"
                        list-type="text"
                        ref="upload"
                        :limit="1"
                        :multiple="false"
                        :file-list="file_list2"
                        accept=".mp4,.MP4"
                        :on-success="handleAvatarSuccess"
                        :on-exceed="onExceed"
                        >

                        <i slot="default" class="el-icon-plus"></i>

                        </el-upload>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="submitForm('info')">提交</el-button>
                    </el-form-item>
                </el-form>

            </div>
        </div>



        <el-dialog :visible.sync="dialogVisible">
            <img width="100%" :src="dialogImageUrl" alt="">
        </el-dialog>


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
          info:{
              cid:0,
              goods_status:1,
              is_hot:0,
              goods_name:'',
              live_name:'',
              live_swting:'',
              live_status:0

          },
          class_list:[],
          //live_swting:1,
          upload_headers:{},//上传的请求头部
          file_list:[],
          file_list2:[], // element 内置file_list
          dialogVisible:false,// 是否打开预览
          dialogImageUrl:'', // 预览
          disabled:false,
      };
    },
    watch: {

    },
    computed: {},
    methods: {
        resetForm:function(e){
            this.$refs[e].resetFields();
        },
        submitForm:function(e){
            console.log("执行了submitForm函数")
            let _this = this;

            // 验证表单
            this.$refs[e].validate(function(res){
                console.log("这里是sub下的res:")
                console.log(res)
                if(res){
                    // 图片 验证
                    if(_this.file_list.length!=1){
                        return _this.$message.error('单次只能上传一个视频');
                    }


                     _this.add_goods();


                }
            });
        },

        selectChange:function(){
            console.log("执行了selectChange函数")
            this.$forceUpdate();
        },
        handleAvatarSuccess(res) {
            console.log("这里是handleAvatarSuccess下的res:")
            console.log(res)

            if(this.file_list.length==0){
                this.info.goods_master_image = res.data;
                console.log("这里是 this.info.goods_master_image:")
                console.log(this.info.goods_master_image)
            }
            this.file_list.push(res.data);
            this.$forceUpdate();
        },


        handlePictureCardPreview:function(file){
            console.log("执行了handlePictureCardPreview函数")
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


        // 上传超过限制
        onExceed:function(file){
            var fileSize = file.size / 1024 / 1024 < 50;
            if (!fileSize) {
                    this.$message.error('视频最大50M');
                }
             this.$message.error('每次只允许上传单个视频');
        },

        get_master_image:function(file){
            console.log("执行了get_master_image函数")
            if(file.response != undefined){
                if(file.response.data == this.info.goods_master_image){
                    return 'display:block';
                }else{
                    return 'display:none';
                }
            }
        },

        // 富文本编辑内容变化
        goods_content:function(data){
            this.info.content = data;
        },

        // 添加图书
        add_goods:function(){
            //1:获取表单信息
            let info = this.info;

            //2:获取图片地址
            info.file_list = this.file_list; // 图片
            console.log("执行了add_goods以下是函数输出live_swting")
            console.log(info.live_swting)
            info.live_swting=1;
            //请求
            this.$post(this.$api.addIntegral,info).then(res=>{
                if(res.code == 200){
                    this.$message.success('添加成功');
                    this.$router.go(-1);
                }else{
                    this.$message.error(res.msg);
                }
            });
        },


        // 获取添加初始化信息
        get_goods_add_info:function(){
             console.log("执行了get_goods_add_info函数")
            this.$get(this.$api.addIntegral).then(res=>{
                if(res.code == 500){
                    this.$message.error(res.msg);
                    this.$router.go(-1);
                }else{
                    this.class_list = res.data.integral_class;
                }
            });
        }
    },
    created() {
        this.get_goods_add_info();
        this.upload_headers.Authorization = 'Bearer '+localStorage.getItem('token'); // 要保证取到


    },
    mounted() {

    }
};
</script>
<style lang="scss" scoped>
.el-breadcrumb{
    line-height: 32px;
}
.avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 146px;
    height: 146px;
    line-height: 146px;
    text-align: center;
}
.avatar-upload{
    width: 146px;
    height: 146px;
}

.is_master{
    position: absolute;
    bottom: 0;
    right: 0;
    background: rgba(0,0,0,0.5);
    color:#fff;
    width: 164px;
    text-align: center;
    display: none;
}
.goods_class_add_left{
    float: left;
    margin-right: 15px;
    // border:1px solid #e1e1e1;
    padding:0 20px;
    border-radius: 5px;
    background: #f1f1f1;
}
</style>
