<template>
	<view class="page bg-white">
		<cu-custom bgColor="bg-gradual-green3" :isBack="true"><block slot="content">公告详情</block></cu-custom>
		<view v-if="!isEmpty(detail)" class="padding">
			<view class="text-xxl text-bold text-black">
				{{detail.adv_title}}
			</view>
			<view class="text-df text-grey2 margin-top-sm margin-bottom">{{formatDate(detail.adv_start)}}</view>
			<view class="text-xl text-black" style="line-height: 60upx;">
				<rich-text :nodes="detail.adv_content"></rich-text>
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
				save_history:true
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
			// 存储数据记录
			save_history_fun:function(notice_info){
				this.save_history=false;
				let notice_json = uni.getStorageSync('show_notice_historys');
				let notice_list = [];
	
				if(!this.isEmpty(notice_json)){
					notice_list = JSON.parse(notice_json);
				}
	
				let have_his = false;
				if(notice_list.length>0){
					notice_list.forEach(res=>{
						if(notice_info.id == res.id){
							res.id = notice_info.id;
							have_his = true;
						}
					})
				}
				// console.log(goods_list)
	
				if(!have_his){
					notice_list.push({id:notice_info.id});
				}
	
				if(notice_list.length>4){
					notice_list.splice(0,1);
				}
	
				let json_str = JSON.stringify(notice_list);
				uni.setStorageSync('show_notice_historys',json_str);
				console.log(notice_list);
			},
			getDetail(){
				var data={
					id:self.id
				}
				this.GetData('/api/get_notice_detail',data,function(res){
					if(res.code==200){
						self.detail=res.data;
						self.isLoad='';
						if(self.isEmpty(res.data)){
							uni.showToast({
							    title: '没有找到数据！',
							    duration: 2000,
								icon:'none'
							});
						}
						
						// 存储游览记录
						if(self.save_history){
							self.save_history_fun(self.detail);
						}
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
