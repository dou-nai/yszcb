import Vue from 'vue'
import App from './App'

import mixin from '@/static/js/mixins/base.js'
Vue.mixin(mixin)

import cuBar from './colorui/components/cu-bar.vue'
Vue.component('cu-bar',cuBar)

import cuCustom from './colorui/components/cu-custom.vue'
Vue.component('cu-custom',cuCustom)

Vue.config.productionTip = false

//Vue.prototype.ApiUrl= "https://ysz.cmx666.cn/api";
Vue.prototype.ApiUrl= "http://192.168.1.102:8082/api";

Vue.prototype.GetData=function(url,data,callback,method,completeback,isjz){
 
	var token=uni.getStorageSync("token");
	if(data==null) 		data={};	
	if(data.hasOwnProperty("token")==false)
	{
		data.token=token;
	}	                 	
	var _method="POST";
	if(method!=null)
	{
		_method=method;
	}
	if(isjz==99){
		uni.showLoading();
	}
	
	uni.request({
		url: this.ApiUrl + url,
		method:_method,
		header: {
			'Authorization': 'Bearer '+ token,  //自定义请求头信息
			"token":token
		},
		data:data,
		success: (res) => {	
			console.log(res);
			uni.hideLoading()
			if(this.isEmpty(res.data)) return;
			if(res.data.code==1004)
			{
				console.log('错误');	
			}
			if(typeof callback == "function") 
				callback(res.data);
		},
		complete: () => {
				if(typeof completeback == "function")
					completeback();	 
		},fail: (res) => {
		 
		   uni.hideLoading()
		}
	});
 }
 Vue.prototype.isEmpty=function(obj){
 	if(obj=='') return true;
 	if(obj==null) return true;
 	if(obj==undefined) return true;
 	return false;
 }
 
 Vue.prototype.checkMobile=function(str){
     var mobile_reg = /^(13[0-9]|14[0-9]|17[0-9]|15[0-9]|18[0-9]|19[0-9]|16[0-9])\d{8}$/i;//手机正则
 	if(mobile_reg.test(str) === false){
 		return false;
 	} 
 	return true;
 }
 
 //时间戳转换方法    date:时间戳数字
 Vue.prototype.formatDate=function(num,type=0) {
	
	let date = new Date(num*1000);
	 console.log(date);
	//时间戳为10位需*1000，时间戳为13位的话不需乘1000
	let y = date.getFullYear();

	let MM = date.getMonth() + 1;

	MM = MM < 10 ? ('0' + MM) : MM;//月补0

	let d = date.getDate();

	d = d < 10 ? ('0' + d) : d;//天补0

	let h = date.getHours();

	h = h < 10 ? ('0' + h) : h;//小时补0

	let m = date.getMinutes();

	m = m < 10 ? ('0' + m) : m;//分钟补0

	let s = date.getSeconds();

	s = s < 10 ? ('0' + s) : s;//秒补0
	if(type==0){
		return y + '-' + MM + '-' + d + ' ' + h + ':' + m;
	}else if(type==1){
		return y + '-' + MM + '-' + d;
	}
	
 }
 Vue.prototype.yMobile=function(cellValue) {
    if (Number(cellValue) && String(cellValue).length === 11) {
        var mobile = String(cellValue)

        var reg = /^(\d{3})\d{4}(\d{4})$/

        return mobile.replace(reg, '$1****$2')

    } else {
        return cellValue

    }
}

App.mpType = 'app'

const app = new Vue({
    ...App
})
app.$mount()

 



