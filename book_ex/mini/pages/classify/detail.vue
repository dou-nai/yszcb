<template>
	<view>
		<cu-custom bgColor="bg-gradual-green3" :isBack="true"><block slot="content">图书详情</block></cu-custom>
		<view v-if="!isEmpty(detail)">
			<view class="cu-list menu-avatar">
				<view class="cu-item" style="height: 440upx;">
					<view class="cu-avatar lg book-shadow" :style="'width:260rpx;height: 360rpx;background-image:url('+detail.goods_master_image+');'"></view>
					<view class="" style="position: absolute;left:320upx;height:360upx;width:calc(100% - 96upx - 60upx - 120upx - 40upx);">
						<view class="text-black text-bold text-xxl" style="font-size: 50upx;">
							{{detail.goods_name}}
						</view>
						<view class="text-gray2 text-xl margin-tb-sm">作者：{{detail.author}}</view>
						<view>
							<view class='cu-tag radius bg-yellow2 '>精装</view>
							<view class='cu-tag radius bg-mauve2' v-if="!isEmpty(detail.class_list[2])">{{detail.class_list[2].name||''}}</view>
							<view class="margin-top-xl margin-right" style="float: right;">
								<text @tap="edit_fav" :class="islike?'cuIcon-det-bcolled':'cuIcon-det-bcoll'" class="sm text-gray2"></text>
							</view>
						</view>
					</view>
					 
				</view>
			</view>
			<view class="padding-lr padding-tb-sm margin-tb bg-white">
				<view class="cu-btn round bg-gradual-green2 margin-bottom">基本信息</view>
				<view class="margin-bottom-sm">
					<text class="text-bold text-xl text-black">书号：</text>
					<text class="text-xl text-grey2">{{detail.goods_no||''}}</text>
				</view>
				<view class="margin-bottom-sm">
					<text class="text-bold text-xl text-black">版别：</text>
					<text class="text-xl text-grey2">{{detail.edition||''}}</text>
				</view>
				<view class="margin-bottom-sm">
					<text class="text-bold text-xl text-black">出版社：</text>
					<text class="text-xl text-grey2">{{detail.store_info.store_name||''}}</text>
				</view>
				<view class="margin-bottom-sm">
					<text class="text-bold text-xl text-black">出版年份：</text>
					<text class="text-xl text-grey2">{{detail.year_publication||''}}</text>
				</view>
				<view class="margin-bottom-sm">
					<text class="text-bold text-xl text-black">定价：</text>
					<text class="text-xl text-grey2 text-price">{{detail.goods_market_price||''}}</text>
				</view>
				<view>
					<text class="text-bold text-xl text-black">销售价：</text>
					<text class="text-xl text-grey2 text-price">{{detail.goods_price||''}}</text>
				</view>
			</view>
			<view class="padding-lr padding-tb-sm margin-tb bg-white">
				<view class="cu-btn round bg-gradual-green2 margin-bottom">简介</view>
				<rich-text :nodes="detail.goods_content||''"></rich-text>
			</view>
		</view>
		<view class="cu-load text-gray2" :class="isLoad"></view> 
	</view>
</template>

<script>
	var self;
	export default {
		data() {
			return {
				detail:[],
				id:0,
				isLoad:'loading',
				islike:false,
				userid:0
			}
		},
		onLoad(op) {
			self=this;
			if(op.id){
				self.id=op.id
			}
			self.getDetail();
			if(!this.isEmpty(uni.getStorageSync('userinfo'))){
				self.is_fav(); 
			}
		},
		onPullDownRefresh() {
			self.getDetail();
		},
		onReachBottom() {
			self.getDetail();
		},
		methods: {
			is_fav(){
				let data={
					mix_id:self.id,
					is_type:0
				};
				this.GetData('/fav/is_fav',data,function(res){
					if(res.code==200){
						self.islike=true;
					}else if(res.code==500){
						self.islike=false;
					}else{
						uni.setStorageSync("userinfo",'');
						uni.setStorageSync("zhanghao",'');
						uni.setStorageSync("token",'');
						return;
					}
					 
				},'post',function(){
					uni.stopPullDownRefresh();
				})
			},
			edit_fav(){
				if(this.isEmpty(uni.getStorageSync('userinfo'))){
					uni.showToast({
					    title: '请先登录！',
					    duration: 2000,
						icon:'none'
					});
					setTimeout(()=>{
						uni.reLaunch({
							url:'/pages/my/login'
						})
					},1000)
					return;
				}
				
				let data={
					mix_id:self.id,
					is_type:0
				};
				this.GetData('/fav/edit_fav',data,function(res){
					if(res.code==200){
						self.islike=!self.islike;
						if(self.islike){
							uni.showToast({
								title: '收藏成功',
								icon: "none"
							});
						}
						if(!self.islike){
							uni.showToast({
								title: '取消收藏成功',
								icon: "none"
							});
						}
					}else{
						uni.setStorageSync("userinfo",'');
						uni.setStorageSync("zhanghao",'');
						uni.setStorageSync("token",'');
						
						uni.showToast({
							title: res.msg,
							icon: "none"
						});
						 
						return;
					}
					 
				},'post',function(){
					uni.stopPullDownRefresh();
				})
			},
			getDetail(){
				var data={
					goods_id:self.id
				}
				this.GetData('/goods/get_goods_info',data,function(res){
					if(res.code==200){
						self.detail=res.data;
						self.isLoad='';
						if(self.isEmpty(res.data)){
							uni.showToast({
							    title: '没有找到数据！',
							    duration: 2000,
								icon:'none'
							});
						}
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
	.cu-list.menu-avatar>.cu-item:after{
		border-bottom: 0px solid #ddd;
	}
</style>
