<template>

       <div class="haeder">
           <div class="header-top"  style="width:1200px;margin:auto;overflow: auto;">
            <el-row style="width:1200px;margin:auto;" >
                <el-col :span="12">
                    <div class="header-left">
                        <h1 class="header-left-h">云书展Logo</h1>
                    </div>
                </el-col>

                <el-col :span="12">
                    <div class="header-right" style="margin-top:10px;">


                        <span v-if="isLogin">
                            <span style="color:#F20E0E;">{{yMobile(user_info.nickname)}},你好！欢迎来到云书展平台&nbsp;</span>
                            <span><router-link  style="color:#747474" to="/ceshi/personal_center">&nbsp;个人中心&nbsp;|</router-link></span>
                            <span style="color:#F20E0E;cursor: pointer;"  @click="logout()">退出</span>
                        </span>
                        <span  v-if="!isLogin">
                            <span style="color:#F20E0E;">你好！欢迎来到云书展平台&nbsp;</span>
                            <span><router-link style="color:#747474" to="/ceshi/login">&nbsp;登录&nbsp;|</router-link></span>
                            <span><router-link style="color:#747474" to="/ceshi/register">免费注册</router-link></span>
                        </span>
                    </div>
                </el-col>
            </el-row>

           </div>
                        <div class="line">

                            <el-row :gutter="20" >
                                <el-col :span="6" :offset="7">
                                    <div class="line-left">
                                    <el-menu
                                        :default-active="activeIndex"
                                        class="el-menu-demo"
                                        mode="horizontal"
                                        @select="handleSelect"
                                        @click="handleSelect"
                                        text-color="#747474"
                                        active-text-color="#F20E0E">
                                        <el-menu-item index="ceshi_index">
                                            首页
                                        </el-menu-item>
                                        <el-menu-item index="ceshi_exhibition_center">
                                           展厅预览
                                        </el-menu-item>
                                        <el-menu-item index="ceshi_activity">
                                           活动
                                        </el-menu-item>
                                        <el-menu-item index="ceshi_book_live">
                                            线上直播
                                        </el-menu-item>
                                    </el-menu>
                                    </div>
                                </el-col>

                                <el-col :span="4" style="float:right;">
                                    <div class="line-right">
                                    <el-input
                                        class="feader-line-right-input"
                                        placeholder="搜索书籍"
                                        prefix-icon="el-icon-search"
                                        v-model="keywords"  @keydown.enter.native="to_search"
                                        >
                                    </el-input>
                                     </div>
                                </el-col>

                            </el-row>
                        </div>


    </div>
</template>

<script>
export default {
    data(){
        return{
            activeIndex: '',
            input:'',
            isLogin:false,
            user_info:{},
            keywords:''
        }
    },
    created() {
        let user_info = localStorage.getItem('user_info');
        if(!this.$isEmpty(user_info)){
            this.isLogin = true;
            this.user_info = JSON.parse(user_info);
        }



        if(this.$route.name == 'home'){
            this.activeIndex='ceshi_index';
        }else{
            this.activeIndex=this.$route.name;
        }
    },
    methods:{
         // 子组件执行搜索
        to_search:function(e){
            var keywords = encodeURI(encodeURI(this.keywords));

            window.location.href='/ceshi/search_book?keywords='+keywords;
            //this.$router.push('/ceshi/search_book?keywords='+keywords);
        },
        handleSelect(key, keyPath) {
            console.log(key, keyPath);

            this.$router.push({ name: key });

        },
        logout:function(){
            // this.$get(this.$api.homeLogout).then(()=>{
            //     // console.log(res);
            //     localStorage.removeItem('user_info');
            //     localStorage.removeItem('token');
            //     this.$router.push('/ceshi/login');

            // });
            localStorage.removeItem('user_info');
                localStorage.removeItem('token');
                this.$router.push('/ceshi/login');
        },
         yMobile:function(cellValue) {
            if (Number(cellValue) && String(cellValue).length === 11) {
                var mobile = String(cellValue)

                var reg = /^(\d{3})\d{4}(\d{4})$/

                return mobile.replace(reg, '$1****$2')

            } else {
                return cellValue

            }
        }
    }
}
</script>
<style lang="scss" scoped>
 *{
        padding: 0;
        margin: 0;
}
.haeder{
    margin: 0 auto;
    width:100%;
    height:135px;
    border:0px solid #F20E0E;
}

.line{
    height: 50px;
    width:1200px;
    margin:auto;
}

.line .el-menu-item{
    margin-left:0;
    margin-right:55px;
    font-size: 18px;
    height:50px;
    width:80px;
    text-align: center;
}
.line .el-menu-item:hover{
    background-color:#ffffff!important;


}
.el-menu-demo{
    font-size:16px;
}

.header-left{
    //margin-left:360px;
    width:225px;
    height:85px;
    background:-webkit-linear-gradient(left,#DD18EB,#F97D00);
}


.header-top .el-col-offset-18{
    margin-left:806px;
}

.header-left-h{
    width:225px;
    color:white;
    font-size: 31px;
    text-align: center;
    line-height: 85px;

}
.header-right{
    float: right;
}
.header-right span{
    width:168px;
    height: 19px;
    color:#747474;
    font-size: 14px;
    text-align: right;
    line-height: 30px;
}

.el-col-offset-0{
    margin-left:400px;
}

.line .el-col-offset-7{
    margin-left:329px;
}

.line .el-col-offset-4{
    margin-left: 164px;
}

//穿透样式

.el-input--small .el-input__inner{
    height: 26px;
}
.line .el-menu{
    height:50px;
    width:680px;
    background-color:unset;
}

.el-menu-demo{
    font-size: 14px;
}

.line-left{

}
.el-menu.el-menu--horizontal {
    border-bottom: solid 0px #e6e6e6;
}
.line-right{
    float: right;
    margin-top:5px;
}

.el-menu--horizontal>.el-menu-item.is-active{
    font-weight: bold;
}
</style>


<style scoped>
/* 不懂为什么lang="scss"会限制修改input的样式 */
.feader-line-right-input>>>.el-input__inner {
  border-radius:30px;
  width:255px;
  height: 40px;
  color:#000;
}
.el-main{
    overflow: unset;
    min-width: 1200px;
}
</style>

