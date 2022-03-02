<template>
	<view class="page">
		<cu-custom bgColor="bg-gradual-green3" :isBack="true"><block slot="content">线上直播</block></cu-custom>
		<view class="cu-card case">
			<view class="cu-item shadow" v-for="(item, index) in list" :key="index">
				<view :data-url="'./livedetail?id='+item.id" @tap="toUrl" class="image">
					<image :src="item.goods_master_image"
					 mode="widthFix"></image>
					<view v-if="item.live_status==1" class="cu-tag bg-gradual-red2" style="top:10upx;left:10upx;right:unset;">直播中</view>
					<view v-if="item.live_status!=1" class="cu-tag bg-gradual-blue2" style="top:10upx;left:10upx;right:unset;">回放</view>
					<view class="cu-bar bg-shadeBottom"></view>
					<view style="position: absolute;top:50%; left:50%; margin-top: -64upx;;margin-left: -64upx;">
						<view class="cu-avatar xl round" :style="'background-color:unset;background-image: url('+ysz_liv_play+');'">
							
						</view>
					</view>
				</view>
				<view class="padding-sm">
					<view class="text-black text-xl text-bold">{{item.goods_name}}</view>
					<view class="text-gray2 text-sm">主播：{{item.live_name}}</view>
					
				</view>
				 
			</view>
			<view class="cu-load text-gray2" :class="isLoad"></view> 
		</view>
	</view>
</template>

<script>
	var self;
	var ysz_liv_play='data:image/png;base64,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';
	
	export default {
		data() {
			return {
				list:[],
				pageNum:1,
				isLoad:'loading',
				ysz_liv_play:ysz_liv_play
			}
		},
		onLoad() {
			self=this;
			self.getList();
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
		methods: {
			toUrl(e){
				let url = e.currentTarget.dataset.url;
				uni.navigateTo({
					url:url
				})
			},
			getList(){
				self.isLoad='loading';
				var data={
					'page':self.pageNum,
					'pageSize':15
				}
				this.GetData('/integral/goods/search_integral_goods',data,function(res){
					if(res.code==200){
						if(self.pageNum==1){
							self.list=res.data.data;
						}else{
							self.list=self.list.concat(res.data.data);
						}
					}
					if(self.pageNum>=res.data.last_page){
						self.isLoad='over';//没有更多或为空
					}else{
						self.isLoad='more';//还有更多
					}
				},'get',function(){
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
