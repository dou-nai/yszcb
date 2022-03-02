<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                   
                </div>

                <div class="admin_main_block_right">
                   
                </div>
            </div>

            <div class="admin_table_main">
                <el-table :data="list" @selection-change="handleSelectionChange" >
                    <el-table-column type="selection"></el-table-column>
                    <el-table-column prop="id" label="#" fixed="left" width="70px"></el-table-column>
                    <el-table-column prop="nickname" label="用户昵称" width="300px">
                        <template slot-scope="scope">
                            <dl class="table_dl">
                                <dt><img :src="scope.row.avatar||require('@/assets/default_avatar.jpg')" width="50px" height="50px"></dt>
                                <dd class="table_dl_dd_all_30">用户昵称： {{ scope.row.nickname }}</dd>
                                <dd class="table_dl_dd_all_16_gray">注册时间： {{ scope.row.add_time|formatDate}}</dd>
                            </dl>
                        </template>
                    </el-table-column>
                    <el-table-column prop="agentid" label="邀请人" width="300px">
                        <template slot-scope="scope">
                           
                            <dl class="table_dl" v-if="scope.row.inviter_user">
                                <dt><img :src="scope.row.inviter_user.avatar||require('@/assets/default_avatar.jpg')" width="50px" height="50px"></dt>
                                <dd class="table_dl_dd_all_30">用户昵称： {{ scope.row.inviter_user.nickname }}</dd>
                                <dd class="table_dl_dd_all_16_gray">注册时间： {{ scope.row.inviter_user.add_time|formatDate}}</dd>
                            </dl>
                           
                            <dl class="table_dl" v-else>
                                <dd>无</dd>
                            </dl>
                            
                        </template>
                    </el-table-column>
                    <el-table-column prop="down" label="下线">
                        <template slot-scope="scope">
                            <dl class="table_dl">
                                <dd class="table_dl_dd_all_16" style="color:blue;">一级：{{ scope.row.down_num1 || 0 }}人</dd>
                                <dd class="table_dl_dd_all_16" style="color:green;">二级：{{ scope.row.down_num2 || 0 }}人</dd>
                                <dd class="table_dl_dd_all_16" style="color:red;">三级：{{ scope.row.down_num3 || 0 }}人</dd>
                            </dl>
                        </template>
                    </el-table-column>
                    <el-table-column prop="fxmoney" label="返利余额">
                        <template slot-scope="scope">
                            <dl class="table_dl">
                                <dd class="table_dl_dd_all_16" style="color:blue;">累计： {{ scope.row.tongji.fxmoney || '0.00' }}</dd>
                                <dd class="table_dl_dd_all_16" style="color:green;">已发放： {{ scope.row.tongji.yfxmoney || '0.00' }}</dd>
                                <dd class="table_dl_dd_all_16" style="color:red;">待发放： {{ scope.row.tongji.wfxmoney || '0.00' }}</dd>
                            </dl>
                        </template>  
                    </el-table-column>
                    <el-table-column prop="fxintegral" label="返利积分">
                        <template slot-scope="scope">
                            <dl class="table_dl">
                                <dd class="table_dl_dd_all_16" style="color:blue;">累计：{{ scope.row.tongji.fxintegral || '0.00' }}</dd>
                                <dd class="table_dl_dd_all_16" style="color:green;">已发放：{{ scope.row.tongji.yfxintegral || '0.00' }}</dd>
                                <dd class="table_dl_dd_all_16" style="color:red;">待发放：{{ scope.row.tongji.wfxintegral || '0.00' }}</dd>
                            </dl>
                        </template>  
                    </el-table-column>
                    <el-table-column prop="fxintegral" label="分销订单">
                        <template slot-scope="scope">
                            <dl class="table_dl">
                                <dd class="table_dl_dd_all_16" style="color:blue;">累计： {{ scope.row.tongji.fxorder || 0 }}笔</dd>
                                <dd class="table_dl_dd_all_16" style="color:green;">已结算： {{ scope.row.tongji.yfxorder || 0 }}笔</dd>
                                <dd class="table_dl_dd_all_16" style="color:red;">未结算： {{ scope.row.tongji.wfxorder || 0 }}笔</dd>
                            </dl>
                        </template>  
                    </el-table-column>
                </el-table>
                <div class="admin_table_main_pagination">
                    <el-pagination @current-change="current_change" background layout="prev, pager, next,jumper,total" :total="total_data" :page-size="page_size" :current-page="current_page"></el-pagination>
                </div>
            </div>
        </div>

        <!-- 调整资金弹出框 -->
        <el-dialog title="调整资金" :visible.sync="changeShow" width="30%">
            <el-form ref="form"  label-width="90px">
                <el-form-item label="改变对象：">
                    <el-select v-model="change_info.change_type" placeholder="请选择">
                        <el-option label="余额" :value="0"></el-option>
                        <el-option label="积分" :value="1"></el-option>
                        <el-option label="冻结资金" :value="2"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="类型：">
                    <el-radio-group v-model="change_info.type">
                        <el-radio :label="1">增加</el-radio>
                        <el-radio :label="0">减少</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="数量：">
                    <el-input v-model="change_info.money"></el-input>
                </el-form-item>
            </el-form>
            
       
            <span slot="footer" class="dialog-footer">
                <el-button @click="changeShow = false">取 消</el-button>
                <el-button type="primary" @click="change_start()">确 定</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
export default {
    components: {},
    props: {},
    data() {
      return {
          list:[],
          total_data:0, // 总条数
          page_size:20,
          current_page:1,
          select_id:'',
          user_id:0,
          changeShow:false, // 显示调整输入框
          change_info:{
              change_type:0, // 修改类型  0 为 余额
              type:1,
              money:1,
          },
      };
    },
    watch: {},
    computed: {},
    methods: {
        handleSelectionChange:function(e){
            let ids = [];
            e.forEach(v => {
                ids.push(v.id);
            });
            this.select_id = ids.join(',');
        },
        get_users_list:function(){
            let _this = this;
            this.$get(this.$api.commissionGetUsersList,{page:this.current_page}).then(function(res){
                _this.page_size = res.data.per_page;
                _this.total_data = res.data.total;
                _this.current_page = res.data.current_page;
                _this.list = res.data.data;
            })

        },
        // 分页改变
        current_change:function(e){
            this.current_page = e;
            this.get_users_list();
        },
        // 删除处理
        del:function(id){
            let _this = this;
            if(this.$isEmpty(id)){
                return this.$message.error('请先选择删除的对象');
            }
            this.$post(this.$api.delUsers,{id:id}).then(function(res){
                if(res.code == 200){
                    _this.get_users_list();
                    return _this.$message.success("删除成功");
                }else{
                    return _this.$message.error("删除失败！"+res.msg);
                }
            });
        },
        change_user_money:function(id){
            this.user_id = id;
            this.changeShow = true;
        },
        change_start:function(){
            this.change_info.user_id = this.user_id;
            this.$post(this.$api.adminChangeMoney,this.change_info).then(res=>{
                if(res.code == 200){
                    this.changeShow = false;
                    this.$message.success('修改成功');
                }else{
                    this.$message.error(res.msg);
                }
            });
        }
    },
    created() {
        this.get_users_list();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.user_list_roles span{margin-right: 10px;}
.user_list_roles span:last-child{margin-right: 0;}
</style>