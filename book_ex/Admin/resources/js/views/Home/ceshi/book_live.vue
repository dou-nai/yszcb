<template>
    <div class="book_live">
        <el-container>
        <el-header><heade-r></heade-r></el-header>
        <el-main>
            <div class="book_live_top">
                <p class="book_live_top_p">线上直播</p>
            </div>
            <div class="book_live_bottom">

                <div class="book_live_card">
                    <div class="btbspan-below-mleft" v-if="live[0]">
                        <img :src="live[0].goods_master_image" class="image_max">
                        <span class="onlive">直播中</span>
                        <div class="onlive_title">
                            <p class="cont">{{ live[0].goods_name }}</p>
                        </div>
                    </div>
                    <div class="center_div">
                        <el-row>
                        <el-col class="btbspan-below-mright" :span="8" v-for="(value, index) in live" v-show="index>0" :key="value" :offset="index > 0 ? 2 : 0">
                            <el-card :body-style="{ padding: '0px' }">
                            <img :src="value.goods_master_image" class="image_mini">
                            <span class="onlook">回放</span>
                            <div style="padding: 14px;">
                                <p class="live_brief">{{value.goods_name}}</p>
                                <p class="live_anchor">主播：{{value.live_name}}</p>
                            </div>
                            </el-card>
                        </el-col>
                        </el-row>

                        <div class="book_live_pagination">

                            <div class="home_fy_block  width_center_1200">
                            <el-pagination @current-change="current_change" background layout="prev, pager, next,jumper,total" :total="total_data" :page-size="page_size" :current-page="current_page"></el-pagination>
                            </div>

                        </div>
                    </div>
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
            live:[],
            //分页数据
            goods_list:[],
            banner_list:[],
            total_data:0, // 总条数
            page_size:40,
            current_page:1,
        }
    },
    created() {
        this.get_zhibo_info();
    },
    methods:{
        // 获取直播信息
        get_zhibo_info:function(){
            this.$get(this.$api.homeSearchIntegralGoods,{page:this.current_page,pageSize:this.page_size}).then(res=>{
                this.live=res.data.data;
                this.page_size = res.data.per_page;
                this.total_data = res.data.total;
                this.current_page = res.data.current_page;
            });
        },
          // 分页改变
        current_change:function(e){
            this.current_page = e;
            this.get_zhibo_info();
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
    margin: 0 auto ;
    margin-top: 78px;
    width:100%;
    height: 100%;
    overflow: unset;
    margin-bottom:80px;
}
.book_live_top{
    position: absolute;
    z-index: -1;
    width:100%;
    height: 200px;
    height: 200px;
    background:-webkit-linear-gradient(left,#DD18EB,#F97D00);
    min-width: 1200px;
}
.book_live_top_p{
    font-size: 36px;
    color: #fff;
    padding-top: 45px;
    width:1200px;
    margin:auto;
    font-weight: bold;
}
.book_live_bottom{
    z-index: 99;
    margin-top:120px;
}
.book_live_card{
    z-index: 999;
    width: 1200px;
    margin: 0 auto;
}
.live_brief{
    font-size: 16px;
    color: #333333;
}
.live_anchor{
    font-size: 14px;
    color: #B2B2B2;
}
.book_live_card .el-row{
    margin-left: 25px;
}
.image_mini{
    //margin-bottom: 10px;
    width: 210px;
    height: 120px;
}
.book_live_card .el-col{
    width: 210px;
    margin-left: 10px;
    margin-right: 10px;
    margin-top: 5px;
}
.image_max{
    margin: 0 auto;
    width: 1200px;
    height: 520px;
}

//分页样式
.book_live_pagination{
    z-index: 9999;
    width:1200px;
    text-align: center;
    margin:auto;
    margin-top:50px;
    margin-bottom:30px;
}

.book_live .home_fy_block{
   width: 1000px;
    height: 50px;
   display:inline-block;
}
.book_live_pagination .my-btn{
    font-size: 16px;
    margin: 30px;
}
.btbspan-below-mleft{
    position: relative;
}

.btbspan-below-mleft .onlive{
      font-size:14px;
      color: #FFFFFF;
      min-width:24px;
      height:20px;
      background:-webkit-linear-gradient(left,#F20E0E,#FF8800);
      line-height:20px;
      padding:2px 4px;
      border-radius: 2px;
      position: absolute;
      left:20px;
      top:20px;
  }
.btbspan-below-mleft .onlive_title{
    position: absolute;
    bottom:0;
    font-size: 24px;
    font-weight: bold;
    color:#FFFFFF;
    width:100%;
    height:195px;
    background-image: linear-gradient(180deg, rgba(0,0,0,0), rgba(0,0,0,1));
}
.btbspan-below-mleft .onlive_title p{
    padding-top: 150px;
    padding-left:20px;
    padding-right:20px;
    width:unset;
}
.btbspan-below-mright{
    position: relative;
}
.btbspan-below-mright .onlook{
      font-size:14px;
      color: #FFFFFF;
      min-width:24px;
      height:19px;
      background:-webkit-linear-gradient(left,#DD18EB,#F97D00);
      line-height:19px;
      padding:1px 2px;
      border-radius: 2px;
      position: absolute;
      left:8px;
      top:8px;
  }
.cont{

    overflow: hidden;/*内容超出后隐藏*/

    text-overflow: ellipsis;/* 超出内容显示为省略号*/

    white-space: nowrap;/*文本不进行换行*/

}
.center_div{
    box-shadow:unset;
    border: 1px solid #eee;
    padding-top:20px;
    padding-bottom:40px;
}
</style>
<style>
/* scoped限制了对原有element组件样式的修改
但在这里设置样式会对后续子组件产生影响 */

.book_live .home_fy_block .el-pagination.is-background .el-pager li:not(.disabled).active{
    background-color: #F20E0E;
}
.book_live .home_fy_block .el-pagination .btn-prev{
    width: 50px;
    height: 50px;
    line-height: 50px;
}
.book_live .home_fy_block .el-pagination .btn-next{
    width: 50px;
    height: 50px;
    line-height: 50px;
}
.book_live  .home_fy_block .el-pagination .el-pager li{
    width: 50px;
    height: 50px;
    line-height: 50px;
    color: #747474;
    font-size: 20px;
}

.book_live .home_fy_block .is-in-pagination{
    width: 74px;
    height: 40px;
    line-height: 40px;
    font-size: 20px;
}
.book_live .home_fy_block .el-pagination__editor.el-input .el-input__inner{
     height: 50px;
    line-height: 40px;
}
.book_live .home_fy_block .el-pagination__total{
    line-height: 50px;
    height: 50px;
}
.book_live .home_fy_block .is-in-pagination text{
    font-size: 16px;
}
.book_live .home_fy_block .el-pagination span:not([class*=suffix]){
    margin-left: 5px;
    font-size: 16px;
    vertical-align:sub;
}
.el-card.is-always-shadow{
    box-shadow:unset;
}
.el-card{
    border: 0px solid #EBEEF5;
}

.el-card.is-always-shadow:hover{
    box-shadow: 0 2px 12px 0 rgba(0,0,0,.1);
}
.el-main{
    overflow: unset;
    min-width: 1200px;
}
</style>
