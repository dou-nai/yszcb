<template>
	<view>
		<cu-custom bgColor="bg-gradual-green3" :isBack="true"><block slot="content">搜索</block></cu-custom>
		<view class="cu-bar search padding bg-gradual-green3">
			<view class="search-form round" style="margin:0;background:#2b3a1e1f;height:80upx;">
				<text class="cuIcon-search text-white text-bold" style="font-size: 42upx;"></text>
				<input type="text" style="color:#FFFFFF;" placeholder-style="color:#f1f1f1" v-model="keyword" placeholder="搜索书名" confirm-type="search" @confirm="searchBook"></input>
			</view>
			<navigator url="./index" class="margin-left-sm text-white text-xl">分类</navigator>
		</view>
		<view class="bg-white">
			<view class="cu-bar margin-left">
				<view>
					<text class="lg text-xxl text-black text-bold">搜索历史</text>
				</view>
				<view class="action" @tap="reset">
					<text class="sm cuIcon-delete text-gray2 margin-right-xs"></text><text class="text-gray3 text-df">清除</text>
				</view>
			</view>
			<view class="grid margin-bottom text-center">
				<view class="padding-tb-sm margin-lr-xs" v-if="!isEmpty(item)" v-for="(item,index) in historySearch" :key="index" @tap="tabSelect" data-type="1" :data-id="index">
					<view class="cu-btn round" :class="index==TabCur1?'bg-gradual-green2':'bg-grey2'">{{item}}</view>
				</view>
			</view>
			<view class="cu-bar margin-left">
				<view>
					<text class="lg text-xxl text-black text-bold">热门搜索</text>
				</view>
			</view>
			<view class="grid margin-bottom text-center">
				<view class="padding-tb-sm margin-lr-xs" style="position: relative;" v-for="(item,index) in hotList" :key="index" @tap="tabSelect" data-type="2" :data-id="index">
					<view class="cu-btn round" :class="index==TabCur2?'bg-gradual-green2':'bg-grey2'">{{item.spec_name}}</view>
					<view class="re-tag" style="top:-2upx;right:-20upx;left:unset;">热</view>
				</view>
			</view>
		</view>
		
	</view>
</template>

<script>
	export default {
		data() {
			return {
				hotList:[],
				historySearch:[],
				TabCur1:-1,
				TabCur2:-1,
				keyword:''
			};
		},
		onLoad() {
			this.getHot();
			let historySearch=uni.getStorageSync('historySearch');
			if(!this.isEmpty(historySearch)){
				this.historySearch=historySearch;
			}
		},
		methods: {
			searchBook(e){
				let key = e.detail.value.toLowerCase();
				
				let historySearch=this.historySearch;
				if(historySearch.indexOf(key)==-1){
					historySearch.push(key);
					uni.setStorageSync('historySearch',historySearch);
					
				}
				this.keyword=key;
				uni.navigateTo({
					url:'./list?keyword='+key
				})
				console.log(key);
			},
			getHot(){
				let that=this;
				this.GetData('/api/get_attr_spec',{},function(res){
					that.hotList=res.data;
					 console.log(res);
				},'get',function(){
					uni.stopPullDownRefresh();
				})
			},
			reset(){
				this.historySearch=[];
				uni.setStorageSync('historySearch',[]);
			},
			tabSelect(e) {
				let TabCur = e.currentTarget.dataset.id;
				let type = e.currentTarget.dataset.type;
				let key='';
				this.TabCur1=-1;
				this.TabCur2=-1;
				if(type==1){
					this.TabCur1=TabCur;
					key=this.historySearch[TabCur];
				}else if(type==2){
					this.TabCur2=TabCur;
					key=this.hotList[TabCur].spec_name;
				}
				let historySearch=this.historySearch;
				if(historySearch.indexOf(key)==-1){
					historySearch.push(key);
					uni.setStorageSync('historySearch',historySearch);
				}
				this.keyword=key;
				uni.navigateTo({
					url:'./list?keyword='+key
				})
			},
		}
	}
</script>

<style>
</style>
