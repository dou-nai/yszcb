<template>
    <div class="qingwu">
        <el-row :gutter="20">
            <el-col :span="16" class="default_block_col">
                <el-card shadow="hover" :body-style="{padding:'15px 20px'}">
                    快速入口
                    <i
                        style="float: right; margin: 3px 0 10px 0;font-size: 18px;"
                        class="el-icon-refresh"
                    ></i>
                    <div class="unline"></div>
                   <div class="default_program2">
                        <ul>
                            <li>
                            <a href="/Admin/goods_brand/index">
                                <div class="default_sq">
                                    <el-tag>展厅列表</el-tag>
                                </div>
                                <p></p>
                            </a>
                            </li>
                            <li>
                             <a href="/Admin/seckill/index">
                                <div class="default_sq">
                                    <el-tag type="success">活动列表</el-tag>
                                </div>
                                <p></p>
                            </a>
                            </li>
                             <li>
                              <a href="/Admin/goods/index">
                                <div class="default_sq">
                                    <el-tag type="danger">图书列表</el-tag>
                                </div>
                                <p></p>
                            </a>
                            </li>
                                <li>
                             <a href="/Admin/store/index">
                                <div class="default_sq">
                                    <el-tag>出版社列表</el-tag>
                                </div>
                                <p></p>
                            </a>
                            </li>
                            <li>
                             <a href="/Admin/integral/index">
                                <div class="default_sq">
                                    <el-tag type="success">直播列表</el-tag>
                                </div>
                                <p></p>
                            </a>
                            </li>
                            <li>
                             <a href="/Admin/users/index">
                                <div class="default_sq">
                                    <el-tag type="danger">用户管理</el-tag>
                                </div>
                                <p></p>
                            </a>
                            </li>
                        </ul>
                    </div>
                </el-card>
            </el-col>

            <el-col :span="8" class="default_block_col">
                <el-card shadow="hover" :body-style="{padding:'15px 20px'}">
                    版本信息
                    <div class="unline" style="margin-bottom: 0px;"></div>
                    <div class="default_copyright">
                        <ul>
                            <li>
                                <span>当前版本：</span>
                                <el-tag type="info">v 1.0.0</el-tag>
                            </li>
                            <li>
                                <span>系统框架：</span> 云书展系统
                            </li>
                            <li>
                                <span>小程序码：</span>

                                    <img :src="require('@/public/login/qrcode.jpg')" style="width:50px;height:50px;float: right;margin-right:130px;margin-top:10px;" >

                            </li>
                        </ul>
                    </div>
                </el-card>
            </el-col>
        </el-row>

        <el-row :gutter="20">
            <el-col :span="24" class="default_block_col">
                <el-card shadow="hover" :body-style="{padding:'15px 20px'}">
                    入驻会员
                    <i
                        style="float: right; margin: 3px 0 10px 0;font-size: 18px;"
                        class="el-icon-refresh"
                    ></i>
                    <div class="unline"></div>
                    <!-- 图表 -->
                    <div id="myChart" :style="{width:'100%',height:'250px'}"></div>
                </el-card>
            </el-col>

            <!-- <el-col :span="8" class="default_block_col">
                <el-card shadow="hover" :body-style="{padding:'15px 20px'}">
                    授权信息
                    <i
                        style="float: right; margin: 3px 0 10px 0;font-size: 18px;"
                        class="el-icon-refresh"
                    ></i>
                    <div class="unline"></div>
                </el-card>
            </el-col> -->
        </el-row>
    </div>
</template>

<script>
import echarts from "echarts";
export default {
    components: {},
    props: {},
    data() {
        return {
            info:{},
            week:[0,0,0,0,0,0,0],
            week2:[0,0,0,0,0,0,0],
            month:[],
        };
    },
    watch: {},
    methods: {
        get_info:function(){
            this.$get(this.$api.adminGetStatistics).then(res=>{
                this.info = res.data;
                this.week = [];
                this.week2 = [];
                this.month = [];
                res.data.week.forEach(res=>{
                    this.week.push(res.users);
                });
                res.data.week2.forEach(res=>{
                    this.week2.push(res.users);
                });
                res.data.month.forEach(res=>{
                    this.month.push(res.price);
                });
                this.echartInit();
            });
        },
        echartInit:function(){
            /*ECharts图表*/
            var myChart = echarts.init(document.getElementById("myChart"));
            myChart.setOption({
                title: { text: "会员趋势" },
                tooltip: { trigger: "axis" },

                color:["#E6A23C","#000"],
                grid: { left: "3%", right: "4%", bottom: "8%", containLabel: true },
                toolbox: { feature: { saveAsImage: {} } },
                xAxis: {
                    type: "category",
                    boundaryGap: false,
                    data: ["周一", "周二", "周三","周四","周五","周六","周日",]
                },
                yAxis: { type: "value" },

                series: [
                    {
                        name: "现周",
                        type: "line",
                        stack: "总量2",
                        data:this.week,
                    },
                    {
                        name: "上周",
                        type: "line",
                        stack: "总量",
                        data: this.week2,
                    }
                ]
            });

            /*ECharts图表*/
            var myChart2 = echarts.init(document.getElementById("myChart2"));
            myChart2.setOption({
                color: "#409EFF",
                title: { text: "销售趋势" },
                legend: {
                    data: ["销量"]
                },
                tooltip: { trigger: "axis" },
                grid: { left: "0%", right: "0%", bottom: "0%", containLabel: true },
                toolbox: { feature: { saveAsImage: {} } },
                xAxis: {
                    data: [
                        "01",
                        "02",
                        "03",
                        "04",
                        "05",
                        "06",
                        "07",
                        "08",
                        "09",
                        "10",
                        "11",
                        "12"
                    ]
                },
                yAxis: { type: "value" },
                series: [
                    {
                        name: "销量",
                        type: "bar",
                        stack: "总量2",
                        data: this.month
                    }
                ]
            });
        }
    },
    created() {

    },
    mounted(){
        this.get_info();


    }
};
</script>
<style lang="scss" scoped>
.unline {
    margin: 15px 0;
}
.default_program {
    text-align: center;
    width: 100%;
}
.default_program ul li {
    float: left;
    width: 22%;
    background: #f9f9f9;
    margin-right: 4%;
    margin-bottom: 10px;
}


.default_program ul li:nth-child(4n) {
    margin-right: 0;
}
.default_program ul li:hover {
    background: #f1f1f1;
}
.default_program2 ul li {
    float: left;
    width: 29%;
    background: #f9f9f9;
    margin-right: 4%;
    margin-bottom: 18px;
    height: 82px;
    padding: 10px;
    box-sizing: border-box;
    font-size: 32px;
    color: #999;
    text-align: center;
    line-height: 62px;
}

.default_program2 ul li .el-tag{
    height:50px;
    font-size: 24px;
    line-height: 50px;
}
.default_program2 ul li:nth-child(2n) {
    // margin-right: 0;
}
.default_program2 ul li:nth-child(3) {
    // width: 100%;
}
.default_program2 ul li:hover {
    background: #f3f3f3;
}
.default_program .i_backgraounds {
    text-align: center;
    margin: 0 auto;
    display: block;
    padding: 5px 0;
}
.default_program2 p {
    line-height: 55px;
    font-size: 22px;
    color: #303133;
}
.i_backgraounds i {
    font-size: 28px;
}
.default_program p {
    text-align: center;
    background: #fff;
    line-height: 30px;
    font-size: 12px;
    clear: both;
}
.default_block_col {
    margin-bottom: 20px;
}
.default_copyright ul li {
    line-height: 66px;
    border-bottom: 1px solid #efefef;
}
.default_copyright ul li:nth-child(3) {
    border-bottom: none;
}
.default_copyright ul li span {
    margin-right: 60px;
}
.default_tongbi_left {
    float: left;
}
.default_tongbi_right {
    float: left;
    margin-left: 40px;
}
.default_tongbi:after {
    clear: both;
    content: "";
    display: block;
}
.default_total:after {
    clear: both;
    content: "";
    display: block;
}
.default_tongbi {
    margin-top: 20px;
}
.default_day_sale {
    margin-top: 15px;
    margin-bottom: 21px;
}
.default_tubiao {
    width: 100%;
    margin-bottom: 20px;
}
.default_hot_goods ul li {
    margin-top: 20px;
    overflow: hidden;
}
.default_hot_goods ul li span {
    border-radius: 50%;
    background: #f5f5f5;
    width: 20px;
    height: 20px;
    text-align: center;
    display: block;
    float: left;
    margin-right: 8px;
}
.default_hot_goods ul li:nth-child(1) span {
    background: #314659;
    color: #fff;
}
.default_hot_goods ul li:nth-child(2) span {
    background: #314659;
    color: #fff;
}
.default_hot_goods ul li:nth-child(3) span {
    background: #314659;
    color: #fff;
}
</style>
