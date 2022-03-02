<template>
    <div class="search_book">
        <el-container>
            <el-header><heade-r></heade-r></el-header>
            <el-main>
                <div class="el-main-top">
                    <div class="abcde">
                    <span class="el-main-top-title">搜索"{{keywords||'全部'}}"的结果</span>
                    </div>
                </div>
                <div class="el-main-bottom">
                    <el-card class="box-card">
                    <div v-for="value in books_ieme" :key="value" class="text item">
                        <div style="padding:10px 20px;">
                         <router-link :to="'/ceshi/book_details?id='+value.id">
                            <div class="book_icons_di"><img :src="value.goods_master_image" class="book_icons"></div>
                            <p class="book_card_title">{{value.goods_name}}</p>
                            <p class="book_card_author">作者：{{value.author}}</p>
                            <p class="book_card_type" v-if="value.class">
                             <span class="s-p">{{value.class.name}}</span>
                            </p>
                         </router-link>
                        </div>
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
            books_ieme:[],
            //分页数据
            goods_list:[],
            banner_list:[],
            total_data:0, // 总条数
            page_size:30,
            current_page:1,
            keywords:''
        }
    },
    created() {

        var keywords = this.$route.query.keywords;


         if(!this.$isEmpty(keywords)){
            this.keywords =decodeURI(decodeURI(keywords,"UTF-8"));
        }

        this.get_list();
    },
    methods:{
         // 分页改变
        current_change:function(e){
            this.current_page = e;
            this.get_list();
        },
         // 获取列表信息
         get_list:function(){
            this.$get(this.$api.homeSearchGoods,{page:this.current_page,pageSize:this.page_size,keywords:this.keywords}).then(res=>{
                this.books_ieme=res.data.data;
                this.page_size = res.data.per_page;
                this.total_data = res.data.total;
                this.current_page = res.data.current_page;
            });
        },
    }
}
</script>

<style lang="scss" scoped>
*{
    margin:0;
    padding:0;
}
.el-container{
    display:block;
}
.search_book{
    margin: 0 auto;
    width:100%;
    // height: 1660px;
}
.search_book .el-main{
    margin: 0 auto;
    margin-top:78px;

}
.search_book .el-main-top{
    margin: 0 auto;
    width:100% ;
    height: 200px;
    background:-webkit-linear-gradient(left,#DD18EB,#F97D00);
}

.book_card_type .s-p{
      font-size:12px;
      color: #D464AB;
      min-width:24px;
      height:16px;
      border:1px solid #F1D7EC;
      background:#FFF2FA;
      line-height:16px;
      padding:2px 4px;
      border-radius: 2px;
  }
.el-main-bottom{
    width:100%;
    height: auto;
    margin-top:120px;
    margin-bottom:80px;
}
.el-main-top{
    position: absolute;
    z-index: -1;
}
.el-main-bottom .el-card{
    z-index: 999;
    width: 1200px;
    height: auto;
    margin:auto;
}

.abcde{
    width:1200px;
    padding-top:45px;
    margin:auto;
}

.el-main-bottom .item{
    width: 20%;
    height: 310px;
    display:inline-block;
}

.book_card_title{
    margin-top: 8px;
    margin-bottom: 8px;
    font-size: 16px;
}
.book_card_author{
    font-size: 12px;
    color: #B2B2B2;
    margin-bottom: 8px;
}
.book_card_type{
    font-size: 12px;
    color: #DCA117;
}
.book_icons{
    width:130px;
    height: 180px;
    margin: 0 auto;
}
// 搜索结构标题样式
.search_book .el-main-top-title{
    font-size: 36px;
    color: #fff;
    margin-left: 0;
    margin-top: 45px;
}

//分页样式
.search_book .activity-card-footer{
    padding-top: 10px;
    height: 52px;
    width: 1200px;
    display:table-cell;
    vertical-align:middle;
    text-align: center;

}
.search_book .home_fy_block{
    width: 1000px;
    height: 50px;
   display:inline-block;
}

.box-card.is-always-shadow{
    margin: 0 auto;
    box-shadow:unset;
    border: 1px solid #eee;
}
</style>

<style >
/* 原生card样式修改 */
.search_book .book_icons_di{
    width:130px;
    height: 180px;
    margin: 0 auto;
}
.el-main-bottom .el-card__body{
    padding:70px;
}

/* 页脚的样式 */
/* 穿透修改了原生element-ui分页组件的样式 */
.search_book .home_fy_block .el-pagination.is-background .el-pager li:not(.disabled).active{
    background-color: #F20E0E;
}
.search_book .home_fy_block .el-pagination .btn-prev{
    width: 50px;
    height: 50px;
    line-height: 50px;
}
.search_book .home_fy_block .el-pagination .btn-next{
    width: 50px;
    height: 50px;
    line-height: 50px;
}
.search_book  .home_fy_block .el-pagination .el-pager li{
    width: 50px;
    height: 50px;
    line-height: 50px;
    color: #747474;
    font-size: 20px;
}

.search_book .home_fy_block .is-in-pagination{
    width: 74px;
    height: 40px;
    line-height: 40px;
    font-size: 20px;
}
.search_book .home_fy_block .el-pagination__editor.el-input .el-input__inner{
     height: 50px;
    line-height: 40px;
}
.search_book .home_fy_block .el-pagination__total{
    line-height: 50px;
    height: 50px;
}
.search_book .home_fy_block .is-in-pagination text{
    font-size: 16px;
}
.search_book  .home_fy_block .el-pagination span:not([class*=suffix]){
    margin-left: 5px;
    font-size: 16px;
    vertical-align:sub;
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
