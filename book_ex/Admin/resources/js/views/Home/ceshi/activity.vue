<template>
    <div class="activity">
        <el-container direction=" horizontal">
            <el-header><heade-r></heade-r></el-header>
            <el-main>
                <div class="activity-main">
                    <div class="activity-main-top">
                        <h1 class="ach">活动</h1>
                    </div>
                    <div class="activity-main-card">
                        <el-card class="box-card">
                            <div v-for="(item,i) in list" :key="i" class="text-item">
                                <router-link :to="'/ceshi/exhibition_center?id='+item.id">
                                    <h1 style="font-size:30px; color:#333333; margin:10px 0;">{{item.name}}</h1>
                                    <div style="width:100%;height:1px;background:#eeeeee"></div>
                                    <span class="cuIcon-act_time"></span><span>{{formatDate(item.start_time)}} 至 {{formatDate(item.end_time)}}</span><br>
                                    <span class="cuIcon-act_addr"></span><span>{{item.hall}}</span><br>
                                    <span class="cuIcon-act_chper"></span><span>{{item.guest}}</span><br>
                                    <span class="cuIcon-act_press"></span><span>{{item.sponsor}}</span><br>
                                </router-link>
                            </div>
                            <div class="activity-card-footer">
                                <div class="home_fy_block  width_center_1200">
                                <el-pagination @current-change="current_change" background layout="prev, pager, next,jumper,total" :total="total_data" :page-size="page_size" :current-page="current_page"></el-pagination>
                                </div>
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
            list:[],
            //分页数据
            goods_list:[],
            banner_list:[],
            total_data:0, // 总条数
            page_size:30,
            current_page:1
       }
    },
    created() {
        this.get_list();
    },
    methods:{
         // 分页改变
        current_change:function(e){
            this.current_page = e;
            this.get_list();
        },
        // 获取活动信息
        get_list:function(){

            this.$post(this.$api.homeGetSeckillList,{page:this.current_page,pageSize:this.page_size}).then(res=>{
                this.list=res.data.data;
                this.page_size = res.data.per_page;
                this.total_data = res.data.total;
                this.current_page = res.data.current_page;
            });
        },
        //时间戳转换方法    date:时间戳数字
        formatDate:function(num,type=1) {

            let date = new Date(num*1000);
            console.log(date);
            //时间戳为10位需*1000，时间戳为13位的话不需乘1000
            let y = date.getFullYear();

            let MM = date.getMonth() + 1;

            MM = MM < 10 ? ('0' + MM) : MM;//月补0

            let d = date.getDate();

            d = d < 10 ? ('0' + d) : d;//天补0

            let h = date.getHours();

            h = h < 10 ? ('0' + h) : h;//小时补0

            let m = date.getMinutes();

            m = m < 10 ? ('0' + m) : m;//分钟补0

            let s = date.getSeconds();

            s = s < 10 ? ('0' + s) : s;//秒补0
            if(type==0){
                return y + '-' + MM + '-' + d + ' ' + h + ':' + m;
            }else if(type==1){
                return y + '-' + MM + '-' + d;
            }

        }
    }
}
</script>

<style lang="scss" scoped>
*{
    margin:0;
    padding:0;
}

// 卡片居中
.activity{
    width:100%;
    height:100%;
}
.activity-main-top .ach{
    width:1200px;
    margin:auto;
    color:#fff;
    font-size: 36px;
    padding-top:45px;
 }

.activity-main-card .is-always-shadow{
    margin: 0 auto;
    box-shadow:unset;
    border: 1px solid #eee;
}
.activity{
    margin: 0 auto;
}

.el-main{
    margin: 0 auto;
    width:100%;
    height:100%;
    margin-bottom:80px;
}

.activity-main{
    margin-top:80px;
}
.activity-main-top{
    z-index:-1;
    width:100%;
    height:200px;
    position: absolute;
    background:-webkit-linear-gradient(left,#DD18EB,#F97D00);
    min-width: 1200px;
}

.activity-main-card{
    padding-top:120px;
    z-index:2;
}
//卡片区域的样式
  .text {
    font-size: 14px;
  }

  .text-item{
    padding-top:17px;
    // padding-left:24px;
  }

  .cuIcon-act_addr{
	display: inline-block;
	width:15px;
	height:15px;
	background-size:100% 100%;
	background-image: url('/dist/images/ysz_act_addr.png');
}
.cuIcon-act_chper{
	display: inline-block;
	width:15px;
	height:15px;
	background-size:100% 100%;
	background-image: url('/dist/images/ysz_act_chper.png');
}
.cuIcon-act_press{
	display: inline-block;
	width:15px;
	height:15px;
	background-size:100% 100%;
	background-image: url('/dist/images/ysz_act_press.png');
}
.cuIcon-act_time{
	display: inline-block;
	width:15px;
	height:15px;
	background-size:100% 100%;
	background-image: url('/dist/images/ysz_act_time.png');
}

.text-item img{
    width:14px;
    height:auto;
}
 .box-card span{
     font-size:16px;
     color:#747474 ;
     margin-left:8px;
     margin-top:8px;
 }

 .box-card .h1{
     font-size:30px;
     color:#333333;
 }

  .box-card {
    width:1200px;
  }

//分页样式居中设置
.activity .activity-card-footer{
    margin-top: 50px;
    margin-bottom: 50px;
    height: 52px;
    width: 1200px;
    vertical-align:middle;
    text-align: center;

}
.activity .home_fy_block{
    width: 1000px;
    height: 50px;
   display:inline-block;
}

</style>

<style>
/* scoped限制了对原有element组件样式的修改
但在这里设置样式会对后续子组件产生影响，命名应严格 */

/* 穿透修改了原生element-ui分页组件的样式 */
.activity .home_fy_block .el-pagination.is-background .el-pager li:not(.disabled).active{
    background-color: #F20E0E;
}
.activity .home_fy_block .el-pagination .btn-prev{
    width: 50px;
    height: 50px;
    line-height: 50px;
}
.activity .home_fy_block .el-pagination .btn-next{
    width: 50px;
    height: 50px;
    line-height: 50px;
}
.activity  .home_fy_block .el-pagination .el-pager li{
    width: 50px;
    height: 50px;
    line-height: 50px;
    color: #747474;
    font-size: 20px;
}

.activity .home_fy_block .is-in-pagination{
    width: 74px;
    height: 40px;
    line-height: 40px;
    font-size: 20px;
}
.activity .home_fy_block .el-pagination__editor.el-input .el-input__inner{
     height: 50px;
    line-height: 40px;
}
.activity .home_fy_block .el-pagination__total{
    line-height: 50px;
    height: 50px;
}
.activity .home_fy_block .is-in-pagination text{
    font-size: 16px;
}
.activity .home_fy_block .el-pagination span:not([class*=suffix]){
    margin-left: 5px;
    font-size: 16px;
    vertical-align:sub;
}
.el-card{
    border: 0px solid #EBEEF5;
}

.el-card__body{
    padding:10px 40px;
}

.el-pagination.is-background .btn-next, .el-pagination.is-background .btn-prev, .el-pagination.is-background .el-pager li{
    border-radius:0;
}
.el-input__inner{
    border-radius:0;
}

.el-main{
    overflow: unset;
    min-width: 1200px;
}
</style>


