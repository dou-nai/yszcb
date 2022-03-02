<template>
    <div id="app">
        <div class="appt">
              <div class="boxfa"  style="width: 48%;height: 10vh;">

            <div style="margin: 20px;"><el-button v-show="false" :plain="true" @click="open4"></el-button>
            <el-button v-show="false" :plain="true" @click="open3"></el-button>
            </div>
<el-form :label-position="labelPosition" label-width="80px" :model="formLabelAlign">
  <el-form :model="ruleForm" status-icon :rules="addRules" ref="ruleForm"
   label-width="100px" class="demo-ruleForm">
  <!-- <el-form-item label="选择起始日期" prop="s_date">
    <el-input v-model.number="ruleForm.s_date" maxlength="8"
     placeholder="日期各式为20201214，默认起始日为20201214"

     ></el-input>
  </el-form-item> -->
  <div>
  <!-- <div class="block">
    <span class="demonstration">选择起始日期</span>
    <el-date-picker
      v-model="value1"
      type="date"
       value-format="timestamp"
      placeholder="选择日期">
    </el-date-picker>

  </div> -->
  <div class="block" >
    <span class="demonstration">{{title_test}}</span>
    <el-date-picker
      v-model="value1"
      type="daterange"
      range-separator="至"
      value-format="timestamp"
      :start-placeholder="start_date"
    @focus="mosinput()"
      :end-placeholder="close_date">
    </el-date-picker>
  </div>
  <div class="btnis"><el-button class="btni" type="primary" @click="submitForm('ruleForm')">提交</el-button></div>
  </div>
</el-form>
</el-form>
        </div>
       <div id="chartLineBox" style="width: 90%;height: 70vh;"> </div>

       <div class="boxf" id="chartLineBoxb" :style="styleb"> </div>
       <div class="boxf" id="chartLineBoxc" :style="styleb"> </div>
         <div class="boxf" id="chartLineBoxd" :style="styleb"> </div>
         <div class="boxf" id="chartLineBoxe" :style="styleb"> </div>
         <div class="boxf" id="chartLineBoxf" :style="styleb"> </div>
          <div class="boxf" id="chartLineBoxg" :style="styleb"> </div>
           <div class="boxf" id="chartLineBoxh" :style="styleb"> </div>
</div>
    </div>
</template>

<script>
import screenfull from 'screenfull'
const echarts = require('echarts');
export default {
    data() {
           var checks_date = (rule, value, callback) => {
               var rex = /^[1-9]{1}[0-9]*$/;
        // if (!value) {
        //   return callback(new Error('不能为空'));
        // }

          if (!Number.isInteger(value)) {
            callback(new Error('请输入数字值'));
          } else {
            if (!rex.test(value)) {
              callback(new Error('日期不能以0开头'));
            } else {
              callback();
             }
          }

         };

          var validatePass = (rule, value, callback) => {
        if (value === '') {
          callback(new Error('请输入密码'));
        } else {
          if (this.ruleForm.checkPass !== '') {
            this.$refs.ruleForm.validateField('checkPass');
          }
          callback();
        }
      };
      var validatePass2 = (rule, value, callback) => {
        if (value === '') {
          callback(new Error('请再次输入密码'));
        } else if (value !== this.ruleForm.pass) {
          callback(new Error('两次输入密码不一致!'));
        } else {
          callback();
        }
      };

    var checks_date = (rule, value, callback) => {
        var rex = /^[1-9]{1}[0-9]*$/;
        if (value === '') {
          callback(new Error('请输入起始日期'));
        } else {
          if (!Number.isInteger(value)) {
            callback(new Error('请输入数字值'));
          } else {
            if (!rex.test(value)) {
              callback(new Error('日期不能以0开头'));
            } else {
              callback();
            }
          }
        }
      };
    //   var inputs = document.getElementsByClassName("input3");
      return {
             styleb:{
                    width:'45%',
                    height:'45vh',
                },


          pickerOptions: {
          disabledDate(time) {
            return time.getTime() > Date.now();
          },
          shortcuts: [{
            text: '今天',
            onClick(picker) {
              picker.$emit('pick', new Date());
            }
          }, {
            text: '昨天',
            onClick(picker) {
              const date = new Date();
              date.setTime(date.getTime() - 3600 * 1000 * 24);
              picker.$emit('pick', date);
            }
          }, {
            text: '一周前',
            onClick(picker) {
              const date = new Date();
              date.setTime(date.getTime() - 3600 * 1000 * 24 * 7);
              picker.$emit('pick', date);
            }
          }]
        },
        value1: '',
        title_test:'默认日期',
        start_date:'20201214',
        close_date:'20201228',
        //默认日期
        default_date:new Date(),
          ruleForm: {
          pass: '',
          checkPass: '',
          age: '',
          s_date:99999999,//1607875200000
          allUpdate:'',//更新全部数据
          s_cnt:'',//	打开次数
          sts:'',//次均停留时长
          stu:'',//人均停留时长
          vn:'',//访问次数
          visit_uv:'',//访问人数
          vun:'',//新用户数
          vd:'',//平均访问深度
        },
        fullscreen: false,
        allUpdates:1,//更新全部数据
          s_cnts:1,//	更改倍数
          stss:1,//次均停留时长
          stus:1,//人均停留时长
          vns:1,//访问次数
          visit_uvs:1,//访问人数
          vuns:1,//新用户数
          vds:1,//平均访问深度

        addRules: {
          pass: [
            { validator: validatePass, trigger: 'blur' }
          ],
          checkPass: [
            { validator: validatePass2, trigger: 'blur' }
          ],
          s_date: [
            { require:true, validator: checks_date, trigger: 'blur' }
          ]
        },
           labelPosition: 'right',
        formLabelAlign: {
          name: '',
          region: '',
          type: ''
        },
        info:{
            date:'',
        },
         rules: {
                date:[
                    { required: true, message: '请输入开始日期', trigger: 'blur' },
                ],
         },
        //图表的数据
        series:[

        ],


        //每条折线的数据

        session_cnt:[],//	打开次数
        stay_time_session:[],//次均停留时长
        stay_time_uv:[],//人均停留时长
        visit_pv:[],//访问次数
        visit_uv:[],//访问人数
        visit_uv_new:[],//新用户数
        visit_depth:[],//平均访问深度
        ref_date:[],

        // arrayData:{
        //     key:1,
        //     value:198,
        // },
        arrayData:[

        ],
        scalseNum:2,
        // items={
        //     name,    //在此备注：在for循环外定义一个空数组，在for循环里定义一个对象即：{name:XX,value:XX}
        //     value,     //之后将对象循环push到定义的arry数组内
        // },
        //图表每条线的颜色
        tableColor:['#FA6F53'],
        colors: ['#8AE09F', '#FA6F53','#FDDB3A','#1F4068','#008891','#1B1B2F','#E43F5A'],
        titles:['打开次数','次均停留时长','人均停留时长','访问次数','访问人数','新用户数','平均访问深度'],
          list:[],

          dateMonth:[],
          ddate:'a14',

      };
    },
     mounted(){
      this.width_height();
      this.configs();

    },

    methods: {
        width_height(){
            var widthfh = 45;
            var heightrs = 45;
            var ji = 1;
            this.styleb.width=(widthfh*ji)+'%';
            this.styleb.height=(heightrs*ji)+'vh';


        },
        formatDate(datetime) {
    var date = new Date(datetime);//时间戳为10位需*1000，时间戳为13位的话不需乘1000
    var year = date.getFullYear(),
        month = ("0" + (date.getMonth() + 1)).slice(-2),
        sdate = ("0" + date.getDate()).slice(-2),
        hour = ("0" + date.getHours()).slice(-2),
        minute = ("0" + date.getMinutes()).slice(-2),
        second = ("0" + date.getSeconds()).slice(-2);
    // 拼接
    var result = year + ""+ month +""+ sdate;
    // 返回
    return result;
},
        open4() {
        this.$message.error('输入日期有误，请刷新页面重试！');
      },
        session_cntEdit(srtd){
            //清空原数组
            console.log(srtd);

            this.arrayData=[];
            this.session_cnt=[];
            this.stay_time_session=[];
            this.stay_time_uv=[];
            this.visit_pv=[];
            this.visit_uv=[];
            this.visit_uv_new=[];
            this.visit_depth=[];
            this.ref_date=[];
            //再从发起请求
            this.ruleForm.s_date = srtd;
            this.getStatistics(srtd)
        },
      resetForm(formName) {
        this.$refs[formName].resetFields();
      },
      //鼠标事件
        mosinput(){
           this.title_test='选择日期'
           this.start_date='开始日期'
           this.close_date='结束日期'
        },
        configs(){
          this.$get(this.$api.webConfig).then(res=>{
              this.s_cnts = res.data['cash_rate'];
          });
        },
    open3(){
        this.$message({
          message: '没有选择日期！',
          type: 'warning'
        });
    },
        submitForm:function(formName){

      if(this.value1==''){
          this.open3()
      }else{

            this.title_test='',
            this.title_test='当前日期',
            //console.log('定义鼠标移入')
            //console.log(var inputs = document.getElementsByClassName("input3"))
            this.dateMonth=[];
           var ddate='';
           this.list=[];
            //this.ddate='';
            console.log('进入验证');
        this.$refs[formName].validate((valid) => {
          if (valid) {
             // console.log('当前时间：');
             // console.log(this.value1)
             //开始日期
             var adate = this.value1['0'];
             //结束日期
             var bdate = this.value1['1'];
             var c = bdate-adate;
             //期间隔了多少天
            ddate = c/(1000*60*60*24);
            //  console.log(a);
            //  console.log(b);
              //console.log();
            this.dateMonth.push(ddate)

            //   var abd =  this.formatDate(a);
            // this.dateMonth.push(abd);
            for(var ik=0;ik<=ddate;ik++){
                //  console.log(ik);
                var adated =adate+(ik*(1000*60*60*24));
                var bd =  this.formatDate(adated);
                this.dateMonth.push(bd);
                // console.log(this.dateMonth[ik]);
            }


            this.session_cntEdit(this.dateMonth);

          } else {
            console.log('error submit!!');
            return false;
          }
        });
      }
        },
        getStatistics:function(s_date,ddate){


            this.$get(this.$api.getStatistics,this.ruleForm.s_date,ddate).then(res=>{
                if(res['code']!=200||res['msg']!='ok'){
                    this.open4()
                }else{

                //  console.log('自定义数据：');
               // console.log(res.data);

                for(var ip=0;ip<Object.keys(res.data).length;ip++){

                    this.list[ip] = res.data[ip].list['0'];

                }
                   //console.log(this.list);

                for(var i = 0;i<=Object.keys(this.list).length*3;i++){


                    this.arrayData.push(this.list[i]);
                    this.arrayData.push(i);

                    //当数据不是填充的number时就进行赋值操作
                    if(typeof(this.arrayData[i])=='object'){


                     if(null == this.arrayData[i].ref_date || 'undefined' == typeof(this.arrayData[i].ref_date) || '' == this.arrayData[i].ref_date){

                    }else{
                       this.ref_date.push(this.arrayData[i].ref_date)
                    }


                    if(null == this.arrayData[i].session_cnt || 'undefined' == typeof(this.arrayData[i].session_cnt) || '' == this.arrayData[i].session_cnt){
                        if(this.arrayData[i].session_cnt==0){
                           this.session_cnt.push(0)
                        }else{

                        }
                    }else{
                        //有倍数就使用更改的倍数（默认*1）
                        //console.log();
                        if(null == this.ruleForm.s_cnt || ''==this.ruleForm.s_cnt){
                            //console.log("使修改打开次数：")
                            // console.log(this.session_cnt);
                            this.session_cnt.push(this.arrayData[i].session_cnt*this.s_cnts)
                            //console.log(this.session_cnt);
                        }else{
                            this.session_cnt.push(this.arrayData[i].session_cnt)

                        }

                    }

                    if(null == this.arrayData[i].stay_time_session || 'undefined' == typeof(this.arrayData[i].stay_time_session) || '' == this.arrayData[i].stay_time_session){
                        if(this.arrayData[i].stay_time_session==0){
                           this.stay_time_session.push(0)
                        }else{

                        }
                    }else{
                       this.stay_time_session.push(this.arrayData[i].stay_time_session*this.s_cnts)
                    }

                     if(null == this.arrayData[i].stay_time_uv || 'undefined' == typeof(this.arrayData[i].stay_time_uv) || '' == this.arrayData[i].stay_time_uv){
                          if(this.arrayData[i].stay_time_uv==0){
                           this.stay_time_uv.push(0)
                        }else{

                        }
                    }else{
                       this.stay_time_uv.push(this.arrayData[i].stay_time_uv*this.s_cnts)
                    }


                     if(null == this.arrayData[i].visit_pv || 'undefined' == typeof(this.arrayData[i].visit_pv) || '' == this.arrayData[i].visit_pv){
                          if(this.arrayData[i].visit_pv==0){
                           this.visit_pv.push(0)
                        }else{

                        }
                    }else{
                       this.visit_pv.push(this.arrayData[i].visit_pv*this.s_cnts)
                    }
                     if(null == this.arrayData[i].visit_uv || 'undefined' == typeof(this.arrayData[i].visit_uv) || '' == this.arrayData[i].visit_uv){
                            if(this.arrayData[i].visit_uv==0){
                           this.visit_uv.push(0)
                        }else{

                        }
                    }else{
                       this.visit_uv.push(this.arrayData[i].visit_uv*this.s_cnts)
                    }

                    if(null == this.arrayData[i].visit_uv_new || 'undefined' == typeof(this.arrayData[i].visit_uv_new) || '' == this.arrayData[i].visit_uv_new){
                        if(this.arrayData[i].visit_uv_new==0){
                           this.visit_uv_new.push(0)
                        }else{
                           // i--;
                        }

                    }else{
                       this.visit_uv_new.push(this.arrayData[i].visit_uv_new*this.s_cnts)
                    }

                    if(null == this.arrayData[i].visit_depth || 'undefined' == typeof(this.arrayData[i].visit_depth) || '' == this.arrayData[i].visit_depth){
                        if(this.arrayData[i].visit_depth==0){
                           this.visit_depth.push(0)
                        }else{

                        }
                    }else{
                       this.visit_depth.push(this.arrayData[i].visit_depth)
                    }
                    }


                }

                // console.log('更改完毕');
                // console.log(this.session_cnt);
                // console.log(this.stay_time_session);
                // //console.log(this.session_cnt);
                // //console.log(this.series)
         this.chartLine = echarts.init(document.getElementById('chartLineBox'));

        // 指定图表的配置项和数据
        var option = {
            tooltip: {              //设置tip提示
                trigger: 'axis'
            },

            legend: {               //设置区分（哪条线属于什么）
                data:this.titles
            },
            color: this.colors,
                //设置区分（每条线是什么颜色，和 legend 一一对应）
            //设置x轴
            xAxis: {

                type: 'category',
                boundaryGap: false,     //坐标轴两边不留白
                data: this.ref_date,
                name: 'DATE',           //X轴 name
                nameTextStyle: {        //坐标轴名称的文字样式
                    color: this.tableColor,
                    fontSize: 16,
                    padding: [0, 0, 0, 13]
                },
                axisLine: {             //坐标轴轴线相关设置。
                    lineStyle: {
                        color: this.tableColor,
                    }
                }
            },
            //设置Y轴
            yAxis: {
                name: '数据统计',
                nameTextStyle: {
                    color: this.tableColor,
                    fontSize: 16,
                    padding: [0, 0, 10, 0]
                },
                axisLine: {
                    lineStyle: {
                        color: this.tableColor,
                    }
                },
                type: 'value'
            },
                 series: [
                {
                    name: '打开次数',
                    data: this.session_cnt,
                    type: 'line',               // 类型为折线图
                    lineStyle: {                // 线条样式 => 必须使用normal属性
                        normal: {
                            color: this.colors[0],
                        }
                    },
                },
                {
                    name: '次均停留时长',
                    data: this.stay_time_session,
                    type: 'line',               // 类型为折线图
                    lineStyle: {                // 线条样式 => 必须使用normal属性
                        normal: {
                            color:  this.colors[1],
                        }
                    },
                },
                {
                    name: '人均停留时长',
                    data: this.stay_time_uv,
                    type: 'line',               // 类型为折线图
                    lineStyle: {                // 线条样式 => 必须使用normal属性
                        normal: {
                            color:  this.colors[2],
                        }
                    },
                },
                {
                    name: '访问次数',
                    data: this.visit_pv,
                    type: 'line',               // 类型为折线图
                    lineStyle: {                // 线条样式 => 必须使用normal属性
                        normal: {
                            color:  this.colors[3],
                        }
                    },
                },
                {
                    name: '访问人数',
                    data: this.visit_uv,
                    type: 'line',               // 类型为折线图
                    lineStyle: {                // 线条样式 => 必须使用normal属性
                        normal: {
                            color:  this.colors[4],
                        }
                    },
                },
                {
                    name: '新用户数',
                    data: this.visit_uv_new,
                    type: 'line',               // 类型为折线图
                    lineStyle: {                // 线条样式 => 必须使用normal属性
                        normal: {
                            color:  this.colors[5],
                        }
                    },
                },
                {
                    name: '平均访问深度',
                    data: this.visit_depth,
                    type: 'line',               // 类型为折线图
                    lineStyle: {                // 线条样式 => 必须使用normal属性
                        normal: {
                            color:  this.colors[6],
                        }
                    },
                },

          ]
        };

        // 使用刚指定的配置项和数据显示图表。
        this.chartLine.setOption(option);

         //+++++++++++++++++++++++++++++++++
        this.chartLine = echarts.init(document.getElementById('chartLineBoxb'));

        // 指定图表的配置项和数据
        var optionb = {
            tooltip: {              //设置tip提示
                trigger: 'axis'
            },

            legend: {               //设置区分（哪条线属于什么）
                data:['打开次数']
            },
            color:  this.colors[0],
                //设置区分（每条线是什么颜色，和 legend 一一对应）
            //设置x轴
            xAxis: {

                type: 'category',
                boundaryGap: false,     //坐标轴两边不留白
                data: this.ref_date,
                name: '日期',           //X轴 name
                nameTextStyle: {        //坐标轴名称的文字样式
                    color: this.tableColor,
                    fontSize: 12,
                    padding: [0, 0, 0, 10]
                },
                axisLine: {             //坐标轴轴线相关设置。
                    lineStyle: {
                       color: this.tableColor,
                    }
                }
            },
            //设置Y轴
            yAxis: {
                name: '打开次数',
                nameTextStyle: {
                    color: this.tableColor,
                    fontSize: 12,
                    padding: [0, 0, 10, 0]
                },
                axisLine: {
                    lineStyle: {
                       color: this.tableColor,
                    }
                },
                type: 'value'
            },
            series: [
              {
                    name: '打开次数',
                    data: this.session_cnt,
                    type: 'line',               // 类型为折线图
                    lineStyle: {                // 线条样式 => 必须使用normal属性
                        normal: {
                            color: this.colors[0],
                        }
                    },
                },

          ]
        };

        // 使用刚指定的配置项和数据显示图表。
        this.chartLine.setOption(optionb);
        //---------------------------------------

        //+++++++++++++++++++++++++++++++++
         //+++++++++++++++++++++++++++++++++
        this.chartLine = echarts.init(document.getElementById('chartLineBoxc'));

        // 指定图表的配置项和数据
        var optionc = {
            tooltip: {              //设置tip提示
                trigger: 'axis'
            },

            legend: {               //设置区分（哪条线属于什么）
                data:['次均停留时长']
            },
            color:  this.colors[1],
                //设置区分（每条线是什么颜色，和 legend 一一对应）
            //设置x轴
            xAxis: {

                type: 'category',
                boundaryGap: false,     //坐标轴两边不留白
                data: this.ref_date,
                name: '日期',           //X轴 name
                nameTextStyle: {        //坐标轴名称的文字样式
                    color: this.tableColor,
                    fontSize: 12,
                    padding: [0, 0, 0, 10]
                },
                axisLine: {             //坐标轴轴线相关设置。
                    lineStyle: {
                       color: this.tableColor,
                    }
                }
            },
            //设置Y轴
            yAxis: {
                name: '次均停留时长',
                nameTextStyle: {
                    color: this.tableColor,
                    fontSize: 12,
                    padding: [0, 0, 10, 0]
                },
                axisLine: {
                    lineStyle: {
                       color: this.tableColor,
                    }
                },
                type: 'value'
            },
            series: [
             {
                    name: '次均停留时长',
                    data: this.stay_time_session,
                    type: 'line',               // 类型为折线图
                    lineStyle: {                // 线条样式 => 必须使用normal属性
                        normal: {
                            color:  this.colors[1],
                        }
                    },
                },

          ]
        };

        // 使用刚指定的配置项和数据显示图表。
        this.chartLine.setOption(optionc);
        //---------------------------------------

         //+++++++++++++++++++++++++++++++++
        this.chartLine = echarts.init(document.getElementById('chartLineBoxd'));

        // 指定图表的配置项和数据
        var optiond = {
            tooltip: {              //设置tip提示
                trigger: 'axis'
            },

            legend: {               //设置区分（哪条线属于什么）
                data:['人均停留时长']
            },
            color:  this.colors[2],
                //设置区分（每条线是什么颜色，和 legend 一一对应）
            //设置x轴
            xAxis: {

                type: 'category',
                boundaryGap: false,     //坐标轴两边不留白
                data: this.ref_date,
                name: '日期',           //X轴 name
                nameTextStyle: {        //坐标轴名称的文字样式
                    color: this.tableColor,
                    fontSize: 12,
                    padding: [0, 0, 0, 10]
                },
                axisLine: {             //坐标轴轴线相关设置。
                    lineStyle: {
                       color: this.tableColor,
                    }
                }
            },
            //设置Y轴
            yAxis: {
                name: '人均停留时长',
                nameTextStyle: {
                    color: this.tableColor,
                    fontSize: 12,
                    padding: [0, 0, 10, 0]
                },
                axisLine: {
                    lineStyle: {
                       color: this.tableColor,
                    }
                },
                type: 'value'
            },
            series: [
              {
                    name: '人均停留时长',
                    data: this.stay_time_uv,
                    type: 'line',               // 类型为折线图
                    lineStyle: {                // 线条样式 => 必须使用normal属性
                        normal: {
                            color:  this.colors[2],
                        }
                    },
                },

          ]
        };

        // 使用刚指定的配置项和数据显示图表。
        this.chartLine.setOption(optiond);
        //---------------------------------------

        //+++++++++++++++++++++++++++++++++
        this.chartLine = echarts.init(document.getElementById('chartLineBoxe'));

        // 指定图表的配置项和数据
        var optione = {
            tooltip: {              //设置tip提示
                trigger: 'axis'
            },

            legend: {               //设置区分（哪条线属于什么）
                data:['访问次数']
            },
            color:  this.colors[3],
                //设置区分（每条线是什么颜色，和 legend 一一对应）
            //设置x轴
            xAxis: {

                type: 'category',
                boundaryGap: false,     //坐标轴两边不留白
                data: this.ref_date,
                name: '日期',           //X轴 name
                nameTextStyle: {        //坐标轴名称的文字样式
                    color: this.tableColor,
                    fontSize: 12,
                    padding: [0, 0, 0, 10]
                },
                axisLine: {             //坐标轴轴线相关设置。
                    lineStyle: {
                       color: this.tableColor,
                    }
                }
            },
            //设置Y轴
            yAxis: {
                name: '访问次数',
                nameTextStyle: {
                    color: this.tableColor,
                    fontSize: 12,
                    padding: [0, 0, 10, 0]
                },
                axisLine: {
                    lineStyle: {
                       color: this.tableColor,
                    }
                },
                type: 'value'
            },
            series: [
              {
                    name: '访问次数',
                    data: this.visit_pv,
                    type: 'line',               // 类型为折线图
                    lineStyle: {                // 线条样式 => 必须使用normal属性
                        normal: {
                            color:  this.colors[3],
                        }
                    },
                },

          ]
        };

        // 使用刚指定的配置项和数据显示图表。
        this.chartLine.setOption(optione);
        //---------------------------------------

         //+++++++++++++++++++++++++++++++++
        this.chartLine = echarts.init(document.getElementById('chartLineBoxf'));

        // 指定图表的配置项和数据
        var optionf = {
            tooltip: {              //设置tip提示
                trigger: 'axis'
            },

            legend: {               //设置区分（哪条线属于什么）
                data:['访问人数']
            },
            color:  this.colors[4],
                //设置区分（每条线是什么颜色，和 legend 一一对应）
            //设置x轴
            xAxis: {

                type: 'category',
                boundaryGap: false,     //坐标轴两边不留白
                data: this.ref_date,
                name: '日期',           //X轴 name
                nameTextStyle: {        //坐标轴名称的文字样式
                    color: this.tableColor,
                    fontSize: 12,
                    padding: [0, 0, 0, 10]
                },
                axisLine: {             //坐标轴轴线相关设置。
                    lineStyle: {
                       color: this.tableColor,
                    }
                }
            },
            //设置Y轴
            yAxis: {
                name: '访问人数',
                nameTextStyle: {
                    color: this.tableColor,
                    fontSize: 12,
                    padding: [0, 0, 10, 0]
                },
                axisLine: {
                    lineStyle: {
                       color: this.tableColor,
                    }
                },
                type: 'value'
            },
            series: [
              {
                    name: '访问人数',
                    data: this.visit_uv,
                    type: 'line',               // 类型为折线图
                    lineStyle: {                // 线条样式 => 必须使用normal属性
                        normal: {
                            color:  this.colors[4],
                        }
                    },
                },

          ]
        };

        // 使用刚指定的配置项和数据显示图表。
        this.chartLine.setOption(optionf);
        //---------------------------------------

        //+++++++++++++++++++++++++++++++++
        this.chartLine = echarts.init(document.getElementById('chartLineBoxg'));

        // 指定图表的配置项和数据
        var optiong = {
            tooltip: {              //设置tip提示
                trigger: 'axis'
            },

            legend: {               //设置区分（哪条线属于什么）
                data:['新用户数']
            },
            color:  this.colors[5],
                //设置区分（每条线是什么颜色，和 legend 一一对应）
            //设置x轴
            xAxis: {

                type: 'category',
                boundaryGap: false,     //坐标轴两边不留白
                data: this.ref_date,
                name: '日期',           //X轴 name
                nameTextStyle: {        //坐标轴名称的文字样式
                    color: this.tableColor,
                    fontSize: 12,
                    padding: [0, 0, 0, 10]
                },
                axisLine: {             //坐标轴轴线相关设置。
                    lineStyle: {
                       color: this.tableColor,
                    }
                }
            },
            //设置Y轴
            yAxis: {
                name: '新用户数',
                nameTextStyle: {
                    color: this.tableColor,
                    fontSize: 12,
                    padding: [0, 0, 10, 0]
                },
                axisLine: {
                    lineStyle: {
                       color: this.tableColor,
                    }
                },
                type: 'value'
            },
            series: [
              {
                    name: '新用户数',
                    data: this.visit_uv_new,
                    type: 'line',               // 类型为折线图
                    lineStyle: {                // 线条样式 => 必须使用normal属性
                        normal: {
                            color:  this.colors[5],
                        }
                    },
                },

          ]
        };

        // 使用刚指定的配置项和数据显示图表。
        this.chartLine.setOption(optiong);
        //---------------------------------------

        //+++++++++++++++++++++++++++++++++
        this.chartLine = echarts.init(document.getElementById('chartLineBoxh'));

        // 指定图表的配置项和数据
        var optionh = {
            tooltip: {              //设置tip提示
                trigger: 'axis'
            },

            legend: {               //设置区分（哪条线属于什么）
                data:['平均访问深度']
            },
            color:  this.colors[6],
                //设置区分（每条线是什么颜色，和 legend 一一对应）
            //设置x轴
            xAxis: {

                type: 'category',
                boundaryGap: false,     //坐标轴两边不留白
                data: this.ref_date,
                name: '日期',           //X轴 name
                nameTextStyle: {        //坐标轴名称的文字样式
                    color: this.tableColor,
                    fontSize: 12,
                    padding: [0, 0, 0, 10]
                },
                axisLine: {             //坐标轴轴线相关设置。
                    lineStyle: {
                       color: this.tableColor,
                    }
                }
            },
            //设置Y轴
            yAxis: {
                name: '平均访问深度',
                nameTextStyle: {
                    color: this.tableColor,
                    fontSize: 12,
                    padding: [0, 0, 10, 0]
                },
                axisLine: {
                    lineStyle: {
                       color: this.tableColor,
                    }
                },
                type: 'value'
            },
            series: [
              {
                    name: '平均访问深度',
                    data: this.visit_depth,
                    type: 'line',               // 类型为折线图
                    lineStyle: {                // 线条样式 => 必须使用normal属性
                        normal: {
                            color:  this.colors[6],
                        }
                    },
                },

          ]
        };

        // 使用刚指定的配置项和数据显示图表。
        this.chartLine.setOption(optionh);
        //---------------------------------------
                }
            })

        },



        changes(){
            //console.log(this.value1);

        }


    },

    created() {

        this.getStatistics();
    },
};
</script>


<style lang="scss" scoped>
// .appt{
//     //transform: scale(1,1);
// //-ms-transform: scale(1,1); /* IE 9 */
//     -webkit-transform: scale(0.8,0.8); /* Safari and Chrome */
// }
.boxf{
    margin-top:50px;
    margin-bottom:50px;
    margin-left:20px;
}
.boxf{
    float:left;
}
.hone{
    margin-bottom: 40px;
}
.boxfa{
    margin-left: 7%;
}
.block{
    float: left;
}
</style>
