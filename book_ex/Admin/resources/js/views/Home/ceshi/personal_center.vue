<template>
    <div class="personal_center">
        <el-container direction=" horizontal">
            <el-header><heade-r></heade-r></el-header>
            <el-main>
                <div class="activity-main">
                    <div class="activity-main-top">
                        <p class="ach">个人中心</p>
                    </div>
                    <div class="activity-main-card" style="padding-top:60px;margin-bottom:30px;">
                        <!-- 个人资料卡 -->
                        <el-card class="role_box_card">
                            <div style="padding:10px 20px;">
                                <div class="left_images">
                                <img src="/dist/images/pc/role_img.png" class="iamge_r">
                                </div>
                                <div class="role_font">
                                <p class="role_name">{{yMobile(user_info.nickname)}}</p>
                                <p class="role_id"><a href="/ceshi/change_password">账号：{{yMobile(user_info.username)}}</a></p>
                                <p class="role_type">兴趣标签：
                                   <a href="/ceshi/recommend_tags"> <span class="s-p" v-for="value in cate_list" :key="value">
                                        {{value.name}}
                                    </span>
                                    </a>
                                </p>
                                </div>
                            </div>
                        </el-card>
                    </div>

                    <!-- 个人收藏书籍 -->
                    <div class="box_sa">
                         <el-card  class="shoucang_box_card">
                             <p class="boc_sa_title">收藏图书（{{total_data}}）</p>
                             <div v-for="value in list" :key="value" class="cards_item_shouang">
                              <router-link :to="'/ceshi/book_details?id='+value.goods.id">
                                <div class="left_imagess">
                                <img :src="value.goods.goods_master_image" class="iamge_rs">
                                </div>
                                <div class="role_fonts">
                                <p class="role_names">{{value.goods.goods_name}}</p>
                                <p class="role_ids">作者：{{value.goods.author}}</p>
                                <p class="role_types" v-if="value.goods.class">
                                 <span class="s-p">{{value.goods.class.name}}</span>
                                 </p>
                                </div>
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
            current_page:1,
            user_info:[],
            cate_list:[]
        }
    },
    created() {
        let user_info = localStorage.getItem('user_info');
        if(!this.$isEmpty(user_info)){
            this.user_info = JSON.parse(user_info);
            console.log(this.user_info);
        }else{
            this.$router.push('/ceshi/login');
        }

        this.get_list();
        this.get_cate();
    },
    methods:{
         // 分页改变
        current_change:function(e){
            this.current_page = e;
            this.get_list();
        },
        // 获取活动信息
        get_list:function(){
            this.$post(this.$api.homeGetFavList,{page:this.current_page,pageSize:this.page_size,is_type:0}).then(res=>{
                this.list=res.data.data;
                this.page_size = res.data.per_page;
                this.total_data = res.data.total;
                this.current_page = res.data.current_page;
            });
        },
        get_cate:function(){
            this.$get(this.$api.homeEditUserInfo,{}).then(res=>{
                 if(!this.$isEmpty(res.data.tags)){
                    this.cate_list=res.data.tags;
                 }
            });
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
    margin:0;
    padding:0;
}

.el-main{
    margin: 0 auto;
    width:100%;
    height:auto;
}

.activity-main{
    margin-top:20px;
}
.activity-main-top{
    position:absolute;
    z-index:-1;
    margin-top: 60px;
    width:100%;
    min-width:1200px;
    height:200px;
    background:-webkit-linear-gradient(left,#DD18EB,#F97D00);
}
.ach{
    margin:auto;
    font-size: 36px;
    margin-top: 45px;
    color: #fff;
    width:1200px;
    font-weight: bold;
}
.role_box_card{
    margin:auto;
    z-index:999;
    width: 1200px;
    margin-top:120px;
}
.iamge_rs{
    width:130px;
    height:180px;
}

.personal_center .role_font{
    margin-left:30px ;
    display:inline-block;
    //vertical-align: top;
    margin-top: 15px;
}
.personal_center .iamge_r{
    width: 150px;
    height: 150px;
    display: block;

}
.personal_center .left_images{
    margin-top: 5px;
    height: 150px;
    line-height: 150px;
    align-content: center;
    display:inline-block;
    vertical-align: top;
    margin-left: 35px;
}
.personal_center .role_name{
    font-size: 40px;
    color: #333333;
    margin-bottom: 15px;
    font-weight: bold;
}
.personal_center .role_id,.personal_center .role_id a{
    font-size: 16px;
    color: #747474;
    margin-bottom: 15px;
}
.personal_center .role_type{
    font-size: 16px;
    color: #747474;
}

//图书收藏卡片
.box_sa{
   width: 1200px;
   z-index:909999;
   margin:auto;
   margin-bottom: 80px;
}
.role_type .s-p{
      font-size:16px;
      color: #ffffff;
      min-width:24px;
      height:16px;
      border:0px solid #F1D7EC;
      background:-webkit-linear-gradient(top,#DD18EB,#F97D00);
      line-height:16px;
      padding:5px 10px;
      border-radius: 20px;
      margin-right:10px;
      text-align: center;
  }
.role_types .s-p{
      font-size:20px;
      color: #D464AB;
      min-width:24px;
      height:16px;
      border:1px solid #F1D7EC;
      background:#FFF2FA;
      line-height:16px;
      padding:2px 4px;
      border-radius: 2px;
  }

.box_sa .left_imagess{
    display:inline-block;
    margin-left: 60px;
    box-shadow: 6px 6px 6px 0px rgba(0, 0, 0, 0.36);
}
.role_fonts{
    margin-left: 80px;
    margin-top: 0px;
    display:inline-block;
    vertical-align: top;
}
.box_sa .role_names{
    font-size: 30px;
    color: #333333;
    margin-bottom:14px ;
    font-weight: bold;
}
.box_sa .role_ids{
    font-size: 16px;
    color: #747474;
    margin-bottom: 60px;
}
.box_sa .role_types {
    font-size: 20px;
    color: #D464AB;
}
.box_sa .boc_sa_title{
    font-size: 40px;
    color: #F20E0E;
    margin: 31px 0 31px 40px;
    font-weight: bold;
}
.box_sa .cards_item_shouang{
    padding-bottom: 25px;
    margin-top: 25px;
    border-bottom:1px solid #eee;
}

//分页样式
.box_sa .activity-card-footer{
    padding-top: 30px;
    height: 52px;
    width: 1200px;
    display:table-cell;
    vertical-align:middle;
    text-align: center;
    padding-bottom:30px;
}
.box_sa .home_fy_block{
    width: 1000px;
    height: 50px;
   display:inline-block;
}
</style>

<style>
/* 穿透修改了原生element-ui分页组件的样式 */
.box_sa .home_fy_block .el-pagination.is-background .el-pager li:not(.disabled).active{
    background-color: #F20E0E;
}
.box_sa .home_fy_block .el-pagination .btn-prev{
    width: 50px;
    height: 50px;
    line-height: 50px;
}
.box_sa .home_fy_block .el-pagination .btn-next{
    width: 50px;
    height: 50px;
    line-height: 50px;
}
.box_sa  .home_fy_block .el-pagination .el-pager li{
    width: 50px;
    height: 50px;
    line-height: 50px;
    color: #747474;
    font-size: 20px;
}

.box_sa .home_fy_block .is-in-pagination{
    width: 74px;
    height: 40px;
    line-height: 40px;
    font-size: 20px;
}
.box_sa .home_fy_block .el-pagination__editor.el-input .el-input__inner{
     height: 50px;
    line-height: 40px;
}
.box_sa .home_fy_block .el-pagination__total{
    line-height: 50px;
    height: 50px;
}
.box_sa .home_fy_block .is-in-pagination text{
    font-size: 16px;
}
.box_sa .home_fy_block .el-pagination span:not([class*=suffix]){
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



