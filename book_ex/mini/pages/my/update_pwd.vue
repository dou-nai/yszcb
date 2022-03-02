<template>
	<view class="page bg-white">
		<cu-custom bgColor="bg-gradual-green3" :isBack="true"><block slot="content">修改密码</block></cu-custom>
		<form @submit="formSubmit">
			<view class="cu-form-group margin-top">
				<text class='cuIcon-sign-accn margin-right-xs'></text>
				<input :value="yMobile(account)" disabled="" name="account" type="text" placeholder="请输入手机号" placeholder-style="color:#B2B2B2"></input>
				<view class="cu-form-x"></view>
			</view>
			<view class="cu-form-group">
				<text class='cuIcon-sign-code margin-right-xs'></text>
				<input type="text" name="smsCode" v-model='smsCode' placeholder="请输入手机验证码" placeholder-style="color:#B2B2B2"></input>
				<view v-if="yzm && !yzm_disabled" class="title text-yellow2" @click="getYzm">获取验证码</view>
				<view v-if="yzm && yzm_disabled" class="title hui">获取验证码</view>
				<view v-if="!yzm" class="title hui">{{djs}}s 后重新发送</view>
				<view class="cu-form-x"></view>
			</view>
			<view class="cu-form-group">
				<text class='cuIcon-sign-conf margin-right-xs'></text>
				<input v-model='password' type="password" name="password" placeholder="请输入密码" placeholder-style="color:#B2B2B2"></input>
				<view class="cu-form-x"></view>
			</view>
			<view class="cu-form-group">
				<text class='cuIcon-sign-pasw margin-right-xs'></text>
				<input v-model='resPassword' type="password" name="resPassword" placeholder="请输入确认密码" placeholder-style="color:#B2B2B2"></input>
				<view class="cu-form-x"></view>
			</view>
			 <view style="">
			 	<view class="padding flex flex-direction" style="">
			 		<button formType="submit" :disabled="submit_disabled" class="cu-btn bg-gradual-green88 lg round" style="height: 100upx;width:100%;color:#fff;">提交</button>
			 	</view>
			 </view>
			  
		</form>
		
	</view>
</template>

<script>
	export default {
		data() {
			return {
				agreeColor:'#DCDCDC' ,//#F97F15   #DCDCDC
				account: uni.getStorageSync("zhanghao"), //手机号
				smsCode: "", //验证码
				password: "", //密码
				resPassword: "", //确认密码
				yzm: true, //验证码
				djs: 60 ,//倒计时
				submit_disabled:false,
				yzm_disabled:false,
			};
		},
		onShow() {
			console.log("success")
		},
		methods:{
			//手机验证码
			getYzm() {
				let that = this;
				if(that.account.length != 11){
					uni.showToast({
						title: "请输入正确的手机号",
						icon: "none"
					});
					return;
				}
				that.yzm_disabled=true;
				that.send_sms();
				return ;	
			},
			send_sms(){
				let that=this;
				this.GetData('/user/send_sms',{"phone":that.account,"is_type":2},function(res){
					uni.showToast({
						title: res.msg,
						icon: "none"
					});
					that.yzm_disabled=false;
					
					if(res.code==200){ 
						that.yzm = !that.yzm;
						that.timer = setInterval(() => {
							if (that.djs == 0) {
								that.yzm = !that.yzm;
								that.djs = 60;
								clearInterval(that.timer);
							}
							that.djs = that.djs - 1;
						}, 1000);
					}
				},'post',function(){
					uni.stopPullDownRefresh();
				})
			},
			//确认找回
			formSubmit(e) {
				let that = this;
				//进行表单检查
				var formData = e.detail.value;
				
				if(this.isEmpty(that.account)){
					uni.showToast({
						title: '请输入手机号!',
						icon: "none"
					});
					return;
				}
				if(that.account.length != 11){
					uni.showToast({
						title: "请输入正确的手机号",
						icon: "none"
					});
					return;
				}
				if(this.isEmpty(formData.smsCode)){
					uni.showToast({
						title: '请输入验证码!',
						icon: "none"
					});
					return;
				}
				if(this.isEmpty(formData.password)){
					uni.showToast({
						title: '请输入密码!',
						icon: "none"
					});
					return;
				}
				if(this.isEmpty(formData.resPassword)){
					uni.showToast({
						title: '请输入确认密码!',
						icon: "none"
					});
					return;
				}
				
				if(formData.password!=formData.resPassword){
					uni.showToast({
						title: '两次密码不一致!',
						icon: "none"
					});
					return;
				}
				let data={
					phone:that.account,
					password:formData.password,
					code:formData.smsCode
				};
				that.submit_disabled=true;
				this.GetData('/user/forget_password',data,function(res){ 
					that.submit_disabled=false;
					if(res.code==200){
						uni.showToast({
							title: "重置成功",
							icon: "success"
						});
						setTimeout(()=>{
							uni.setStorageSync("userinfo",'');
							uni.setStorageSync("zhanghao",'');
							uni.setStorageSync("token",'');
							
							uni.reLaunch({
								url:'/pages/my/login'
							})
						},1000)
					}else{
						uni.showToast({
							title: res.msg,
							icon: "none"
						});
						return;
					}
				},'post',function(){
					uni.stopPullDownRefresh();
				})
				 
			}
		}
	}
</script>

<style>
	.page {
		height: 100vh;
		width:100vw;
	}
	.cu-form-group{
		position: relative;
		border-top:0px solid #eee;
	}
	.cu-form-x{
		width:calc(100% - 120upx);
		height: 0.5upx;
		background-color: #EEEEEE;
		position: absolute;
		bottom:0.5upx;
		margin-left:50upx;
	}
	.cu-form-group+.cu-form-group {
		border-top:0px solid #eee;
	}
</style>
