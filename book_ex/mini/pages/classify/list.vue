<template>
	<view class="page bg-white">
		<cu-custom bgColor="bg-gradual-green3" :isBack="true"><block slot="content">图书列表</block></cu-custom>
		<view class="cu-bar search padding bg-gradual-green3">
			<view class="search-form round" style="margin:0;background:#2b3a1e1f;height:80upx;">
				<text class="cuIcon-search text-white text-bold" style="font-size: 42upx;"></text>
				<input type="text" style="color:#FFFFFF;" v-model="keyword" placeholder-style="color:#f1f1f1" placeholder="搜索书名" confirm-type="search"  @tap="toSearch"></input>
			</view>
			<navigator url="./index" class="margin-left-sm text-white text-xl">分类</navigator>
		</view>
		<view class="padding bg-white" style="border-radius: 10upx;">
			<view class="grid col-2">
				<view class="padding-sm" v-for="(item,index) in bookList" :key="index" :data-url="'../classify/detail?id='+item.id" @tap="toUrl">
					<view class="text-center">
						<view class="cu-avatar xl book-shadow" :style="'width:200rpx;height: 260rpx;background-image:url('+item.goods_master_image+')'"></view>
					</view>
					<view class="text-black text-bold text-xl margin-top-sm">
						{{item.goods_name}}
					</view>
					<view class="text-gray2 text-sm margin-tb-xs">作者：{{item.author}}</view>
					<view>
						<view class='cu-tag radius bg-yellow2 '>精装</view>
						<view class='cu-tag radius bg-mauve2' v-if="!isEmpty(item.class)">{{item.class.name}}</view>
					</view>
				</view>
			</view>
			<view class="cu-load text-gray2" :class="isLoad"></view> 
		</view>
	</view>
</template>
<script>
	export default {
		data() {
			return {
				bookList:[],
				pageNum:1,
				isLoad:'loading',
				keyword:''
			};
		},
		onLoad(op) {
			if(op.keyword){
				this.keyword=op.keyword
			}
			this.getBook();
			
		},
		onPullDownRefresh() {
			that.pageNum=1;
			that.getBook();
		},
		onReachBottom() {
			let that=this;
			if(that.isLoad!='more'){
				return;
			}
			that.pageNum=that.pageNum+1;
			that.getBook();
		},
		methods:{
			toUrl(e){
				let url = e.currentTarget.dataset.url;
				uni.navigateTo({
					url:url
				})
			},
			getBook(){
				let that=this;
				that.isLoad='loading';
				this.GetData('/goods/search_goods',{page:that.pageNum,pageSize:20,keywords:that.keyword},function(res){
					if(res.code==200){
						if(that.pageNum==1){
							that.bookList=res.data.data;
						}else{
							that.bookList=that.bookList.concat(res.data.data);
						}
					}
					if(that.pageNum>=res.data.last_page){
						that.isLoad='over';//没有更多或为空
					}else{
						that.isLoad='more';//还有更多
					}
				},'get',function(){
					uni.stopPullDownRefresh();
				})
			},
			toSearch(){
				uni.navigateTo({
					url:'./search'
				})
			},
		}
	}
</script>

<style>
	.page {
		height: 100vh;
	}
</style>
