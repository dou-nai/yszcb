<template>
    <div class="exhibition_center">
        <el-container>
        <el-header><heade-r></heade-r></el-header>
        <el-main>
            <div class="center_main_top">
                <p>展厅导览</p>
            </div>
            <div class="center_main_bottom">
                <!-- 头部卡片 -->
                <el-card class="center-box-card">
                <div class="itemns">
                    <p class="max_title">
                        {{ info.name }}
                    </p>
                    <p class="mini_title">
                        {{ info.sponsor }}
                    </p>
                    <div class="center_info">
                        <div class="neir" v-html="info.content">

                        </div>


                        <div class="center_btn2">
                            <button class="center_btnb">展会预览</button>
                        </div>
                    </div>

                </div>


                <!-- 中间卡片 -->
                <div class="center_middle_card" v-if="center_info[0]">
                    <div>
                        <div class="title_1" v-for="(item,i) in center_info" @click="act_change(i)" :key="i" :class="act==i?'examPaper-header':''">{{item.name}}</div>

                    </div>
                    <a href="javascript:;">

                        <img :src="center_info[act].thumb" class="center_middle_image">
                    </a>
                </div>

                <!-- 底部卡片 -->
                <p class="centercard_bottom_title">出版社名录</p>
                <p class="press_num">{{total_data}}家</p>
                <div v-for="(value,i) in center_bootm_info" :key="i" class="center_bottom_card">
                    <router-link :to="'/ceshi/book_press?id='+value.goods.id">
                        <span class="center_id">{{i+1}}：</span>
                        <span class="center_press_name">{{value.goods.store_name}}</span>
                    </router-link>
                </div>

                <!-- 分页结构 -->
                <div class="activity-card-footer">
                        <div class="home_fy_block  width_center_1200">
                             <el-pagination @current-change="current_change" background layout="prev, pager, next,jumper,total" :total="total_data" :page-size="page_size" :current-page="current_page"></el-pagination>
                        </div>
                </div>
                </el-card>
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
            //中部卡片信息
            center_info:[],
            //底部卡片信息
            center_bootm_info:[],
            info:[],
            //分页数据
            goods_list:[],
            banner_list:[],
            total_data:0, // 总条数
            page_size:30,
            current_page:1,
            act:0,
            id:0
        }
    },
    created() {
        this.id = this.$route.query.id;
        if(this.$isEmpty(this.id)){
          this.id=0;
        }

        this.get_info();
    },
    methods:{
        // 获取活动信息
        get_info:function(){
            this.$post(this.$api.homeGetSeckillDetail,{id:this.id,page:this.current_page,pageSize:this.page_size}).then(res=>{
                this.info=res.data.seckill_info;
                this.center_bootm_info=res.data.seckill_goods.data;
                this.center_info=res.data.seckill_brands;

                this.page_size = res.data.seckill_goods.per_page;
                this.total_data = res.data.seckill_goods.total;
                this.current_page = res.data.seckill_goods.current_page;

            });
        },
          // 分页改变
        current_change:function(e){
            this.current_page = e;
            this.get_list();
        },
        // 分页改变
        act_change:function(e){
            this.act = e;
        },
    }
}
</script>

<style lang="scss" scoped>
*{
    margin: 0;
    padding: 0;
}
.el-main{
    margin: 0 auto;
    margin-top: 78px;
    height: 100%;
    width: 100%;
    margin-bottom:80px;
}
.center_main_top{
    width:100%;
    height: 200px;
    position: absolute;
    z-index: -1;
    background:-webkit-linear-gradient(left,#DD18EB,#F97D00);
    min-width: 1200px;
}
.center_main_top p{
    font-size: 36px;
    width:1200px;
    margin: auto;
    padding-top: 45px;
    color:#FFFFFF;
    font-weight: bold;
}

//下方
.center_main_bottom{
  z-index: 99;
  margin-top:120px;
  position: relative;
}
.center-box-card{
    margin: auto;
    z-index: 999;
    width:1200px;
}

.max_title{

    font-size: 40px;
    color: #F20E0E;
    font-weight: bold;

}
.mini_title{

    font-size: 20px;
    color:#B2B2B2;
}
.center_info{
    margin-top: 30px;
    line-height: 30px;
    text-emphasis: none;
    width: 100%;
    min-height: 100px;
    font-size: 16px;
    border: 1px solid #FFA86E;
    background-color:#fef7ee    ;
    color: #333333;
    position: relative;
}
.center_info .neir{

    padding: 30px 10px 30px 10px;

}
.center_btnb{
    // position: absolute;
    // z-index: 9999999;
    // float: right;
    color: #fff;
    font-size: 20px;
    border-radius:20px;
    width: 123px;
    border: none;
    height: 41px;
    background:-webkit-linear-gradient(top,#DD18EB,#F97D00);
}
// .itemns{
//     position: relative;
//     //z-index: 200;
// }
.center_btn2{
    position: absolute;;
    top:-20px;
    right:-20px;
}

//中间卡片样式
.center_middle_card{
    width: 100%;
    height: 100%;
    margin-top:20px;

}
.center_middle_card span{
    font-size: 30px;
    color: #333333;
}

.center_middle_image{
    margin-top: 20px;
    width: 100%;
    height: auto;
}

//底部卡片样式
.center_bottom_card {
    margin-top: 20px;
    margin-left: 10px;
    font-size: 20px;
    display:inline-block;
    width:32%;
    line-height: 60px;
    height: 60px;
    overflow: hidden;/*内容超出后隐藏*/
    text-overflow: ellipsis;/* 超出内容显示为省略号*/
    white-space: nowrap;/*文本不进行换行*/
}
.center_bottom_card .center_id{
    margin-left:10px;
}
.centercard_bottom_title{
    margin-top:20px;
    font-size: 40px;
    color: #F20E0E;
    font-weight: bold;
}
.press_num{
    margin-top:10px ;
    font-size: 20px;

    color:#B2B2B2;
}

//分页样式
.exhibition_center .activity-card-footer{
    padding-top: 50px;
    padding-bottom: 50px;
    height: 52px;
    width: 1200px;
    vertical-align:middle;
    text-align: center;

}
.exhibition_center .home_fy_block{
    width: 1000px;
    height: 50px;
   display:inline-block;
}
</style>

<style>
/* 穿透修改了原生element-ui分页组件的样式 */
.exhibition_center .home_fy_block .el-pagination.is-background .el-pager li:not(.disabled).active{
    background-color: #F20E0E;
}
.exhibition_center .home_fy_block .el-pagination .btn-prev{
    width: 50px;
    height: 50px;
    line-height: 50px;
}
.exhibition_center .home_fy_block .el-pagination .btn-next{
    width: 50px;
    height: 50px;
    line-height: 50px;
}
.exhibition_center  .home_fy_block .el-pagination .el-pager li{
    width: 50px;
    height: 50px;
    line-height: 50px;
    color: #747474;
    font-size: 20px;
}

.exhibition_center .home_fy_block .is-in-pagination{
    width: 74px;
    height: 40px;
    line-height: 40px;
    font-size: 20px;
}
.exhibition_center .home_fy_block .el-pagination__editor.el-input .el-input__inner{
     height: 50px;
    line-height: 40px;
}
.exhibition_center .home_fy_block .el-pagination__total{
    line-height: 50px;
    height: 50px;
}
.exhibition_center .home_fy_block .is-in-pagination text{
    font-size: 16px;
}
.exhibition_center .home_fy_block .el-pagination span:not([class*=suffix]){
    margin-left: 5px;
    font-size: 16px;
    vertical-align:sub;
}
.el-card.is-always-shadow{
    box-shadow:unset;
    border: 1px solid #eee;
}

.el-card__body{
    padding:20px 40px;
}
.title_1{
    display: inline-block;
    color:#B2B2B2;
    font-size: 24px;
    margin-right:20px;
    font-weight: unset;
    cursor:pointer;
}
.examPaper-header{
    display: inline-block;
    color:#333333;
    font-weight: Bold;
    font-size: 30px;
    margin-right:20px;
}
.examPaper-header::after {
    content: '';
	width:34px;
	height: 7px;
	display: block;
	margin: 0 auto;
	border-radius: 10px;
	background-image: linear-gradient(62deg, rgba(221, 24, 235, 1), rgba(249, 125, 0, 1));
}
.el-main{
    overflow: unset;
    min-width: 1200px;
}
</style>
