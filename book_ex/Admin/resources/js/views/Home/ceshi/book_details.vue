<template>
    <div class="book_details">
        <el-container>
        <el-header><heade-r></heade-r></el-header>
        <el-main>
            <div style="margin-top: 80px;">
                <div class="book_details_main_top">
                    <p class="personage">图书详情</p>
                </div>
                <div class="book_details_main_bottom">

                    <el-card class="book_details_card_top">
                    <div class="text item">
                        <div class="card_images">
                        <img :src="goods_info.goods_master_image" class="book_details_card_top_image">
                        </div>
                        <div class="card_fonts">
                        <div style="height:100%;">
                            <p class="p_title">{{goods_info.goods_name}}</p>
                            <p class="p_author">作者：{{goods_info.author}}</p>
                            <p class="p_book_type">
                                 <span class="s-p">{{ goods_info.class_list[2].name }}</span>
                            </p>
                        </div>
                            <el-button @click="goods_fav()" :class="is_fav?'goods_info_sc on':'goods_info_sc'" class="shoucang">{{is_fav?'已收藏':'点击收藏'}}</el-button>
                        </div>
                    </div>
                    </el-card>

                    <el-card class="book_details_card_middle">
                        <div style="padding:10px 40px;">
                            <el-button class="middle_btn">基本信息</el-button>
                            <div class="text item">
                                <p class="book_code">书号：<span>{{goods_info.goods_no}}</span></p>
                                <p class="book_versions">版别：<span>{{goods_info.edition}}</span></p>
                                <p class="book_press">出版社：<span>{{goods_info.store_info.store_name}}</span></p>
                                <p class="press_time">出版年份：<span>{{goods_info.year_publication}}</span></p>
                                <p class="book_price">定价：<span>{{goods_info.goods_price}}</span></p>
                                <p class="sales_price">销售价：<span>{{goods_info.goods_market_price}}</span></p>
                            </div>
                        </div>
                    </el-card>

                    <el-card class="book_details_card_bottom">
                    <div style="padding:10px 40px;">
                        <el-button class="bottom_btn">简介</el-button>
                        <div class="text item"  v-html="goods_info.goods_content || '暂无简介'">

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
            goods_info:[],
            store_info:[],
            goods_images_thumb:['/pc/loading_pic_400.png'],
            goods_images:[],
            is_fav:false,
        }
    },
    created() {

        this.goods_id = this.$route.query.id;
        if(this.$isEmpty(this.goods_id)){
            return this.$message.error('请检查图书是否存在');
        }
        this.get_goods_info();
        if(!this.$isEmpty(localStorage.getItem('token'))){
             this.is_fav_fun();
        }


   },
    methods:{
          // 收藏状态
        is_fav_fun:function(){

            this.$post(this.$api.homeIsFav,{mix_id:this.goods_id,is_type:0}).then(res=>{
                if(res.code== 200){
                    this.is_fav = true;
                }else{
                    this.is_fav = false;
                }
            });
        },
        goods_fav:function(){
            if(this.$isEmpty(localStorage.getItem('token'))){
                return this.$message.error('请先登录！');
            }
            this.$post(this.$api.homeEditFav,{mix_id:this.goods_id,is_type:0}).then(res=>{
                 if(res.code== 200){
                     if(res.data==1){
                         this.is_fav = true;
                        return this.$message.success('收藏成功！');
                     }else{
                        this.is_fav = false;
                        return this.$message.success('取消收藏成功！');
                    }
                }
            });
        },
        // 获取图书信息
        get_goods_info:function(){
            this.$post(this.$api.homeGetGoodsInfo,{goods_id:this.goods_id}).then(res=>{
                this.goods_info = res.data;
                this.store_info = res.data.store_info;
                this.goods_images_thumb = res.data.goods_images_thumb;
                this.goods_images = res.data.goods_images;

                this.is_spec_goods();

                // this.isSkeleton = true; // 骨架显示

                // 存储游览记录
                if(this.save_history){
                    this.save_history_fun(this.goods_info);
                }
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
.book_details{
    margin:0 auto;

    width:100%;
    //height:520px;
}


.p_book_type .s-p{
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

.book_details_main_top {
    background:-webkit-linear-gradient(left,#DD18EB,#F97D00);
    width:100%;
    height: 200px;
    display: block;
    position: absolute;
    z-index: -1;
    min-width: 1200px;
}

.book_details_main_top .personage{
    font-size: 36px;
    color: #fff;
    width:1200px;
    margin:auto;
    margin-top: 45px;

}

.book_details_main_bottom{
    margin: 0 auto;
    width:1200px;
    z-index: 999;
    padding-top:120px;
}
.book_details_card_top{
    width: 100%;
}

.book_details_card_top .p_title{
    width: 582px;
    font-size: 30px;
    color: #333333;
    margin-top:0;
    margin-bottom: 23px;
    font-weight: bold;
    padding-top:20px;
}
.card_images{
    display: inline-block;
    padding:20px 40px;
    border:1px solid #eeeeee;
    margin:20px;
}
.card_fonts{
    height:479px;
    display: inline-block;
}
.book_details_card_top_image{
    //display: inline-block;
    width: 350px;
    height: 479px;
    box-shadow: 6px 6px 6px 0px rgba(0, 0, 0, 0.36);

}
.p_author{
    font: 16px;
    color: #747474;
    margin-bottom: 14px;
}
.p_book_type{
    font-size: 20px;
    color: #DCA117;
}

.shoucang{
    width: 200px;
    height: 50px;
    font-size: 16px;
    border-radius:4px;
    margin-bottom: 20px;
    color: #333333;
    background:#F5F5F5;
    border: 0px solid #DCDFE6;
}

.shoucang.on{
 background:-webkit-linear-gradient(left,#DD18EB,#F97D00);
 color: #fff;
}
.book_details_card_middle {
    width: 1200px;
    margin: auto;
    margin-top: 20px;
    height: auto;

}
.book_details_card_middle .item p{
    color:#333333;
    font-size: 16px;
    font-weight: bold;
    margin-top:5px;
}

.book_details_card_bottom {
    width: 1200px;
    margin: auto;
    margin-top: 20px;
    margin-bottom: 40px;
}
.book_details_card_middle span{
    font-size: 14px;
    color: #747474;
    font-weight: normal;
}
.middle_btn{
    width: 100px;
    height: 30px;
    border-radius:15px;
    font-size: 16px;
    color: #fff;
    margin: 0px 0 10px 0;
    background:-webkit-linear-gradient(top,#DD18EB,#F97D00);
}
.book_details_card_bottom p{
    margin-top:5px;
    font-size: 16px;
    color: #333333;
}
.book_details_card_bottom span{
    font-size: 14px;
    color: #747474;
    font-weight: unset;
}
.bottom_btn{
    width: 57px;
    height: 30px;
    font-size: 16px;
    color: #fff;
    margin-bottom: 10px;
    margin-left: 0;
    border-radius:15px;
    //margin-top: 260px;
   background:-webkit-linear-gradient(top,#DD18EB,#F97D00);
}
.book_details_card_bottom span{
    margin-left: 80px;
}
.el-button{
    border: 0 solid #DCDFE6;
}
.el-main{
    overflow: unset;
    min-width: 1200px;
}
</style>
