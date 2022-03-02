<template>
    <div>
        <el-container>
            <el-header><heade-r></heade-r></el-header>
            <el-main>
                <!-- 首页整体内容 -->
                <div class="el-main-body">

                    <!-- 上背景图部分div -->
                    <div class="el-main-body-top">
                        <img v-if="banner_list[0]" :src="banner_list[0].adv_image" style="width:100%;height:100%;">
                    </div>

                    <!-- 下方页面内容 -->
                    <div class="el-main-body-below">

                        <!-- 左侧菜单和推荐图书 -->
                        <div class="el-main-body-below-top">
                            <el-row>

                            <el-col :span="12">

                                <div class="grid-content bg-purple">


                                <h1 class="el-main-bt-left-top-h" @click="clickLi(-1)">
                                        <p style="font-size:18px;">全部图书分类<i class="el-icon-arrow-down el-icon--right"></i></p>
                                </h1>

                                    <div class="ceshibb">
                                        <div :class="change_color?'leftbar2':'leftbar'">
        <ul>
            <li
            class="left_bar_block"
            v-for="(v,k) in goods_list" :key="k"
            :class="active == k ? 'isActive' : ''"
            @click="clickLi(k)"
            >
                <!-- 一级分类名称 -->
                <div class="class_1"><router-link :to="'/ceshi/index'">{{v.class_info.name}}<i class="el-icon-arrow-right"></i></router-link></div>
                <!-- 二级分类名称 -->
                <div class="class_2">
                    <ul>
                         <li style="color:#747474">{{v.class_info.tags}} </li>
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
                                        <span class="cspan-left">推荐图书</span>
                                        <router-link :to="'/ceshi/search_book'">
                                          <span class="cspan-right">全部<i class="el-icon-arrow-right"></i></span>
                                        </router-link>
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
                                    </div>
                                </div>
                            </el-col>

                            </el-row>
                        </div>

                        <!-- 线上直播 -->
                        <div class="el-main-body-below-below" v-if="live[0]">
                            <div class="btbspan-top">
                                    <span class="btbspan-left">线上直播</span>
                                    <router-link :to="'/ceshi/book_live'">
                                        <span class="btbspan-right">全部<i class="el-icon-arrow-right"></i></span>
                                    </router-link>
                            </div>
                            <div class="btbspan-below">
                                <div class="btbspan-below-mleft">
                                    <img :src="live[0].goods_master_image" class="image-one">
                                    <span class="onlive">直播中</span>
                                    <div class="onlive_title">
                                        <p class="cont">{{ live[0].goods_name }}</p>
                                    </div>
                                </div>
                                <div class="btbspan-below-mright">
                                <el-row>
                                <el-col style="position: relative;" :span="4" v-for="(item,i) in live" :key="i" v-show="i>0" :offset="i> 0 ? 2 : 0">
                                    <el-card shadow="hover" :body-style="{ padding: '0px' }">
                                    <img :src="item.goods_master_image" class="image">
                                    <span class="onlook">回放</span>

                                    <div class="cont" style="padding-top:5px;">
                                        <span class="btbw-p">{{ item.goods_name }}</span>
                                        <div class="bottom clearfix">
                                        <time class="time">主播:{{ item.live_name }}</time>

                                        </div>
                                    </div>
                                    </el-card>
                                </el-col>
                                </el-row>
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

    //左侧菜单栏测试
    props: {
        change_color:{
            type:Boolean,
            default:false,
        }
    },

    data(){
        return{
            currentDate: new Date(),
            switchb:true,
            banner_list:[],
            goods_list:[],
            live:[],

            //推荐图书
            books_ieme:[],
            all_goods_list:[],
            //样式更改
            styleSwitch:true,
            //li类名动态设置
            active:-1,

            //左侧菜单栏测试
             goods_class:[],
          goods_brand:[],
          goods_brand_adv:{},
        }
    },
    created() {
        this.get_index_info();
        this.get_zhibo_info();
    },
    methods:{
         // 获取首页信息
        get_index_info:function(){
            this.$get(this.$api.homeGetIndexInfo).then(res=>{
                this.banner_list = res.data.banner.adv;
                this.goods_list = res.data.goods_list;
                this.all_goods_list=res.data.all_goods_list;
                this.books_ieme=res.data.all_goods_list.data;
                this.seckill_goods_list = res.data.seckill_goods;
                this.seckill_info = res.data.seckill_info;
            });
        },
        // 获取直播信息
        get_zhibo_info:function(){
            this.$get(this.$api.homeGetIntegralIndexInfo).then(res=>{
                this.live=res.data.hot_goods;
            });
        },
    //    mouseOveri(id){
    //        this.styleSwitch=false
    //        console.log(this.styleSwitch+'鼠标进入了')
    //    },
    //     mouseLeavei(id){
    //         this.styleSwitch=true
    //         console.log(this.styleSwitch+'鼠标离开了了')
    //    },
       clickLi(index){
           this.active=index;
           if(index>-1){
                this.books_ieme=this.goods_list[index].list;
           }else{
                this.books_ieme=this.all_goods_list.data;
           }

           console.log(this.active)
       },
       mouseLeaveLi(id){
        //this.active=-1
       }
    },
}
</script>

<style  lang="scss" scoped>
*{
    margin: 0;
    padding: 0;
}
.el-main{
    margin-top:78px;
}
.el-main-body-top{
    width:100%;
    height:520px;
    margin:0 auto;
    //border-left:0px solid #DD18EB;
    //border-right:0px solid #DD18EB;
    background:-webkit-linear-gradient(left,#DD18EB,#F97D00);
    min-width: 1200px;
}
.el-main-body-below{
    width:100%;
    height: auto;
    margin:0 auto;
    //border-left:0px solid #DD18EB;
    //border-right:0px solid #DD18EB;
}
.el-main-body-below-top{
    width: 1200px;
    height:720px;
    margin:0 auto;
    margin-top:20px;
    //border-left:1px solid #53D486;
   // border-right:1px solid #53D486;
}

.el-main-body-below-below{
    width:1200px;
    height:490px;
    margin:0 auto;
    margin-top:20px;
    border:1px solid#EEEEEE;
    margin-bottom:80px;
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
    margin-left: 18px;
    width:955px;
    height: 720px;
}

.el-main-body-below-top .bg-purple--light{
    margin-left:18px;
    width:955px;
    height: 720px;
}
.cospan-top{
    line-height: 50px;
    width:955px;
    text-align: center;
    //font-size: 16px;
    color:#fff;
    background:-webkit-linear-gradient(left,#DD18EB,#F97D00);
}
.cspan-left{
    margin-right: 780px;
}

.btbspan-top{
    line-height: 50px;
    width:1200px;
    text-align: center;
    //font-size: 16px;
    color:#fff;
    background:-webkit-linear-gradient(left,#DD18EB,#F97D00);
}

.btbspan-left{
    margin-right:1016px;
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
    margin-left:10px;
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
      color: #D464AB;
      min-width:24px;
      height:16px;
      border:1px solid #F1D7EC;
      background:#FFF2FA;
      line-height:16px;
      padding:2px 4px;
      border-radius: 2px;
  }


//线上直播卡片区域样式

 .el-main-body-below-below .time {
    font-size: 13px;
    color: #999;
  }

.btbspan-below[data-v-094039f8]{
    width:1121px;
    height:372px;
    padding: 30px 42px 0 37px;
}

.btbspan-below .el-col{
    padding-top:0px;
    width:210px;
    height:200px;
   // padding-left:30px;
   margin-left:25px;
}

.btbspan-below .btbw-p{
    font-size:16px;
    color: #333333;
    margin-top:0px;
    margin-bottom:0px;
    font-weight: bold;
}
 .el-main-body-below-below  .image {
    width:210px;
    height: 120px;
    display: block;
  }

.el-main-body-below-below  .image-one {
    width:641px;
    height: 372px;
    display: block;
  }

.btbspan-below{
    width:915px;
    height:372px;
}
.btbspan-below-mleft{
    display: inline-block;
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
.btbspan-below-mright .is-hover-shado{
    height: 169px;
}
.btbspan-below-mright{
    width:470px;
    //margin-left:30px;
    height: 372px;
    display: inline-block;
}
.btbspan-below-mright .el-card__body{
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
.el-main-body-below .one-item a{
    color:#F20E0E;
    font-size:15px;
    margin-left:10px;
    padding-bottom:10px;
    padding-top:5px ;
    line-height: 40px;
    //border-bottom:1px dashed #DCDCDC;
    align-content: center;
}

.bottom.clearfix{
    margin-top:5px ;
}
//虚线修改
.one-item-ms{
    border-bottom:1px dashed #DCDCDC;
}

.el-main-body-below .one-item .el-icon-arrow-right{
    float:right;
    line-height: 40px;
    align-content: center;
}

//显示实线
.el-main-body-below .isActive .one-itemhms{
    border-bottom:1px solid #F20E0E;
    z-index:99999;
    float:left;
    width:500px;
}


//div的上下位置确定
.el-main-body .bg-purple{
    position: absolute;
    z-index:9999;
}

.el-main-body .bg-purple-light{
    border-left:1px solid #EEEEEE;
    border-right:1px solid #EEEEEE;
    border-bottom:1px solid #EEEEEE;
    position: absolute;
}

.menu-one-children-right{
    margin-left: 300px;
    display:inline-block;
}

.menu-one-childrens{
    display:inline-block;
}

// 左侧菜单栏测试样式
.leftbar{
    width: 100%;
    position: absolute;
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
.cont{

    overflow: hidden;/*内容超出后隐藏*/

    text-overflow: ellipsis;/* 超出内容显示为省略号*/

    white-space: nowrap;/*文本不进行换行*/
    width:100%;
}
.el-card {
    border: 0px solid #EBEEF5;
    background-color: #FFF;
    color: #303133;
    transition: .3s;
    border-radius:0;
}
//左侧菜单栏测试样式
.class_1 a{
    color: #F20E0E;
    font-size:16px;
}

.left_bar_block .el-icon-arrow-right{
    float:right;
    font-weight: bold;
}
.ceshibb .isActive{
    border-bottom:1px solid #F20E0E;
    border-top:1px solid #F20E0E;
    box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
}
</style>


<style>
/* 修改下拉菜单的字体大小 */
.el-main-body-below-top .el-button--small{
    font-size:16px;
}
a{
    color:#fff;
}
.el-main{
    overflow: unset;
    min-width: 1200px;
}
</style>
