<template>
	<view>
		
		 <view class="cu-bar search padding fixed bg-gradual-green3" :style="[{'padding-top':CustomBar + 'px'}]">
			<view class="search-form round" style="margin:0;background:#2b3a1e1f;height:80upx;">
				<text class="cuIcon-search text-white text-bold" style="font-size: 42upx;"></text>
				<input type="text" style="color:#FFFFFF;" disabled="" placeholder-style="color:#f1f1f1" placeholder="搜索书名" confirm-type="search" @tap="toSearch"></input>
			</view>
		 </view>
		
		<view style="height:140upx;"></view>
		<view class="VerticalBox" :style="[{'padding-top':CustomBar + 'px'}]">
			<scroll-view class="VerticalNav nav" scroll-y scroll-with-animation :scroll-top="verticalNavTop" style="height:calc(100vh - 140upx)">
				<view class="cu-item solids-bottom" style="height: 100upx;line-height: 100upx;" :style="index==tabCur?'background:unset;':'background:#fff;'" v-for="(item,index) in list" :key="index" @tap="TabSelect"
				 :data-id="index">
					<view class="" style="height:60upx;min-width: 140upx;" :class="index==tabCur?'bg-gradual-green2 cu-tag round':'text-gray3'">{{item.name}}</view>
				</view>
			</scroll-view>
			<scroll-view class="VerticalMain" scroll-y scroll-with-animation style="height:calc(100vh - 140upx)"
			 :scroll-into-view="'main-'+mainCur">
				<view class="">
					 
					<view class="grid col-2">
						<view class="padding-sm margin-bottom" v-for="(item,index) in bookList" :key="index" :data-url="'../classify/detail?id='+item.id" @tap="toUrl">
							<view class="text-center">
								<view class="cu-avatar xl book-shadow" :style="'width:200rpx;height: 260rpx;background-image:url('+item.goods_master_image+')'"></view>
							</view>
							<view class="text-black text-bold text-xl margin-top-sm text-center">
								{{item.goods_name}}
							</view>
						</view> 
					</view>
					<view class="cu-load text-gray2" :class="isLoad"></view> 
				</view>
			</scroll-view>
		</view>
		
		<cu-bar PageCur="classify"> </cu-bar>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				list: [{id:0,name:'全部'}],
				bookList:[],
				bookPage:1,
				tabCur: 0,
				mainCur: 0,
				verticalNavTop: 0,
				load: true,
				isLoad:'loading',
				CustomBar:this.CustomBar,
				StatusBar:this.StatusBar
			};
		},
		onLoad() {
			uni.showLoading({
				title: '加载中...',
				mask: true
			});
			this.getClass();
			this.getBook();
			 
			this.listCur = this.list[0];
			 
		},
		onReady() {
			uni.hideLoading()
		},
		onPullDownRefresh() {
			that.bookPage=1;
			that.getBook();
		},
		onReachBottom() {
			let that=this;
			if(that.isLoad!='more'){
				return;
			}
			that.bookPage=that.bookPage+1;
			that.getBook();
		},
		methods: {
			getClass(){
				let that=this;
				this.GetData('/api/get_goods_class_list',{},function(res){
					that.list=that.list.concat(res.data);
					
				},'get',function(){
					uni.stopPullDownRefresh();
				})
			},
			getBook(){
				let that=this;
				let data={
					page:that.bookPage,
					pageSize:20,
					class_id:that.list[that.tabCur].id,
				}
				that.isLoad='loading';
				this.GetData('/goods/search_goods',data,function(res){
					if(res.code==200){
						if(that.bookPage==1){
							that.bookList=res.data.data;
						}else{
							that.bookList=that.bookList.concat(res.data.data);
						}
					}
					if(that.bookPage>=res.data.last_page){
						that.isLoad='over';//没有更多或为空
					}else{
						that.isLoad='more';//还有更多
					}
				},'get',function(){
					uni.stopPullDownRefresh();
				})
			},
			toUrl(e){
				let url = e.currentTarget.dataset.url;
				uni.navigateTo({
					url:url
				})
			},
			toSearch(){
				uni.navigateTo({
					url:'./search'
				})
			},
			TabSelect(e) {
				this.tabCur = e.currentTarget.dataset.id;
				this.mainCur = e.currentTarget.dataset.id;
				this.verticalNavTop = (e.currentTarget.dataset.id - 1) * 50;
				 
				this.bookPage=1;
				this.getBook();
			}
		},
	}
</script>

<style>
	.fixed {
		position: fixed;
		z-index: 99;
	}

	.VerticalNav.nav {
		width: 200upx;
		white-space: initial;
	}

	.VerticalNav.nav .cu-item {
		width: 100%;
		text-align: center;
		background-color: #fff;
		margin: 0;
		border: none;
		height: 50px;
		position: relative;
	}

	.VerticalNav.nav .cu-item.cur {
		background-color: #f1f1f1;
	}

	.VerticalNav.nav .cu-item.cur::after {
		content: "";
		width: 8upx;
		height: 30upx;
		border-radius: 10upx 0 0 10upx;
		position: absolute;
		background-color: currentColor;
		top: 0;
		right: 0upx;
		bottom: 0;
		margin: auto;
	}

	.VerticalBox {
		display: flex;
	}

	.VerticalMain {
		background-color: #f1f1f1;
		flex: 1;
	}
</style>
