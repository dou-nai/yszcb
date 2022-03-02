<template>
    <div class="change_password">
        <el-container>
        <el-header><heade-r></heade-r></el-header>
        <el-main>
            <div style="position: relative;">
                <div class="change_password_main_top">
                    <p class="personage">忘记密码</p>
                </div>
                <div class="change_password_main_bottom">
                    <el-card class="box-card">
                        <div class="box-card-top">
                            <p class="box-card-title">云书展·忘记密码</p>
                        </div>
                        <div class="box-card-main">
                            <el-form label-width="0" class="demo-ruleForm">
                                <el-form-item>
                                    <el-input v-model="info.phone" placeholder="请输入手机号"></el-input>
                                </el-form-item>
                                <el-form-item  class="codeclass">
                                    <el-input v-model="info.code" placeholder="请输入手机验证码"></el-input>
                                    <span class="send" @click="send_sms()">获取验证码 </span>
                                </el-form-item>
                                <el-form-item>
                                    <el-input v-model="info.password" placeholder="请输入密码"></el-input>
                                </el-form-item>
                                <el-form-item>
                                    <el-input  v-model="info.password_comp" placeholder="请输入确认密码"></el-input>
                                </el-form-item>
                                <el-form-item>
                                    <el-button type="primary" v-if="!submit_disabled" @click="to_register">提交</el-button>
                                    <el-button type="primary" v-if="submit_disabled" disabled>提交</el-button>
                                </el-form-item>
                            </el-form>
                        </div>

                    </el-card>
                </div>
            </div>
        </el-main>
        <el-footer><foote-r></foote-r></el-footer>
        </el-container>
    </div>
</template>

<script>
import FooteR from "@/components/home/public/ceshi/footer.vue"
import HeadeR from "@/components/home/public/ceshi/header.vue"
export default {
    components: {
        FooteR,
        HeadeR,
    },
    props: {},
    data() {
      return {
          login_adv:"https://x.dscmall.cn/storage/data/afficheimg/1564453243544166182.jpg",
          info:{
              phone:'',
              password:'',
              inviter_id:0,
              password_comp:'',
              code:'',
              submit_disabled:false
          }
      };
    },
    watch: {},
    computed: {},
    methods: {
        // 发送短信
        send_sms:function(){
            if(this.$isEmpty(this.info.phone)){
                return this.$message.error('请先填写手机号码');
            }
            this.$post(this.$api.homeSendSms,{phone:this.info.phone,is_type:5}).then(res=>{
                if(res.code == 200){
                    return this.$message.success(res.msg);
                }else{
                    return this.$message.error(res.msg);
                }
            });
        },
        to_register:function(){
            if(this.$isEmpty(this.info.phone) || this.$isEmpty(this.info.password) || this.$isEmpty(this.info.password_comp) || this.$isEmpty(this.info.code)){
                return this.$message.error('请先认真填写完整');
            }
            if(this.info.password != this.info.password_comp){
                return this.$message.error('两次密码不相同');
            }
            this.submit_disabled=true;
            this.$post(this.$api.homeForgetPaasword,this.info).then(res=>{
                this.submit_disabled=false;
                if(res.code == 200){
                    this.$message.success(res.msg);
                    this.$router.push('/ceshi/login');
                }else{
                    return this.$message.error(res.msg);
                }
            });
        }

    },
    created() {

    },
    mounted() {}
};
</script>

<style lang="scss" scoped>
*{
    margin:0;
    padding:0;
}
.change_password{
    margin:0 auto;
    border-left:0px solid #DD18EB;
    border-right:0px solid #DD18EB;
    width:100%;
    //height:520px;
}

.change_password .el-main{
    margin-top:78px;
    height: auto;
    position: relative;
}

.change_password_main_top {
    background:-webkit-linear-gradient(left,#DD18EB,#F97D00);
    width:100%;
    height: 200px;
    display: block;
    position: absolute;
    z-index: -1;
    min-width: 1200px;
}

.change_password_main_top .personage{
    font-size: 36px;
    color: #fff;
    width:1200px;
    margin:auto;
    margin-top: 45px;
    font-weight: bold;
}
.change_password_main_bottom{
    padding-top:110px;
    padding-bottom:80px;
}
.change_password_main_bottom .box-card{
    width:1200px;
    height: 562px;
    margin:auto;
    z-index: 999;
}

.change_password_main_bottom .box-card-title{
    font-size: 32px;
    line-height:33px;
    color: #F20E0E;
    // margin:56px 472px 45px 472px ;
}

.change_password_main_bottom .el-form-item{
    // margin-left:-30px;
}

.change_password_main_bottom .el-button--small{
    margin-top:15px;
    width:394px;
    height: 60px;
    border-radius:30px;
    // margin-left: 320px;
    font-size: 20px;
    background:-webkit-linear-gradient(left,#DD18EB,#F97D00);
    border: 0px solid #DCDFE6;
}

.change_password .box-card-bottom{
    margin-top:30px;
    font-size: 14px;
}


.change_password .box-card-bottom .card-left-font{
    margin-left: 76px;
}

.change_password .box-card-bottom .card-middle-font{
    margin-left:8px;
}
.change_password .box-card-bottom .card-middle-font a{
    color:#F20E0E;
}
.change_password .box-card-bottom .card-right-font{
    margin-left:175px;
}
</style>

<style>
/* inout输入框修改 */
.change_password .change_password_main_bottom .el-input__inner{
    border-radius:10px;
    width:394px;
    height: 55px;
}
.change_password .box-card-top p{
    text-align: center;
    padding:50px 0 40px 0;
}
.change_password .demo-ruleForm{
    margin:auto;
    width:394px;

    text-align: center;
}
.change_password .codeclass{
    position: relative;
}

.change_password .codeclass .send{
    background: unset;
    color:#F69414;
    width: auto;
    margin-left: 0;
    border: none;
    position: absolute;
    font-size:14px;
    right:20px;
    top:10px;
    cursor: pointer;
}
.change_password .el-card__body{
    padding: 0;
}
.el-main{
    overflow: unset;
    min-width: 1200px;
}
</style>
