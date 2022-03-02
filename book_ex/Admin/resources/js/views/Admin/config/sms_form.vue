<template>
    <div>
       <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>信息编辑</div>
                </div>

                <div class="admin_main_block_right">
                    <div><el-button icon="el-icon-back" @click="$router.go(-1)">返回</el-button></div>
                </div>
            </div>

            <div class="admin_form_main">
                <el-form  label-width="100px" ref="info" :model="info">

                    <el-form-item label="add_time" prop="add_time" ><el-input disabled placeholder="添加时间自动生成" v-model="info.add_time"></el-input></el-form-item>
                    <el-form-item label="code" prop="code" :rules="[{required:true,message:'code不能为空',trigger: 'blur' }]"><el-input placeholder="请输入code" v-model="info.code"></el-input></el-form-item>
                    <el-form-item label="is_type" prop="is_type" :rules="[{required:true,message:'is_type不能为空',trigger: 'blur' }]"><el-input placeholder="请输入is_type" v-model="info.is_type"></el-input></el-form-item>
                    <el-form-item label="is_use" prop="is_use" :rules="[{required:true,message:'is_use不能为空',trigger: 'blur' }]"><el-input placeholder="请输入is_use" v-model="info.is_use"></el-input></el-form-item>
                    <el-form-item label="name" prop="name" :rules="[{required:true,message:'name不能为空',trigger: 'blur' }]"><el-input placeholder="请输入name" v-model="info.name"></el-input></el-form-item>
                    <el-form-item label="phone" prop="phone" :rules="[{required:true,message:'phone不能为空',trigger: 'blur' }]"><el-input placeholder="请输入手机号" v-model="info.phone"></el-input></el-form-item>
                    <el-form-item label="status" prop="status" :rules="[{required:true,message:'status不能为空',trigger: 'blur' }]"><el-input placeholder="请输入status" v-model="info.status"></el-input></el-form-item>


                    <el-form-item>
                        <el-button type="primary" @click="submitForm('info')">提交</el-button>
                        <el-button @click="resetForm('info')">重置</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
     components: {},
    props: {},
    data() {
      return {
          edit_id:0,
          info:{
            add_time:""
          },
          list:[],
          //文本框只读限制
          disabled: true
      };
    },
    onload(){

    },
    watch: {},
    computed: {},
    methods: {
        //1:表单重置函数
        resetForm:function(e){
            this.$refs[e].resetFields();
        },

        //2:提交表单处理函数
        submitForm:function(e){
            let _this = this;

            // 验证表单
            this.$refs[e].validate(function(res){
                if(res){
                    //  判断是Add 或者 Edit
                    let url = _this.$api.addSmsLog;
                    if(_this.edit_id>0){
                        url = _this.$api.editSmsLog+_this.edit_id;
                    }
                    // Http 请求
                    //console.log(url)
                    _this.$post(url,_this.info).then(function(res){
                        if(res.code == 200){
                            _this.$message.success("操作成功");
                            _this.$router.go(-1);
                        }else{
                            _this.$message.error("操作失败");
                        }
                    });
                }
            });
        },

        //3:获取当前id数据的函数
        get_sms_info:function(){
            let _this = this;
            this.$get(this.$api.editSmsLog+this.edit_id).then(function(res){
                    _this.info = res.data;
                    _this.list = res.data.list;
            })
            .catch(function(error){
                console.log(error)
            })

        },



    },

    //created是创建完毕后执行的钩子函数
    created() {

        //1:判断是否是编辑传过来的id不为空则进行赋值
        if(!this.$isEmpty(this.$route.params.id)){
            this.edit_id = this.$route.params.id;
            //console.log(this.edit_id)
        }
        //2:当edit_id>0则表示需要查询一条信息记录
        if(this.edit_id>0){
            this.get_sms_info();

        }

    },
    mounted() {}
}
</script>

<style lang="less" scoped>

</style>
