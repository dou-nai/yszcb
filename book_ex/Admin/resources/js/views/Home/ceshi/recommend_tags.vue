<template>
    <div class="recommend_tags">
        <el-container>
        <el-header><heade-r></heade-r></el-header>
        <el-main>
            <div>
                <div class="recommend_tags_main_top">
                    <p class="personage">推荐标签</p>
                </div>
                <div class="recommend_tags_main_bottom">
                    <el-card class="card_book_type">
                        <div style="padding:0 20px;">
                            <p class="card_type_title">选择兴趣的书类</p>
                            <p class="card_type_font">选择书类（最多选择三种）</p>
                            <p class="card_type_fontb">方便我们提供更方便的服务</p>
                        </div>
                    <el-row>
                    <el-col :span="8" v-for="(value,index) in cared_data" :key="index" :offset="index > 0 ? 2 : 0">
                        <el-card :body-style="{ padding: '0px' }">
                        <div  style="padding:0;">
                            <p>{{ value.name }}</p>
                            <div class="recommend_tags_btn">
                            <el-button class="button" @click="select(index)" :class="sel_cateid.indexOf(value.id)>-1?'on':''">关注</el-button>
                            </div>
                        </div>
                        </el-card>
                    </el-col>
                    </el-row>
                    <div class="buttombc">
                    <el-button class="button2" v-if="!sel_disabled" @click="edit_fav">保存</el-button>
                    <el-button class="button2" v-if="sel_disabled" disabled >保存</el-button>
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
            //卡片数据
            cared_data:[],
            sel_cateid:[],
            sel_disabled:false,
        }
    },
    created() {
        this.get_class();
        this.fav_list();
    },
    methods: {
        edit_fav:function(){
            let self=this;
            if(self.sel_disabled){
                return;
            }
            if(self.sel_cateid.length<=0){
                return this.$message.error('至少选择1类');
            }

             this.sel_disabled=true;
            this.$post(this.$api.homeEditUserInfo,{like_cate_ids:this.sel_cateid.join()}).then(res=>{
                this.sel_disabled=false;
                 if(res.code == 500){
                    return this.$message.error(res.msg);
                }else{
                    this.$message.success('保存成功');
                    this.$router.push('/ceshi/index');
                }
            });
        },
         fav_list:function(){
            this.$get(this.$api.homeEditUserInfo,{}).then(res=>{
                 if(!this.$isEmpty(res.data.like_cate_ids)){

                    this.sel_cateid=res.data.like_cate_ids.split(",");
					this.sel_cateid=this.sel_cateid.map(Number);


                 }
            });
        },
         get_class:function(){
            this.$get(this.$api.homeGetGoodsClassList,{}).then(res=>{
                this.cared_data=res.data;
            });
        },
        select(i){
            let that=this;
            let item=that.cared_data[i];
            console.log(that.sel_cateid.indexOf(item.id));

            if(that.sel_cateid.indexOf(item.id)>-1){
                let newCateid =[];
                for(var it of that.sel_cateid){
                    console.log(it);
                    if(it!=item.id){
                        newCateid.push(Number(it));
                    }
                }
                that.sel_cateid=newCateid;
            }else{

                if(that.sel_cateid.length>=3){
                    return this.$message.error('最多选择3类！');
                }

                let newCateid=that.sel_cateid;
                newCateid.push(item.id);
                that.sel_cateid=newCateid;
            }
            console.log(that.sel_cateid);
        },
    },
}
</script>

<style lang="scss" scoped>
*{
    margin:0;
    padding:0;
}
.recommend_tags{
    margin:0 auto;
    border-left:0px solid #DD18EB;
    border-right:0px solid #DD18EB;
    width:100%;
    //height:520px;
}

.recommend_tags .el-main{
    margin-top:78px;
}

.recommend_tags_main_top {
    background:-webkit-linear-gradient(left,#DD18EB,#F97D00);
    width:100%;
    height: 200px;
    display: block;
    position: absolute;
    z-index:-1;
    min-width: 1200px;
}

.recommend_tags_main_bottom{
    padding-bottom:80px;
    padding-top:110px;
    z-index: 999;

}
.recommend_tags_main_top .personage{
    font-size: 36px;
    color: #fff;
    width:1200px;
    margin:auto;
    margin-top: 45px;
    font-weight: bold;
}


//卡片区域样式
.recommend_tags .time {
    font-size: 13px;
    color: #999;
  }

 .recommend_tags .bottom {
    margin-top: 13px;
    //line-height: 12px;
  }

 .recommend_tags .button {
    padding: 0;
    //float: right;
  }

 .recommend_tags .card_book_type .el-row p{
     font-size: 19px;
     color:#333333;
    text-align:center;
     margin-top:30px;
     margin-bottom:30px;
 }
.recommend_tags  .el-col{
    width: 167px;
    height: 150px;
    margin: 20px;
}
.recommend_tags .is-always-shadow{
    width: 167px;
    height: 150px;
}
.recommend_tags .card_book_type{
    width:1200px;
    height: auto;
    margin: auto;
}
.recommend_tags .card_book_type .button2{
    border-radius:30px;
    font-size: 16px;
    width: 130px;
    height: 50px;
    color: #fff;
    background:-webkit-linear-gradient(left,#DD18EB,#F97D00);
    border: 0px solid #DCDFE6;
}
.recommend_tags .card_book_type .button{
    border-radius:30px;
    font-size: 16px;
    width: 70px;
    height: 40px;
    color: #333333;
    background:#F5F5F5;
    border: 0px solid #DCDFE6;
}
.recommend_tags .card_book_type .button.on{
    border-radius:30px;
    font-size: 16px;
    width: 70px;
    height: 40px;
    color: #fff;
    background:-webkit-linear-gradient(left,#DD18EB,#F97D00);
}

.recommend_tags .recommend_tags_btn{
    width: 70px;
    height: 40px;
    margin:  0 auto;
}

.card_book_type .card_type_title{
    font-size: 34px;
    color: #111111;
    margin-bottom: 10px;
    font-weight: bold;
    padding-top:20px;
}

.card_book_type .card_type_font{
    font-size: 16px;
    color: #a5a5a5;
}

.card_book_type .card_type_fontb{
    font-size: 16px;
    color: #a5a5a5;
}

.card_book_type .card_book_type {
    margin: 0 auto;
}
.card_book_type .buttombc{
    width: 130px;
    height: 50px;
    margin:  0 auto;
    margin-top:50px;
    margin-bottom:50px;
}
.card_book_type .buttombc .button{
    width: 130px;
    height: 50px;
}
.el-main{
    overflow: unset;
    min-width: 1200px;
}
</style>



