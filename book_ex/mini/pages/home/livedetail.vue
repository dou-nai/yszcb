<template>
	<view class="page bg-white">
		<cu-custom bgColor="bg-gradual-green3" :isBack="true"><block slot="content">直播回看</block></cu-custom>
		 
		<view v-if="!isEmpty(detail)" class="cu-card case no-card">
			<view class="cu-item shadow">
				<view class="padding-0 margin-0">
					<video :url="detail.video_address" mode="widthFix" style="width: 100%;"></video>
				</view>
				<view class="padding-sm">
					<view class="text-black text-xl text-bold">{{detail.goods_name||''}}</view>
					<view class="text-grey2 text-sm">主播：{{detail.live_name||''}}
						<text style="float: right;">{{formatDate(detail.add_time,1)}}</text>
					</view>
				</view>
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
			}
		},
		onLoad(op) {
			self=this;
			if(op.id){
				self.id=op.id
			}
			self.getDetail();
			
		},
		onPullDownRefresh() {
			self.getDetail();
		},
		onReachBottom() {
			self.getDetail();
		},
		methods: {
			getDetail(){
				var data={
					 
				}
				this.GetData('/integral/goods/info/'+self.id,data,function(res){
					if(res.code==200){
						self.detail=res.data;
						self.isLoad='';
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
	.text{
		min-height: 120rpx;
	}
</style>
