<template>
    <div class="book_press">
        <el-container>
        <el-header><heade-r></heade-r></el-header>
        <el-main>
            <div class="book_press_top">
                <div class="press_title">
                    <p>展厅导览/出版社详情</p>
                </div>
            </div>

            <div class="book_press_bottom">

                <el-card class="box_card_top">
                <div class="cards_item">
                    <div style="padding:20px;">
                        <div>
                                <img :src="info.store_logo">
                                <span class="presss_name">{{info.store_name}}</span>
                        </div>

                        <div class="bottom_title_middle">
                            <button class="btn2">机构介绍</button>
                            <p  v-html="info.store_description || '暂无介绍'"></p>
                        </div>
                        <div class="bottom_title_p">
                        <button class="btn1">基本信息</button>
                        <p>展区位置：<span>{{info.hall_address}}</span></p>
                        <p>公司名称：<span>{{info.store_company_name}}</span></p>
                        <p>网址：<span>{{info.company_web}}</span></p>
                        <p>联系电话：<span>{{info.store_mobile}}</span></p>
                        <p>公司地址：<span>{{info.store_address}}</span></p>
                        </div>
                    </div>
                </div>
                </el-card>
            </div>

            <!-- 左侧菜单栏和图书列表 -->
            <div class="press_left_menu">
                <div class="el-main-body-below-top">
                            <el-row>

                            <el-col :span="12">

                                <div class="grid-content bg-purple">


                                <h1 class="el-main-bt-left-top-h"  @click="clickLi(-1)">
                                        <p style="font-size:18px;">全部图书分类<i class="el-icon-arrow-down el-icon--right"></i></p>
                                </h1>

                                    <div class="ceshibb">
                                        <div class="leftbar">
         <ul>
            <li
            class="left_bar_block"
            v-for="(v,k) in goods_class" :key="k"
            :class="active == k ? 'isActive' : ''"
            @click="clickLi(k)"

            >
                <!-- 一级分类名称 -->
                <div class="class_1"><router-link :to="'/ceshi/index'">{{v.name}}<i class="el-icon-arrow-right"></i></router-link></div>
                <!-- 二级分类名称 -->
                <div class="class_2">
                    <ul>
                         <li style="color:#747474">{{v.tags}} </li>
                    </ul>
                </div>
                </li>
            </ul>

        </div>
                                    </div>


                                </div>
                            </el-col>

                            <el-col :span="8">
                                <div class="grid-content bg-purple-light" >
                                    <div class="cospan-top">
                                        <span class="cspan-left">版权图书({{total_data}})</span>
                                        <span class="cspan-right">全部<i class="el-icon-arrow-right"></i></span>
                                    </div>
                                    <div class="cospan-below">
                                        <el-row>
                                        <el-col :span="8" v-for="(value, index) in books_ieme" :key="index" :offset="index > 0 ? 2 : 0">
                                            <router-link :to="'/ceshi/book_details?id='+value.id">
                                            <el-card shadow="hover" :body-style="{ padding: '0px' }">
                                            <img :src="value.goods_master_image" class="image">
                                            <div style="padding:17px;">
                                                <span class="title-span">{{ value.goods_name }}
                                                </span>
                                                <div class="bottom clearfix">
                                                <p class="w-p">作者:{{ value.author }}</p>
                                                <!-- <time class="time">{{ currentDate }}</time> -->
                                                <p v-if="value.class">
                                                    <span class="s-p">{{ value.class.name }}</span>
                                                </p>
                                                </div>
                                            </div>
                                            </el-card>
                                            </router-link>
                                        </el-col>
                                        </el-row>



                                        <!-- 分页结构 -->
                                        <div class="center_home">
                                        <div class="home_fy_block  width_center_1200">
                                        <el-pagination @current-change="current_change" background layout="prev, pager, next,jumper,total" :total="total_data" :page-size="page_size" :current-page="current_page"></el-pagination>
                                        </div>
                                        </div>

                                    </div>
                                </div>


                            </el-col>

                            </el-row>

                        </div>





            </div>
            <div style="clear:both;margin-top:80px;margin-bottom:80px;"></div>
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
            info:[],
            goods_class:[],
            //图书列表数据
            books_ieme:[],

            //样式更改
            styleSwitch:true,
            //li类名动态设置
            active:-1,
            switchb:true,
            //左侧菜单栏测试
            props: {
                change_color:{
                    type:Boolean,
                    default:false,
                }
            },

            //分页数据
            goods_list:[],
            banner_list:[],
            total_data:0, // 总条数
            page_size:20,
            current_page:1,
            class_id:0
        }
    },
     created() {

        this.store_id = this.$route.query.id;
        if(this.$isEmpty(this.store_id)){
            return this.$message.error('请检查出版社是否存在');
        }
        this.get_info();
        this.get_class();
        this.get_book();
  },
    methods:{
           // 分页改变
        current_change:function(e){
            this.current_page = e;
            this.get_book();
        },
         // 获取信息
        get_info:function(){
            this.$post(this.$api.homeGetStoreInfo,{id:this.store_id}).then(res=>{
                this.info=res.data;
            });
        },
        get_class:function(){
            this.$get(this.$api.homeGetGoodsClassList,{}).then(res=>{
                this.goods_class=res.data;
            });
        },
        get_book:function(){
            this.$get(this.$api.homeSearchGoods,{page:this.current_page,pageSize:this.page_size,store_id:this.store_id,class_id:this.class_id}).then(res=>{
                this.books_ieme=res.data.data;
                this.page_size = res.data.per_page;
                this.total_data = res.data.total;
                this.current_page = res.data.current_page;
            });
        },

       clickLi(index){
           this.active=index;
            if(index>-1){
                this.class_id=this.goods_class[index].id;
           }else{
                this.class_id=0;
           }

           this.get_book();
           console.log(this.active)
       },
       mouseLeaveLi(id){
         //this.active=-1
       }
    }
}
</script>

<style lang="scss" scoped>
*{
    margin: 0;
    padding: 0;
}

.book_press .el-main{
    margin: 0 auto;
    margin-top: 78px;
    width:100%;
    height: auto;
}
.book_press_top{
    width:100%;
    height: 200px;
    position:absolute;
    z-index: 9;
    background:-webkit-linear-gradient(left,#DD18EB,#F97D00);
    min-width: 1200px;
}

.book_press_top .press_title{
    font-weight: bold;
    width:1200px;
    margin:auto;
}
.book_press_top p{
    padding-top: 45px;
    font-size: 36px;
    color: #fff;
}

//最上层
.book_press_bottom{
    width:1200px;
    margin:auto;
    margin-top: 110px;
    position:relative
}
.box_card_top{
    position:relative;
    z-index: 999;
}

.cards_item{
    width: 100%;
    height: auto;
    margin:auto;
}
.cards_item img{
    width: 150px;
    height: 150px;
    margin-top:10px;
    float: right;
    border-radius: 150px;
}
.presss_name{
    font-size: 40px;
    color: #333;
    margin-top: 50px;
    z-index: 9999999;
    float: left;
    font-weight: bold;
}
.bottom_title_p p{
    line-height: 30px;
    font-size: 16px;
    color: rgb(0, 0, 0);
    font-weight: bold;
}

.bottom_title_p{
    margin-top: 30px;
}
.bottom_title_p span{
    font-size: 14px;
    color: rgb(82, 82, 82);
    font-weight: normal;
}
.btn1{
    margin-bottom: 10px;
    font-size: 16px;
    color: #FFFFFF;
    width: 100px;
    height: 30px;
    border-radius:20px;
    border: none;
    background:-webkit-linear-gradient(top,#DD18EB,#F97D00);
}
.bottom_title_middle{
    margin-top: 200px;
    clear: both;
}
.btn2{
    margin-bottom: 10px;
    font-size: 16px;
    color: #FFFFFF;
    width: 100px;
    height: 30px;
    border-radius:20px;
    border: none;
    background:-webkit-linear-gradient(top,#DD18EB,#F97D00);
}
.bottom_title_middle p{
    line-height:32px;
    font-size: 16px;
    color: rgb(0, 0, 0);
}

//左侧菜单栏和图书列表样式
.el-main-body-below-top{
    width: 1200px;
    height:auto;
    margin:0 auto;
    margin-top:20px;
    clear:both;
}
.el-main-body-below-top .el-col-12{
    width:225px;
    height: 720px;
}

.el-main-body-below-top .bg-purple{
    width:225px;
    height: 720px;
    border-left: 1px solid #EEEEEE;
    border-right: 1px solid #EEEEEE;
    border-bottom: 1px solid #EEEEEE;
}


.el-main-body-below-top .el-col-8{
    margin-left: 20px;
    width:955px;
    height: auto;
    margin-bottom:80px;
}

.el-main-body-below-top .bg-purple--light{
    margin-left:18px;
    width:955px;
    height: 720px;
}

//卡片样式
.el-main-body-below-top .time {
    font-size: 13px;
    color: #999;
  }

.el-main-body-below-top .cospan-below .el-row{
    margin-left:50px;
    margin-top: 20px;
}

.el-main-body-below-top .cospan-below .el-col{
    width:200px;
    height:310px;

    margin-bottom: 15px;
    margin-left:20px;
}

 .el-main-body-below-top .bottom {
    margin-top: 13px;
    line-height: 12px;
  }

 .el-main-body-below-top .button {
    padding: 0;
    float: right;
  }

 .el-main-body-below-top .image {
    margin:0 auto;
    margin-top:10px;
    width:130px;
    height: 180px;
    display: block;
  }

.el-main-body-below-top  .clearfix:before,
  .clearfix:after {
      display: table;
      content: "";
  }

 .el-main-body-below-top .clearfix:after {
      clear: both
  }

.el-main-body-below-top .title-span{
    font-size: 16px;
    color: #333333;
    font-weight: bold;
}

 .el-main-body-below-top .w-p{
      font-size: 12px;
      color:#B2B2B2;
      margin-bottom:8px;
  }
 .el-main-body-below-top .s-p{
      font-size:12px;
      color: #DCA117;
  }

.cospan-below .s-p{
    font-size: 12px;
    color: #D464AB;
    min-width: 24px;
    height: 16px;
    border: 1px solid #F1D7EC;
    background: #FFF2FA;
    line-height: 16px;
    padding: 2px 4px;
    border-radius: 2px;
}
.el-card {
    border: 0px solid #EBEEF5;
    background-color: #FFF;
    color: #303133;
    transition: .3s;
    border-radius:0;
}
 // 左侧菜单栏测试样式
.leftbar{
    width: 100%;
    left: 0px;
    height: 450px;
    display: block;
    z-index: 998;
    color:#F20E0E;
    // background: rgba(255,255,255,.7);
    background:#fff;
    // overflow: hidden;
    // padding:0 15px;
    .left_bar_block{
       cursor:pointer;
    }
    .left_bar_block::after{
       content: '';
        width: 90%;
        height: 1px;
        display: block;
        background:#eee;
        margin:0 auto;
    }
    .class_1{
        padding: 8px 15px 0 15px;
        a{
            font-weight: bold;
            font-size: 14px;
        }
    }

    .class_2{
        padding: 0 15px 0 15px;
        font-size: 12px;
        overflow: hidden;
        box-sizing: border-box;
        width: 100%;
        height: 24px;
       ul:after{
            display: block;
            clear: both;
            content:'';
        }
        ul li{
            line-height: 18px;
            float: left;
            color:#747474;

            a{
                color:#747474;
                margin-right: 15px;
            }
        }
    }
    .class_2:after{
        display: block;
        clear: both;
        content:'';
    }

    .left_bar_block:hover{
        background: #fff;
        .subbar{
            display: block;
        }
    }
    .subbar{
        background: #fff;
        width: 960px;
        height: 450px;
        position: absolute;
        top:0;
        left: 243px;
        z-index: 999;
        display: none;
        border: 1px solid #F20E0E;
         box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
        .subbar_top{
            padding: 20px 0 0 20px;
            width: 680px;
            float: left;
            ul li{
                float: left;
                line-height: 25px;
                padding: 0 8px;
                background: #5f4f4f;
                margin-right: 20px;
                a{
                    color:#fff;
                    font-size: 12px;
                }
            }
            :after{
                display: block;
                content: "";
                clear: both;
            }
        }
        .subbar_right{
            float: right;
            width: 220px;
            height: 450px;
            ul{
                margin-top:20px;
            }
            ul li{
                float: left;
                height: 50px;
                border:1px solid #eee;
                border-bottom: none;
            }
            ul li:nth-child(2n){
                border-left: none;
            }
        }
    }
    .subbar_subnav{
        margin-left: 20px;
        margin-top: 15px;
        width: 680px;
        display: block;
        float: left;
        .class2_title{
            h4{
                width: 100px;
                text-align: right;
                float: left;
                margin-right: 30px;
            }
            ul{
                float: left;
                width: 600px;
                li{
                    float: left;
                    padding-left: 20px;
                    padding-right: 20px;
                    margin-top: 13px;
                    border-left: 1px solid #ddd;
                    line-height: 12px;
                    a{
                        font-size: 12px;
                        color:#F20E0E;

                    }
                    a:hover{
                        color:#F20E0E;
                    }

                }
                li:last-child{
                    margin-bottom: 14px;
                }
                border-bottom: 1px dashed #ccc;
            }

        }
        .class2_title:after{
            display: block;
            clear: both;
            content:'';
        }
    }

}
.leftbar2{
    width: 100%;
    position: absolute;
    left: 0px;
    height: 450px;
    display: block;
    z-index: 998;
    color:#fff;
    background:#F20E0E;
    // overflow: hidden;
    // padding:0 15px;
    .class_1{
        padding: 8px 15px 0 15px;
        a{
            // font-weight: bold;
            color:#fff;
        }
    }

    .class_2{
        padding: 0 15px 0 15px;
        font-size: 12px;
        overflow: hidden;
        box-sizing: border-box;
        width: 100%;
        height: 24px;
        ul:after{
            display: block;
            clear: both;
            content:'';
        }
        ul li{
            line-height: 18px;
            float: left;
            a{
                color:#bfbfbf;
                margin-right: 15px;
            }
        }
    }
    .class_2:after{
        display: block;
        clear: both;
        content:'';
    }

    .left_bar_block:hover{
        background:#F20E0E;
        .subbar{
            display: block;
        }
    }
    .subbar{
        background: #fff;
        width: 960px;
        height: 450px;
        position: absolute;
        top:0;
        left: 240px;
        z-index: 999;
        display: none;
        box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
        .subbar_top{
            padding: 20px 0 0 20px;
            width: 680px;
            float: left;
            ul li{
                float: left;
                line-height: 25px;
                padding: 0 8px;
                background: #5f4f4f;
                margin-right: 20px;
                a{
                    color:#fff  ;
                    font-size: 12px;
                }
            }
            :after{
                display: block;
                content: "";
                clear: both;
            }
        }
        .subbar_right{
            float: right;
            width: 220px;
            height: 450px;
            ul{
                margin-top:20px;
            }
            ul li{
                float: left;
                height: 50px;
                border:1px solid #eee;
                border-bottom: none;
            }
            ul li:nth-child(2n){
                border-left: none;
            }
        }
    }
    .subbar_subnav{
        margin-left: 20px;
        margin-top: 15px;
        width: 680px;
        display: block;
        float: left;
        .class2_title{
            h4{
                width: 60px;
                text-align: right;
                float: left;
                margin-right: 20px;
                color:#F20E0E;
            }
            ul{
                float: left;
                width: 600px;
                li{
                    float: left;
                    padding-left: 20px;
                    padding-right: 20px;
                    margin-top: 13px;
                    border-left: 1px solid #ddd;
                    line-height: 12px;
                    a{
                        font-size: 12px;
                        color:#999;

                    }
                    a:hover{
                        color:#F20E0E;
                    }

                }
                li:last-child{
                    margin-bottom: 14px;
                }
                border-bottom: 1px dashed #ccc;
            }

        }
        .class2_title:after{
            display: block;
            clear: both;
            content:'';
        }
    }

}

.class_1 a{
    color: #ca151e;
    font-size:16px;
}

.left_bar_block .el-icon-arrow-right{
    float:right;
    font-weight: bold;
}
.ceshibb{
    width:225px;
}
.ceshibb .isActive{
    border-bottom:1px solid #F20E0E;
    border-top:1px solid #F20E0E;
    box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
}
.press_left_menu{

}

.cospan-top{
    line-height: 50px;
    margin-left:0px;
    text-align: center;
    //font-size: 16px;
    color:#fff;
    background:-webkit-linear-gradient(left,#DD18EB,#F97D00);
}
.cspan-left{
    margin-right: 780px;
}


.el-main-bt-left-top-h{
    line-height: 50px;
    width:225px;
    text-align: center;
    //font-size: 16px;
    color:#fff;
    background:-webkit-linear-gradient(left,#DD18EB,#F97D00);
    cursor:pointer;
}

//分页样式修改
.center_home{
    height: 52px;
    width: 100%;
    margin: 0 auto;
    position:relative;
    text-align:center;
    margin-bottom:40px;
}

.book_press .width_center_1200{
   margin: 0 auto;
}
.book_press .width_center_1200{
    width:100%;
}
</style>


<style>
/* 修改element-ui原生组件的自带样式 */

/* 修改下拉菜单的字体大小 */
.el-main-body-below-top .el-button--small{
    font-size:16px;
}

/* 分页样式修改 */
.book_press .home_fy_block .el-pagination.is-background .el-pager li:not(.disabled).active{
    background-color: #F20E0E;
}
.book_press .home_fy_block .el-pagination .btn-prev{
    width: 50px;
    height: 50px;
    line-height: 50px;
}
.book_press .home_fy_block .el-pagination .btn-next{
    width: 50px;
    height: 50px;
    line-height: 50px;
}
.book_press  .home_fy_block .el-pagination .el-pager li{
    width: 50px;
    height: 50px;
    line-height: 50px;
    color: #747474;
    font-size: 20px;
}

.book_press .home_fy_block .is-in-pagination{
    width: 74px;
    height: 40px;
    line-height: 40px;
    font-size: 20px;
}
.book_press .home_fy_block .el-pagination__editor.el-input .el-input__inner{
     height: 50px;
    line-height: 40px;
}
.book_press .home_fy_block .el-pagination__total{
    line-height: 50px;
    height: 50px;
}
.book_press .home_fy_block .is-in-pagination text{
    font-size: 16px;
}
.book_press .home_fy_block .el-pagination span:not([class*=suffix]){
    margin-left: 5px;
    font-size: 16px;
    vertical-align:sub;
}
.grid-content.bg-purple-light{
    border-left: 1px solid #EEEEEE;
    border-right: 1px solid #EEEEEE;
    border-bottom: 1px solid #EEEEEE;
}
.el-main{
    overflow: unset;
    min-width: 1200px;
    clear:both;
}
</style>
