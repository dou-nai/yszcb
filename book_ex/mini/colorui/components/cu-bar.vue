<template>
	<view>
		<view :style="[{height:Height + 'upx'}]"></view>
		<view class="cu-bar tabbar bg-white shadow foot">
			<view class="action" @click="NavChange" data-cur="home">
				<view class='cuIcon-cu-image'>
					<image :src="'/static/tabbar/ysz_tb_home' + [PageCur=='home'?'_hl':''] + '.png'"></image>
				</view>
				<view :class="PageCur=='home'?'text-black':'text-gray'">首页</view>
			</view>
			<view class="action" @click="NavChange" data-cur="classify">
				<view class='cuIcon-cu-image'>
					<image :src="'/static/tabbar/ysz_tb_class' + [PageCur == 'classify'?'_hl':''] + '.png'"></image>
				</view>
				<view :class="PageCur=='classify'?'text-black':'text-gray'">分类</view>
			</view>
			<view class="action" @click="NavChange" data-cur="activity">
				<view class='cuIcon-cu-image'>
					<image :src="'/static/tabbar/ysz_tb_act' + [PageCur == 'activity'?'_hl':''] + '.png'"></image>
				</view>
				<view :class="PageCur=='activity'?'text-black':'text-gray'">活动</view>
			</view>
			<view class="action" @click="toTiao">
				<view class='cuIcon-cu-image'>
					<image :src="'/static/tabbar/ysz_tb_bybk' + [PageCur == 'buying'?'_hl':''] + '.png'"></image>
				</view>
				<view :class="PageCur=='buying'?'text-black':'text-gray'">购书</view>
			</view>
			<view class="action" @click="NavChange" data-cur="my">
				<view class='cuIcon-cu-image'>
					<image :src="'/static/tabbar/ysz_tb_per' + [PageCur == 'my'?'_hl':''] + '.png'"></image>
				</view>
				<view :class="PageCur=='my'?'text-black':'text-gray'">我的</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		name: 'cu-bar',
		data() {
		return {
			
			}
		},
		props: {
			PageCur: {
				type: String,
				default: 'home'
			},
			Height: {
				type: Number,
				default: 120
			}
		},
		methods: {
			NavChange: function(e) {
				let PageCur = e.currentTarget.dataset.cur;
				 
			
				if(PageCur=='home'){
					uni.reLaunch({
						url:'/pages/home/index'
					})
				}else if(PageCur=='classify'){
						console.log(PageCur);
					uni.reLaunch({
						url:'/pages/classify/index'
					})
				}else if(PageCur=='activity'){
					uni.reLaunch({
						url:'/pages/activity/index'
					})
				}else if(PageCur=='my'){
					if(!this.isEmpty(uni.getStorageSync('userinfo'))){
						uni.reLaunch({
							url:'/pages/my/index'
						})
					}else{
						uni.reLaunch({
							url:'/pages/my/login'
						})
					}
					
				} 
			},
			toTiao(){
				var xcx=false;
				 switch(uni.getSystemInfoSync().platform){
				    case 'android':
				
				       console.log('运行Android上')
				
				       break;
				
				    case 'ios':
				
				       console.log('运行iOS上')
				
				       break;
				
				    default:
						xcx=true;
				       console.log('运行在开发者工具上')
				
				       break;
				
				}
				if(!xcx){
					uni.showToast({
					    title: '只有在小程序环境下才可使用该功能！',
					    duration: 2000,
						icon:'none'
					});
					return;
				}
				uni.navigateToMiniProgram({
				  appId: 'wx46c940ba5de1f3cd',
				  path: '',
				  extraData: {
				    'data1': 'test'
				  },
				  success(res) {
					 // 打开成功
					console.log(res);
				  },fail(res){
					 console.log(res);
				  }
				})
			}
		}
	}
</script>

<style>

</style>
