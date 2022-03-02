<template>
	<view class="page bg-white">
		<view style="">
			<view class="bg-gradual-green2">
				<cu-custom :isBack="false"><block slot="content">我的</block></cu-custom>
				<view style="height: 50rpx;"></view>
				
				<view :style="[{'margin-top':CustomBar + 'px'}]" class="bg-white" style="border-radius: 30upx 30upx 0 0;width: 100%;height:140upx;position: relative;">
					<view class="padding-left" style="position: absolute;top:-100upx;width: 100%;z-index: 999;">
						<!-- #ifdef H5 -->
						 <view class="cu-avatar round xl" :style="'float: left;background-image:url('+avatar+')'"></view>
						 <view class="padding-top margin-left text-xxl text-white" style="float: left;">{{yMobile(zhanghao)}}</view>
	
						<!-- #endif -->
						<!-- #ifdef MP-WEIXIN -->
						<view class="cu-avatar round xl" style="float: left; overflow:hidden;font-size: 0;">
							
							<open-data type="userAvatarUrl"></open-data>
						</view>
						
						
						<open-data class="padding-top margin-left text-xxl text-white" style="float: left;" type="userNickName"></open-data>
											 
						<!-- #endif -->
						
						
						<navigator url="./setting" class="margin-top margin-right" style="float: right;">
							<text class="sm cuIcon-per-set text-gray2"></text>
						</navigator>
						<view style="clear: both;"></view>
					</view>
					
					<view class="cu-bar padding-top-xl bg-white" style="border-radius: 30upx;width: 100%;height:120upx;">
						<view class="margin-left margin-top-xxl">
							<text class="lg text-xxl text-black text-bold">收藏图书({{total}})</text>
						</view>
					</view>
				</view>
			</view>
		</view>  
		
		<!-- <view style="height: 300upx;"></view> -->
		<view class="bg-white radius-xl padding-lr padding-bottom">
			
			<view class="cu-list menu-avatar">
				<navigator :url="'../classify/detail?id='+item.goods.id" class="cu-item" style="height: 300upx;" v-for="(item,index) in list" :key="index">
					<view class="cu-avatar lg book-shadow" :style="'width:180rpx;height: 240rpx;background-image:url('+item.goods.goods_master_image+')'"></view>
					<view class="" style="position: absolute;left:240upx;height:240upx;">
						<view class="text-black text-bold text-xxl">
							{{item.goods.goods_name}}
						</view>
						<view class="text-gray2 text-df margin-tb-sm">作者：{{item.goods.author}}</view>
						<view>
							<view class='cu-tag radius bg-yellow2 '>精装</view>
							<view class='cu-tag radius bg-mauve2' v-if="!isEmpty(item.goods.class)">{{item.goods.class.name}}</view>
						</view>
					</view>
					 
				</navigator>
			</view>
			<view class="cu-load text-gray2" :class="isLoad"></view> 
		</view>
		<cu-bar PageCur="my"> </cu-bar>  
	</view>
</template>

<script>
	let self;
	export default {
		data() {
			return {
				zhanghao: uni.getStorageSync("zhanghao"),
				userinfo: uni.getStorageSync("userinfo"),
				list:[],
				total:0,
				pageNum:1,
				isLoad:'loading',
				avatar:'/static/empty_avatar.png',
				CustomBar:this.CustomBar
			};
		},
		onShow() {
			self=this;
			if(this.isEmpty(uni.getStorageSync('userinfo'))){
				uni.reLaunch({
					url:'/pages/my/login'
				})
				return;
			}
			
			console.log(this.userinfo);
		},
		onLoad() {
			if(this.isEmpty(uni.getStorageSync('userinfo'))){
				uni.reLaunch({
					url:'/pages/my/login'
				})
				return;
			}
			self=this;
			this.getList();
		},
		onPullDownRefresh() {
			self.pageNum=1;
			self.getList();
		},
		onReachBottom() {
			if(self.isLoad!='more'){
				return;
			}
			self.pageNum=self.pageNum+1;
			self.getList();
		},
		methods:{
			getList(){
				self.isLoad='loading';
				var data={
					'page':self.pageNum,
					'pageSize':15,
					'is_type':0
				}
				this.GetData('/user/get_fav_list',data,function(res){
					if(res.code==200){
						if(self.pageNum==1){
							self.list=res.data.data;
						}else{
							self.list=self.list.concat(res.data.data);
						}
						self.total=res.data.total;
						
						if(self.pageNum>=res.data.last_page){
							self.isLoad='over';//没有更多或为空
						}else{
							self.isLoad='more';//还有更多
						}
					}else{
						uni.setStorageSync("userinfo",'');
						uni.setStorageSync("zhanghao",'');
						uni.setStorageSync("token",'');
						
						uni.showToast({
							title: res.msg,
							icon: "none"
						});
						
						setTimeout(()=>{
							uni.reLaunch({
								url:'/pages/my/login'
							})
						},1000)
						
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
	}
</style>
