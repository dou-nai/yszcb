<template>
	<view>
		<cu-custom bgColor="bg-gradual-green3" :isBack="false"><block slot="content">活动</block></cu-custom>
		<view class="cu-card case">
			<navigator class="cu-item shadow" :url="'/pages/activity/hall?id='+item.id" v-for="(item, index) in list" :key="index">
				<view class="padding-sm">
					<view class="text-black text-xl text-bold padding-tb-sm solids-bottom">{{item.name}}</view>
					<view class="text-gray3 text-sm padding-top-sm" style="clear: bold;">
						<text style="float: left;" class="lg text-green cuIcon-ysz-time margin-right-sm"></text>
						<text style="clear: bold;">{{formatDate(item.start_time,1)}} 至 {{formatDate(item.end_time,1)}}</text>
					</view>
					<view class="text-gray3 text-sm padding-top-sm" style="clear: bold;">
						<text style="float: left;" class="lg text-blue cuIcon-ysz-addr margin-right-sm"></text>
						<text style="clear: bold;">{{item.hall||'无'}}</text>
					</view>
					<view class="text-gray3 text-sm padding-top-sm" style="clear: bold;">
						<text style="float: left;" class="lg text-red cuIcon-ysz-chper margin-right-sm"></text>
						<text style="clear: bold;">{{item.guest||'无'}}</text>
					</view>
					<view class="text-gray3 text-sm padding-tb-sm" style="clear: bold;">
						<text style="float: left;" class="lg text-yellow cuIcon-ysz-press margin-right-sm"></text>
						<text style="clear: bold;">{{item.sponsor||'无'}}</text>
					</view>
				</view>
			</navigator>
		</view>
		<view class="cu-load text-gray2" :class="isLoad"></view> 
		<cu-bar PageCur="activity"> </cu-bar> 
	</view>
</template>

<script>
	
	var self;
	export default {
		data() {
			return {
				list:[],
				pageNum:1,
				isLoad:'loading',
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
			getList(){
				self.isLoad='loading';
				var data={
					'page':self.pageNum,
					'pageSize':15
				}
				this.GetData('/seckill/get_seckill_list',data,function(res){
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
