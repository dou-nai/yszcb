<template>
    <div class="login">
        <el-container>
        <el-header><heade-r></heade-r></el-header>
        <el-main>
            <div>
                <div class="login_main_top">
                    <img src="/dist/images/pc/login_icon.png" class="login_background">
                </div>
                <div class="login_main_bottom">
                    <el-card class="box-card">
                        <div class="box-card-top">
                            <p class="box-card-title">云书展·登录</p>
                        </div>
                        <div class="box-card-main">
                            <el-form label-width="0" class="demo-ruleForm">
                                <el-form-item  prop="user_name" >
                                    <el-input type="text" @keyup.enter.native="to_login" v-model="info.phone" placeholder="请输入账号"></el-input>
                                </el-form-item>
                                <el-form-item  prop="password">
                                    <el-input  type="password" @keyup.enter.native="to_login" v-model="info.password" placeholder="请输入密码"></el-input>
                                </el-form-item>
                                <el-form-item>
                                    <el-button type="primary" v-if="!submit_disabled" @click="to_login">登录</el-button>
                                    <el-button type="primary" v-if="submit_disabled" disabled>登录</el-button>
                                </el-form-item>
                            </el-form>
                        </div>
                        <div class="box-card-bottom">
                            <span class="card-left-font">没有账号？</span>
                            <span class="card-middle-font"><router-link :to="'/ceshi/register'">立即注册</router-link></span>
                            <span class="card-right-font"><router-link :to="'/ceshi/forget_password'">忘记密码</router-link></span>
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
        HeadeR
    },
    data(){
        return{
            login_adv:"",
            error_num:0,
            info:{
                phone:'',
                password:'',
                code:'',
            },
            oauth:{},
            //表单数据绑定对象
            login_model:{
                user_naem:'',
                password: ''
            },
            //表单验证对象
            login_res: {
                user_name: [
                    { required: true, message: '请输入用户名', trigger: 'blur' },
                    { min: 3, max: 5, message: '长度在 3 到 5 个字符', trigger: 'blur' }
                ],
                password: [
                    { required: true, message: '请输入密码', trigger: 'blur' },
                    { min: 3, max: 5, message: '长度在 3 到 5 个字符', trigger: 'blur' }
                ],
            },
            submit_disabled:false
        }
    },
     created() {
        this.error_num = this.get_error_num(); // 获取登录失败次数;
        this.get_oauth_config();
        // console.log(localStorage.getItem('user_info'));
    },
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
        to_login:function(){
            this.error_num = this.get_error_num();
            if(this.$isEmpty(this.info.phone)){
                return this.$message.error('账号不能为空');
            }
            if(this.$isEmpty(this.info.password)){
                return this.$message.error('密码不能为空');
            }

            if(this.error_num>=5 && this.$isEmpty(this.info.code)){
                return this.$message.error('请填写短信验证码');
            }
            this.submit_disabled=true;
            this.$post(this.$api.homeLogin,this.info).then(res=>{
                this.submit_disabled=false;
                if(res.code == 500){
                    localStorage.setItem('login_error_num',parseInt(this.error_num)+1);
                    return this.$message.error(res.msg);
                }else{
                    this.$message.success('登录成功');
                    localStorage.setItem('token',res.token);
                    localStorage.setItem('user_info',JSON.stringify(res.user_info));
                    localStorage.removeItem('login_error_num');

                    if(!this.$isEmpty(res.user_info.like_cate_ids)){
                        this.$router.push('/ceshi/index');
                    }else{
                        this.$router.push('/ceshi/recommend_tags');
                    }

                }
            });
        },
        // 获取本地存储登录失败次数
        get_error_num:function(){
            let error_num = localStorage.getItem('login_error_num');
            if(this.$isEmpty(error_num)){
                error_num = 0;
            }
            return error_num;
        },
        wechat_login:function(){
            location.href="https://open.weixin.qq.com/connect/qrconnect?appid="+this.oauth.appid+"&redirect_uri=%2fuser%2fwechat_login&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect"
        },
        // 获取配置数据
        get_oauth_config:function(){
            this.$get(this.$api.homeGetOauthConfig).then(res=>{
                this.oauth = res.data;
            });
        }
    },
}
</script>

<style lang="scss" scoped>
*{
    margin:0;
    padding:0;
}
.login{
    margin:0 auto;
    border-left:0px solid #DD18EB;
    border-right:0px solid #DD18EB;
    width:100%;
    //height:520px;
}

.login .el-main{
    margin-top:78px;
    height: 738px;
    background-color:#F6F9F8;
}

.login_main_top .login_background{
    width:100%;
    height: 520px;
    display: block;
    position: absolute;
    z-index: 5;
    min-width: 1200px;
}

.login_main_bottom{
    width:1200px;
    margin:auto;
    position: relative;
}
.login_main_bottom .box-card{
    width:546px;
    height: auto;
    //margin:108px 321px 0 1053px;
    position: absolute;
    z-index: 999;
    right:0;
    top:100px;
    padding-bottom:30px;
}

.login_main_bottom .box-card-title{
    font-size: 32px;
    width:192px;
    line-height:33px;
    color: #F20E0E;
    margin:auto;
    margin:63px auto;
}

.login_main_bottom .el-form-item{
    text-align:center;
}

.login_main_bottom .el-button--small{
    margin-top:35px;
    width:394px;
    height: 60px;
    border-radius:30px;
    font-size: 20px;
    background:-webkit-linear-gradient(left,#DD18EB,#F97D00);
    border: 0px solid #DCDFE6;
}

.login .box-card-bottom{
    margin-top:30px;
    font-size: 14px;
    padding:0 60px;
}


.login .box-card-bottom .card-left-font{
    // margin-left: 76px;
    color:#B2B2B2;
    font-size:14px;
    float: left;
}

.login .box-card-bottom .card-middle-font{
    margin-left:4px;
}
.login .box-card-bottom .card-middle-font a{
    color:#F20E0E;
}
.login .box-card-bottom .card-right-font{
    // margin-left:175px;
    float: right;
}
.login .box-card-bottom .card-right-font a{
    color:#B2B2B2;
    font-size:14px;
}

.login .el-card__body{
    padding: 0;
}
.login .box-card-top p{
    padding:0;
}
</style>

<style>
/* inout输入框修改 */
.login_main_bottom .el-input__inner{
    border-radius:10px;
    width:394px;
    height: 55px;
    margin-left:0px;
}
.el-form-item__content{
    margin-left:0;
}
.el-main{
    overflow: unset;
    min-width: 1200px;
}
</style>
