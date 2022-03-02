//let domain2 = 'https://qwshopapi.cmx666.cn';
//let domain2 = 'http://shopx.test';
let domain2 = 'https://shopx.cmx666.cn';
//let domain2 = 'http://qwShopPhp.test';
var app_name="博亚商城";

export default {
	
	info: domain2 + '/api/index/get_index_info',  //首页数据
	seckill: domain2 + '/api/seckill/get_seckill_list', //秒杀列表
	cart: domain2 + '/api/cart/get_cart_list', //购物车列表
	changeCart: domain2 + '/api/cart/change_cart', //改变数量
	delCart: domain2 + '/api/cart/del_cart', //删除购物车
	goodsInfo: domain2 + '/api/goods/get_goods_info', //商品详情
	addCart: domain2 + '/api/cart/add_cart', //加入购物车
	addressList: domain2 + '/api/address/index', //地址列表
	beforOrder: domain2 + '/api/order/get_befor_order', //确认订单
	login: domain2 + '/api/user/login', //登录
	edit: domain2 + '/api/address/edit', //修改地址
	sendSms: domain2 + '/api/user/send_sms', //发送验证码
	register: domain2 + '/api/user/register', //注册
	add: domain2 + '/api/address/add', //添加地址
	del: domain2 + '/api/address/del', //删除地址
	editInfo: domain2 + '/api/user/edit_user_info', //修改密码
	forgetInfo: domain2 + '/api/user/forget_password', //忘记密码
	order: domain2 + '/api/user/get_user_order', //订单列表
	closeOrder: domain2 + '/api/order/close_order', //取消订单
	changeOrder: domain2 + '/api/order/change_order_status', //确认收货
	refund: domain2 + '/api/order/refund', //申请售后
	addComment: domain2 + '/api/order/add_comment', //评论
	orderInfo: domain2 + '/api/order/get_order_info_by_order_id', //评论数据
	orderPl: domain2 + '/api/order/get_comment_list', //评论数据
	commentImage: domain2 + '/api/comment/comment_image', //图片上传
	delivery: domain2 + '/api/order/get_user_delivery', //查看物流
	createOrder: domain2 + '/api/order/create_order', //创建订单
	searchGoods: domain2 + '/api/goods/search_goods', //搜索商品
	brandList: domain2 + '/api/goods/get_brand_list', //品牌列表
	classList: domain2 + '/api/api/get_goods_class_list', //分类列表
	commentList: domain2 + '/api/goods/get_comment_list_by_goods', //评论列表
	pay: domain2 + '/api/order/pay', //支付接口
	wxpay: domain2 + '/api/payment/pay_ok', //支付回调
	payNh: domain2 + '/api/order/pay_nh', //农行支付
	paytest: domain2 + '/api/payment/pay_ok', //农行支付
	pay_mode:['abc','al'],
	card_info:domain2 + '/api/card/info', //卡号查询
	card_list:domain2 + '/api/card/list', //商品卡号列表
	write_off:domain2 + '/api/card/write_off', //卡号核销
	is_seller:domain2 + '/api/is_seller', //判断是否是商家
	adv:domain2 + '/api/adv', //广告活动信息
	
	check_phone:domain2 + '/api/check_phone', //验证手机
	
	
	/**
	 * 请求封装
	 * @param {Object} res
	 * @param {Object} success
	 * @param {Object} fail
	 */
	RequestData : function (res, success, fail) {
	  var that = this;
	  res.data = res.data ? res.data : {};
	  let method = res.method ? res.method : "POST";
	  let wx=false;
	  let shopToken = uni.getStorageSync('user').token ? uni.getStorageSync('user').token : "";
	  //#ifdef MP-WEIXIN
		wx=true;
	  //#endif
	  res.data["wx"]=wx;
	  uni.showLoading({
	  	title:"请求中..."
	  })
	  uni.request({
	    url: res.url,
	    data: res.data, 
	    method: method,
	    header: {
		  "Content-Type": "application/json; charset=UTF-8",
		  "Authorization": uni.getStorageSync('user').token_type + " " + shopToken
	    },
		dataType: 'json',
	    success: function (res) {
			
	      if (res.data.code == 200) {
	        success(res.data);
	      } else if(res.data.code == 401){
			  that.msg("登录过期，请重新登陆~");
			  setTimeout(()=>{
				  that.navTo("/pages/login/login")
			  },1000)
		  } else {
			  that.msg(res.data.msg,"none",3000);
			  success(res.data);
		  }
		  uni.hideLoading();
	    },
	    fail: function (e) {
	      if (fail) fail();
		  uni.hideLoading();
	    },
		complete:function (){
			uni.hideLoading();
		}
	  })
	},
	
	RequestDatas : function (res, success, fail) {
	  var that = this;
	  res.data = res.data ? res.data : {};
	  let method = res.method ? res.method : "POST";
	  let shopToken = uni.getStorageSync('user').token ? uni.getStorageSync('user').token : "";
	  uni.showLoading({
	  	title:"请求中..."
	  })
	  uni.request({
	    url: res.url,
	    data: res.data, 
	    method: method,
	    header: {
		  "Content-Type": "application/json; charset=UTF-8",
		  "Authorization": uni.getStorageSync('user').token_type + " " + shopToken
	    },
		dataType: 'json',
	    success: function (res) {
	      if (res.data.code == 200) {
	        success(res.data);
	      }
		  uni.hideLoading();
	    },
	    fail: function (e) {
	      if (fail) fail();
		  uni.hideLoading();
	    },
		complete:function (){
			uni.hideLoading();
		}
	  })
	},
	
	//获取openid
	getopendId:function(success){
		uni.login({
		  success: res => {
			  success(res.code);
		  }
		});
	},
	
	//图片上传
	uploadFile:function(tempFilePath,success){ 
		let that = this;
		that.loadings();
		uni.uploadFile({
			url: that.commentImage, 
			filePath: tempFilePath,
			name: 'file',
			header: {"Authorization": uni.getStorageSync('user').token_type + " " + uni.getStorageSync('user').token},
			success: (res) => {
				var data=JSON.parse(res.data);
				if(data.code == 200){
					success(data);
					uni.hideLoading();
				}else{									
					that.msg(data.msg);
				}
			}
		});
	},
	
	//是否为空判断
	isEmpty:function(obj){
		if(obj=='') return true;
		if(obj==null) return true;
		if(obj=='null') return true;
		if(obj===undefined) return true;
		return false;
	},
	
	
	//提示
	msg:function(message,icon,duration){
		if(!icon) icon="none";
		if(!duration) duration=1000;
		uni.showToast({
			title:message,
			icon:icon,
			duration:duration
		})
	},
	
	//模态窗
	model:function(title,content,success){
		uni.showModal({
			title: title ? title : '提示',
			content: content ? content : '这是一个模态弹窗',
			success: function (res) {
				success(res);
			}
		});
	},
	
	//等待窗
	loadings:function(title){
		uni.showLoading({
			title: title ? title : "加载中...",
		});
	},
	
	
	//保留当前页面，跳转到应用内的某个页面
	navTo:function(url){
		uni.navigateTo({
		    url: url
		});
	},
	
	//关闭当前页面，跳转到应用内的某个页面
	navCloseTo:function(url){
		uni.redirectTo({
		    url: url
		});
	},
	
	//跳转到 tabBar 页面，并关闭其他所有非 tabBar 页面
	navTab:function(url){
		uni.switchTab({
		    url: url
		});
	},
	
	//关闭所有页面，打开到应用内的某个页面
	navLaunch:function(url){
		uni.reLaunch({
		    url: url
		});
	},
	
	//关闭当前页面，返回上一页面或多级页面
	navBack:function(index){
		
		uni.navigateBack({
			delta: index
		});
	},	
	getLocalTime:function(nS) {     
		let dt=new Date(parseInt(nS) * 1000);
		return this.dateFormat("YYYY-mm-dd HH:MM:SS",dt);
	   //return new Date(parseInt(nS) * 1000).toLocaleString().replace(/:\d{1,2}$/,' ');     
	},
	
	 dateFormat:function(fmt, date) {
	    let ret;
	    const opt = {
	        "Y+": date.getFullYear().toString(),        // 年
	        "m+": (date.getMonth() + 1).toString(),     // 月
	        "d+": date.getDate().toString(),            // 日
	        "H+": date.getHours().toString(),           // 时
	        "M+": date.getMinutes().toString(),         // 分
	        "S+": date.getSeconds().toString()          // 秒
	        // 有其他格式化字符需求可以继续添加，必须转化成字符串
	    };
	    for (let k in opt) {
	        ret = new RegExp("(" + k + ")").exec(fmt);
	        if (ret) {
	            fmt = fmt.replace(ret[1], (ret[1].length == 1) ? (opt[k]) : (opt[k].padStart(ret[1].length, "0")))
	        };
	    };
	    return fmt;
	}
	
}