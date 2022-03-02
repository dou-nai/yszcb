<template>
	<view>
		<cu-custom bgColor="bg-gradual-green3" :isBack="true"><block slot="content">详情</block></cu-custom>
		 
		<view class="bg-gradual-green3 " :style="[{top:CustomBar + 'px'}]" style="height:300rpx;position: fixed;width:100%;z-index: 1024;">
			<view>
				<view class="padding">
					<view class="text-white text-xxl text-bold" style="float: left;">{{detail.store_name}}</view>
					<view class="cu-avatar xl margin-left" :style="'border-radius: 15rpx;float: right;background-image: url('+detail.store_logo+');'"></view>
				</view>
				<scroll-view scroll-x class="nav padding" style="height: 150upx;" scroll-with-animation>
					<view class="cu-item text-xl" data-id="0" :class="TabCur==0?'text-white examPaper-press':''" @tap="tabSelect">
						简介
					</view>
					<view class="cu-item text-xl" data-id="1" :class="TabCur==1?'text-white examPaper-press':''" @tap="tabSelect">
						版权图书（{{total}}）
					</view>
				</scroll-view>
				<view class="bg-white" style="height:40upx;width:100%;border-radius: 30upx 30upx 0 0;position: absolute;bottom:0;">
					
				</view>
			</view>
			 
		</view>
		<view style="height:300rpx"></view>
		<view  v-show="TabCur==0">
			<view class="padding-lr padding-tb-sm margin-bottom bg-white">
				<view class="cu-btn round bg-gradual-green2 margin-bottom">机构介绍</view>
				<rich-text class="text-xl text-grey2" :nodes="detail.store_description|| content"></rich-text>
			</view>
			<view class="padding-lr padding-tb-sm margin-tb bg-white">
				<view class="cu-btn round bg-gradual-green2 margin-bottom">基本信息</view>
				<view class="margin-bottom-sm">
					<text class="text-bold text-xl text-black">展区位置：</text>
					<text class="text-xl text-grey2">{{detail.hall_address||''}}</text>
				</view>
				<view class="margin-bottom-sm">
					<text class="text-bold text-xl text-black">公司名称：</text>
					<text class="text-xl text-grey2">{{detail.store_company_name||''}}</text>
				</view>
				<view class="margin-bottom-sm">
					<text class="text-bold text-xl text-black">网址：</text>
					<text class="text-xl text-grey2">{{detail.company_web||''}}</text>
				</view>
				<view class="margin-bottom-sm">
					<text class="text-bold text-xl text-black">联系电话：</text>
					<text class="text-xl text-grey2">{{detail.store_mobile||''}}</text>
				</view>
				<view class="margin-bottom-sm">
					<text class="text-bold text-xl text-black">公司地址：</text>
					<text class="text-xl text-grey2">{{detail.store_address||''}}</text>
				</view>
			</view>
		</view>
		<view class="VerticalBox" v-show="TabCur==1">
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
						<view class="padding-sm margin-bottom" v-for="(item,index) in bookList" :key="index" :data-url="'../classify/detail?id='+index" @tap="toUrl">
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
	</view>
</template>

<script>
	var self;
	export default {
		data() {
			return {
				list: [{id:0,name:'全部'}],
				total:0,
				tabCur: 0,
				mainCur: 0,
				verticalNavTop: 0,
				load: true,
				isLoad:'loading',
				CustomBar: this.CustomBar,
				TabCur:0,
				content:'<p style="text-align:left;">暂无简介</p>',
				detail:[],
				id:0,
				bookPage:1,
				bookList:[]
			};
		},
		onLoad(op) {
			self=this;
			if(op.id){
				self.id=op.id
			}
			this.getDetail();
			this.getClass();
			this.getBook();
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
					store_id:that.id
				}
				that.isLoad='loading';
				this.GetData('/goods/search_goods',data,function(res){
					if(res.code==200){
						if(that.bookPage==1){
							that.bookList=res.data.data;
						}else{
							that.bookList=that.bookList.concat(res.data.data);
						}
						that.total=res.data.total;
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
			getDetail(){
				var data={
					id:self.id
				}
				this.GetData('/store/get_store_info',data,function(res){
					if(res.code==200){
						self.detail=res.data;
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
			},
			tabSelect(e) {
				this.TabCur = e.currentTarget.dataset.id;
				this.scrollLeft = (e.currentTarget.dataset.id - 1) * 60
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
