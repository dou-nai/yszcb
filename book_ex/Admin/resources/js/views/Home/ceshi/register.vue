<template>
    <div class="register">
        <el-container>
        <el-header><heade-r></heade-r></el-header>
        <el-main>
            <div>
                <div class="register_main_top">
                    <img src="/dist/images/pc/login_icon.png" class="login_background">
                </div>
                <div class="register_main_bottom">
                    <el-card class="box-card">
                        <div class="box-card-top">
                            <p class="box-card-title">云书展·注册</p>
                        </div>
                        <div class="box-card-main">
                            <el-form label-width="0" class="demo-ruleForm">
                                <el-form-item >
                                    <el-input v-model="info.phone" placeholder="请输入手机号"></el-input>
                                </el-form-item>
                                <el-form-item class="codeclass">
                                    <el-input class="yzm" v-model="info.code" placeholder="请输入手机验证码"></el-input>

                                    <span class="send" @click="send_sms()">获取验证码 </span>
                                </el-form-item>
                                <el-form-item >
                                    <el-input type="password" v-model="info.password" placeholder="请输入密码"></el-input>
                                </el-form-item>
                                <el-form-item>
                                    <el-input type="password" v-model="info.password_comp" placeholder="请输入确认密码"></el-input>
                                </el-form-item>
                                <el-form-item>
                                    <el-button type="primary" v-if="!submit_disabled" @click="to_register">注册</el-button>
                                    <el-button type="primary" v-if="submit_disabled" disabled >注册</el-button>
                                </el-form-item>
                            </el-form>
                        </div>
                         <div class="box-card-bottom">
                            <span class="card-left-font">已有账号？</span>
                            <span class="card-middle-font"><router-link :to="'/ceshi/login'">立即登陆</router-link></span>
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
            this.$post(this.$api.homeSendSms,{phone:this.info.phone,is_type:2}).then(res=>{
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
            this.$post(this.$api.homeRegister,this.info).then(res=>{
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
        // 邀请人
        if(!this.$isEmpty(this.$route.query.inviter_id)){
            this.inviter_id = this.$route.query.inviter_id;
        }
    },
    mounted() {}
};
</script>

<style lang="scss" scoped>
*{
    margin:0;
    padding:0;
}
.register{
    margin:0 auto;
    border-left:0px solid #DD18EB;
    border-right:0px solid #DD18EB;
    width:100%;
    //height:520px;
}

.register .el-main{
    margin-top:78px;
    height: 768px;
    background-color:#F6F9F8;
}

.register_main_top .login_background{
    width:100%;
    height: 520px;
    display: block;
    position: absolute;
    z-index: 5;
    min-width: 1200px;
}
.register_main_bottom{
    width:1200px;
    margin:auto;
    position: relative;
}
.register_main_bottom .box-card{
    width:546px;
    height: auto;
    position: absolute;
    z-index: 999;
    right:0;
    top:100px;
    padding-bottom:30px;
}

.register_main_bottom .box-card-title{
    font-size: 32px;
    width:192px;
    line-height:33px;
    color: #F20E0E;
    margin:63px auto 63px auto ;
}

.register_main_bottom .el-form-item{
    // margin-left:-30px;
    text-align: center;
}

.register_main_bottom .el-button--small{
    margin-top:35px;
    width:380px;
    height: 60px;
    border-radius:30px;
    font-size: 20px;
    background:-webkit-linear-gradient(left,#DD18EB,#F97D00);
    border: 0px solid #DCDFE6;
}

.register .box-card-bottom{
    margin-top:18px;
    font-size: 14px;
    padding:0 90px;
}

.box-card-bottom .card-middle-font{
    margin-left:4px;
}
.box-card-bottom .card-middle-font a{
    color:#F20E0E;
}
.register .box-card-bottom .card-left-font{
    float: left;
    color:#B2B2B2;
    font-size:14px;
}


</style>

<style>
/* inout输入框修改 */
.register .register_main_bottom .el-input__inner{
    border-radius:20px;
    width:380px;
    height: 55px;
    margin-left:0px;
}

.register .codeclass{
    position: relative;
}

.register .codeclass .send{
    background: unset;
    color:#F69414;
    width: auto;
    margin-left: 0;
    border: none;
    position: absolute;
    font-size:14px;
    right:100px;
    top:10px;
    cursor: pointer;
}
.register .el-card__body{
    padding: 0;
}
.register .box-card-top p{
    padding:0;
}
.el-main{
    overflow: unset;
    min-width: 1200px;
}
</style>
