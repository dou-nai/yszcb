<template>
	<view class="page bg-white">
		<cu-custom bgColor="bg-gradual-green3" :isBack="true"><block slot="content">设置</block></cu-custom>
		<view class="cu-list menu padding-tb padding-lr-sm shadow-lg bg-white">
			<view class="cu-item" >
				<view class="content">
					<text class="text-grey2">我的账号</text>
				</view>
				<view class="action">
					<text class="text-gray2">{{yMobile(zhanghao)}}</text>
				</view>
			</view>
			<navigator url="/pages/classify/cate" class="cu-item arrow" >
				<view class="content">
					<text class="text-grey2">兴趣书类</text>
				</view>
				<view class="action">
					 
				</view>
			</navigator>
			<navigator url="./update_pwd" class="cu-item arrow" >
				<view class="content">
					<text class="text-grey2">修改密码</text>
				</view>
				<view class="action">
					 
				</view>
			</navigator>
			<view class="cu-item"></view>
		</view>
		<view style="position: fixed;width:100%;bottom:30upx;">
			<view class="padding flex flex-direction" style="">
				<view class="cu-btn bg-grey-red lg round" @tap="logout()" style="height: 100upx;width:100%">退出账号</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				agreeColor:'#DCDCDC' ,//#F97F15   #DCDCDC
				lookmm:false,
				zhanghao: uni.getStorageSync("zhanghao"),
				userinfo: uni.getStorageSync("userinfo"),
			};
		},
		onShow() {
			console.log("success")
		},
		methods:{
			//退出账号
			logout(){
				uni.setStorageSync("userinfo",'');
				uni.setStorageSync("zhanghao",'');
				uni.setStorageSync("token",'');
				uni.reLaunch({
					url:'/pages/my/login'
				})
			},
		}
	}
</script>

<style>
	.page {
		height: 100vh;
		width:100vw;
	}
	
	.cu-list.menu>.cu-item.arrow:before{
		color:#B2B2B2;
	}
</style>
