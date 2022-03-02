<template>
	<view class="page">
		<cu-custom bgColor="bg-gradual-green3" :isBack="true"><block slot="content">公告</block></cu-custom>
		<navigator :url="'./detail?id='+item.id" class="margin padding flex align-center radius bg-white" v-for="(item, index) in list" :key="index">
			<view class="flex-sub text-left">
				<view class="text-xl solids-bottom padding-bottom">
					<text class="text-black text-bold">{{item.adv_title}}</text>
				</view>
				<view class="text-left text-df margin-top text-grey2">{{formatDate(item.adv_start)}}</view>
			</view>
		</navigator>
		<view class="cu-load text-gray2" :class="isLoad"></view> 
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
				this.GetData('/api/get_notice',data,function(res){
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
	.text{
		min-height: 120rpx;
	}
</style>
