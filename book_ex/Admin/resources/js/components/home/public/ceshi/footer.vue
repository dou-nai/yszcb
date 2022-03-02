<template>
    <div class="footer">
        <div style="margin:auto;width:1200px;">
            <div class="footer-top">
                <el-row>
                <el-col :span="6">
                <div style="margin:auto;width:150px;">
                    <div class="footer-top-item1">
                        <img src="/dist/images/icons3.png">
                    </div>
                    <div class="footer-top-item-font">
                            <span>正规行货</span>
                            <br/>
                            <span>品牌承诺</span>
                    </div>
                </div>
                </el-col>

                <el-col :span="6">
                <div style="margin:auto;width:150px;">
                    <div class="footer-top-item1">
                        <img src="/dist/images/icons2.png">
                    </div>
                    <div class="footer-top-item-font">
                        <span>品类齐全</span>
                        <br/>
                        <span>轻松逛展</span>
                    </div>
                </div>
                </el-col>
                <el-col :span="6">
                <div style="margin:auto;width:150px;">
                    <div class="footer-top-item1">
                        <img src="/dist/images/icons1.png">
                    </div>
                    <div class="footer-top-item-font">
                        <span>全新服务</span>
                        <br/>
                        <span>极致体验</span>
                    </div>
                </div>
                </el-col>
                <el-col :span="6">
                <div style="margin:auto;width:150px;">
                    <div class="footer-top-item1">
                        <img src="/dist/images/icons4.png">
                    </div>
                    <div class="footer-top-item-font">
                        <span>购书无忧</span>
                        <br/>
                        <span>安全放心</span>
                    </div>
                </div>
                </el-col>

                </el-row>
            </div>
            <div class="footer-bottom">
            <el-row style="margin:auto;width:700px;">

                    <div class="footer-bottom-item1">
                        <el-col :span="10" ><div class="footer-bottom-1">Copyright © 2004 - 2020 云书展. All Rights Reserved</div></el-col>
                    </div>
                    <div class="footer-bottom-item1">
                        <el-col :span="1"><div class="footer-bottom-1"><img src="/dist/images/ds.png"></div></el-col>
                    </div>
                    <div class="footer-bottom-item1">
                        <el-col :span="12"><div class="footer-bottom-1">京ICP证041189号 | 出版物经营许可证 新出发京批字第直0673号</div> </el-col>
                    </div>

                </el-row>
            </div>
    </div>
    </div>
</template>

<script>
export default {
    data(){
        return{}
    }
}
</script>

<style lang="scss" scoped>
.footer{
    width:100%;
    height:207px;
    border-top:1px solid #F20E0E;

    background-color:#ffffff;
    margin: 0 auto;
    min-width: 1200px;
}
.footer-top{
    margin:auto;
    text-align: center;
    font-size:16px;
    height:100px;
    margin-top:26px;
    width:900px;
    align-items: center;
}

// .footer-top-item{
//     width:50px;
//     height:30px;
//    // border:1px solid #53D486;
// }

.footer-top-item1{
   float:left;
}
.footer-top span{
    //width:127px;
    //margin-top:9px;
    margin:0;
    color:#333333;
    font-size:9x;
    text-align: center;
    line-height: 20px;

}

.footer-top-item-font{
    float:left;
    margin-top:8px;
    margin-left:15px;
    font-weight: bold;
    color:#333333;
    font-size: 15px;
}

.footer-bottom{
    height:60px;
    margin:auto;
    text-align: center;
    width:900px;
    align-items: center;
    margin-top:6px;
}

.footer-bottom .el-col{
    font-size:3px;
    color: #B2B2B2;
}

.footer-bottom-1{
    //width:290px;
    float:left;
}

.footer-bottom .el-col-10{
    width:290px;
}
.el-main{
    overflow: unset;
    min-width: 1200px;
}
</style>





