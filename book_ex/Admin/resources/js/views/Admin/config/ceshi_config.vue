<template>
    <div class="form">
        <el-form :model="sms_log" status-icon  :rules="rules" ref="ruleForm" label-width="100px" class="demo-ruleForm">
        <el-form-item label="phone" prop="phone" >
            <el-input type="text"  v-model="sms_log.phone" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="is_type" prop="is_type">
            <el-input type="text" v-model="sms_log.secret" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="name" prop="name">
             <el-input type="text" v-model="sms_log.sign_name" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item>
            <el-button type="primary" @click="submitForm('ruleForm')">提交</el-button>
            <el-button @click="resetForm('ruleForm')">重置</el-button>
        </el-form-item>
        </el-form>
    </div>
</template>
<script>
export default {


data() {



      return {
        sms_log: {
          phone: '',
          is_type: '',
          name: ''
        },
        rules: {
          phone: [
            { required:true,message:"phone", trigger: 'blur' }
          ],
          is_type: [
            { required:true,message:"请输入is_type", trigger: 'blur' }
          ],
          name: [
            { required:true,message:"请输入NAME", trigger: 'blur' }
          ]
        }
      };
    },
    methods: {
        //获取sms_Log所有数据的方法
         get_wecaht_public_config(){
            this.$get(this.$api.wechatPublicConfig).then(res=>{
                if(res.data != null){
                    this.sms_log  = res.data;
                }
            });
        },
      submitForm(formName) {
        this.$refs[formName].validate((valid) => {
          if (valid) {
            alert('submit!');
          } else {
            console.log('error submit!!');
            return false;
          }
        });
      },
      resetForm(formName) {
        this.$refs[formName].resetFields();
      }


    }
  }
</script>
<style lang="scss" scoped>
.form{
    width: 420px;
}
</style>
