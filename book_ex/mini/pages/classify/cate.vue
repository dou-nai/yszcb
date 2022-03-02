<template>
	<view class="page bg-white">
		<view class="padding">
			<view class="text-right" :style="[{'padding-top':CustomBar + 'px'}]" style="height:150rpx;margin-top:20upx;">
				<text class="text-gray2 text-xl" @tap="cancel">跳过</text>
			</view>
			<view class="text-bold text-sl text-black" style="margin-top: 40rpx;">选择兴趣的书类</view>
			<view class="text-gray2 text-xl margin-tb" style="margin-top: 15rpx;">
				<rich-text nodes="选择书类（最多选择3类），<br>方便我们更好的为您提供优质服务"></rich-text>
			</view>
		</view>
		<view class="grid margin-bottom text-center margin-left">
			<view class="padding-tb-sm margin-right-sm" v-for="(item,index) in list" :key="index">
				<view :class="sel_cateid.indexOf(item.id)>-1?'bg-gradual-green2':'bg-grey3'" @tap="select(index)" class="cu-btn round lg">{{item.name}}</view>
			</view>
		</view>
		<view style="height: 200upx;"></view>
		<view class="text-center" style="bottom:100upx;left:0;right:0;position: fixed;z-index: 1024;">
			<view class="cu-btn round xl bg-gradual-green2" @tap="edit_fav" style="margin:auto;">保存</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				list:[],
				sel_cateid:[],
				sel_disabled:false,
				CustomBar:this.CustomBar,
			};
		},
		onShow() {
		
		},
		onLoad() {
			this.fav_list();
			this.getClass();
			
		},
		methods:{
			fav_list(){
				let self=this;
				let data={
					 
				};
				this.GetData('/user/edit_user_info',data,function(res){
					if(res.code==200){
						if(!self.isEmpty(res.data.like_cate_ids)){
							self.sel_cateid=res.data.like_cate_ids.split(",");
							self.sel_cateid=self.sel_cateid.map(Number); 
						}
					}else{
						uni.setStorageSync("userinfo",'');
						uni.setStorageSync("zhanghao",'');
						uni.setStorageSync("token",'');
						return;
					}
					 
				},'get',function(){
					uni.stopPullDownRefresh();
				})
			},
			edit_fav(){
				let self=this;
				if(self.sel_disabled){
					return;
				}
				if(self.sel_cateid.length<=0){
					uni.showToast({
						title: "至少选择1类",
						icon: "none"
					});
					return;
				}
				self.sel_disabled=true;
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
					like_cate_ids:self.sel_cateid.join(),
				};
				this.GetData('/user/edit_user_info',data,function(res){
					self.sel_disabled=false;
					if(res.code==200){
						uni.showToast({
							title: '保存成功',
							icon: "none"
						});
						setTimeout(()=>{
							uni.redirectTo({
								url:'../my/index'
							})
						},1000)
						
					}else{
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
			select(i){
				let that=this;
				let item=that.list[i]; 
				console.log(that.sel_cateid.indexOf(item.id)); 
			 
				if(that.sel_cateid.indexOf(item.id)>-1){
					let newCateid =[];
					for(var it of that.sel_cateid){
						console.log(it);
						if(it!=item.id){
							newCateid.push(Number(it));
						}
					}
					that.sel_cateid=newCateid;
				}else{
					
					if(that.sel_cateid.length>=3){
						uni.showToast({
							title: "最多选择3类",
							icon: "none"
						});
						return;
					}
					 
					let newCateid=that.sel_cateid;
					newCateid.push(item.id);
					that.sel_cateid=newCateid;
				}
				console.log(that.sel_cateid);
			},
			cancel(){
				//uni.navigateBack()
				uni.redirectTo({
					url:'../my/index'
				})
			},
			save(){
				uni.redirectTo({
					url:'./list'
				})
			},
			getClass(){
				let that=this;
				uni.showLoading();
				this.GetData('/api/get_goods_class_list',{},function(res){
					that.list=that.list.concat(res.data);
					uni.hideLoading();
				},'get',function(){
					uni.stopPullDownRefresh();
				})
			},
		}
	}
</script>

<style>
	.page {
		min-height: 100vh;
		height:auto;
	}
	.text-xl{
		font-size:32upx;
	}
</style>
