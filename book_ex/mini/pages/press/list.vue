<template>
	<view class="page bg-white">
		<cu-custom bgColor="bg-gradual-green3" :isBack="true"><block slot="content">出版社</block></cu-custom>
		<view class="cu-list menu margin-bottom-xl shadow-lg bg-white">
			 
			 <navigator v-if="item.goods" :url="'../press/detail?id='+item.goods_id" class="cu-item arrow solids-bottom" v-for="(item,index) in list" :key="index">
			 	<view class="content">
			 		<text class="text-black">{{index +1}}.</text>
			 		<text class="text-black margin-left-sm">{{item.goods['store_name']||''}}</text>
			 	</view>
			 </navigator>
			 <view class="cu-load text-gray2" :class="isLoad"></view> 
		</view>
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
				aid:0
			}
		},
		onLoad(op) {
			self=this;
			if(op.aid){
				self.aid=op.aid
			}
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
					'id':self.aid,
					'pageSize':20
				}
				this.GetData('/seckill/get_seckill_detail',data,function(res){
					if(res.code==200){
						if(self.pageNum==1){
							self.list=res.data.seckill_goods.data;
						}else{
							self.list=self.list.concat(res.data.seckill_goods.data);
						}
					}
					if(self.pageNum>=res.data.seckill_goods.last_page){
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
	.cu-list.menu>.cu-item:after{
		border-bottom: 0.5px solid #F9F9F9;
	}
	.cu-list.menu>.cu-item.arrow:before{
		color:#B2B2B2;
	}
	
	.solids-bottom::after {
		border-bottom: 2upx solid rgba(238, 238, 238, 1);
	}
</style>
