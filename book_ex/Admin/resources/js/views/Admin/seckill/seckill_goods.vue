<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div><el-button icon="el-icon-back" @click="$router.go(-1)">返回</el-button></div>
                    <!-- <div><el-input v-model="name" placeholder="请输入内容"></el-input></div>
                    <div><el-button icon="el-icon-search">条件筛选</el-button></div> -->
                </div>

                <div class="admin_main_block_right" style="width:250px">
                    <div>
                        <el-button icon="el-icon-plus" @click="add()">添加出版社</el-button>
                        <el-button type="danger" icon="el-icon-delete" @click="del(select_id)">批量删除</el-button>

                    </div>
                </div>
            </div>
            <div class="admin_table_main">
                <el-table :data="list" @selection-change="handleSelectionChange" >
                    <el-table-column type="selection"></el-table-column>
                    <!-- <el-table-column prop="id" label="#" fixed="left" width="70px"></el-table-column> -->
                    <el-table-column prop="id" label="#"  width="70px"></el-table-column>
                    <el-table-column label="出版社名称">
                        <template slot-scope="scope">
                            <dl class="table_dl">
                                <dt><img :src="scope.row.goods.store_logo||require('@/assets/default_pic.png')" width="50px" height="50px"></dt>
                                <dd class="table_dl_dd_all">{{ scope.row.goods.store_name }}</dd>
                            </dl>
                        </template>
                    </el-table-column>
                    <el-table-column label="参加状态">
                        <template slot-scope="scope">
                            <el-tag type="success" v-if="scope.row.status==1" @click="change_status(scope.row.id)">通过</el-tag>
                            <el-tag type="warning" v-else-if="scope.row.status==0" @click="change_status(scope.row.id)">审核中</el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column label="操作" fixed="right" width="120px">
                        <template slot-scope="scope">
                            <el-button type="danger" icon="el-icon-delete" @click="del(scope.row.id)">删除</el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <div class="admin_table_main_pagination">
                    <el-pagination @current-change="current_change" background layout="prev, pager, next,jumper,total" :total="total_data" :page-size="page_size" :current-page="current_page"></el-pagination>
                </div>
            </div>
        </div>

        <el-dialog title="选择参加活动出版社" :visible.sync="dialogTableVisible" width="80%">
            <el-table :data="list2" @selection-change="handleSelectionChange2" >
                <el-table-column type="selection"></el-table-column>
                <!-- <el-table-column prop="id" label="#" fixed="left" width="70px"></el-table-column> -->
<!--                <el-table-column prop="id" label="#"  width="70px"></el-table-column>-->
                <el-table-column label="出版社名称">
                    <template slot-scope="scope">
                        <dl class="table_dl">
                            <dd class="table_dl_dd_all">{{ scope.row.store_name }}</dd>
                        </dl>
                    </template>
                </el-table-column>
                <el-table-column label="出版社状态" width="120">
                    <template slot-scope="scope">

                        <el-tag type="success" v-if="scope.row.store_status==1">通过</el-tag>
                        <el-tag type="warning" v-else-if="scope.row.store_status==2">审核中</el-tag>
                        <el-tag type="danger" v-else-if="scope.row.store_status==0">失败</el-tag>
                    </template>
                </el-table-column>
            </el-table>
            <div class="admin_table_main_pagination">
                <el-pagination @current-change="current_change2" background layout="prev, pager, next,jumper,total" :total="total_data2" :page-size="page_size2" :current-page="current_page2"></el-pagination>
            </div>
            <span slot="footer" class="dialog-footer">
                <el-button @click="dialogTableVisible = false">取 消</el-button>
                <el-button type="primary" @click="add_seckill_goods()">确 定</el-button>
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
          list2:[],
          total_data2:0,
          page_size2:20,
          current_page2:1,
          total_data:0, // 总条数
          page_size:20,
          current_page:1,
          select_id:'',
          select_id2:'',
          adv_position_id:0,
          dialogTableVisible:false, // 选择出版社
          sid:0

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
        get_seckill_list:function(){
            this.$post(this.$api.getAddSeckillGoods,{page:this.current_page,sid:this.$route.params.sid}).then(res=>{
                this.page_size = res.data.per_page;
                this.total_data = res.data.total;
                this.current_page = res.data.current_page;
                this.list = res.data.data;
            })

        },
        // 删除处理
        del:function(id){
            if(this.$isEmpty(id)){
                return this.$message.error('请先选择删除的对象');
            }
            this.$post(this.$api.delSeckillGoods,{id:id}).then(res=>{
                if(res.code == 200){
                    this.get_seckill_list();
                    return this.$message.success("删除成功");
                }else{
                    return this.$message.error("删除失败");
                }
            });
        },
        current_change:function(e){
            this.current_page = e;
            this.get_seckill_list();
        },
        change_status:function(id){
            return;
            this.$post(this.$api.changeSeckillStatus,{id:id}).then(()=>{
                this.$message.success('修改成功');
                this.get_seckill_list();
            });

        },
       add:function(){
         this.dialogTableVisible=true;
         if(this.list2.length==0) {
             this.get_goods_list();
         }
       },
        get_goods_list:function(){

            this.$get(this.$api.getStoreList,{page:this.current_page2,store_status:1,seckill_id:this.sid}).then(res=>{
                this.page_size2 = res.data.per_page;
                this.total_data2 = res.data.total;
                this.current_page2 = res.data.current_page;
                this.list2 = this.$formatGoods(res.data.data);
                console.log(this.list2,"list2");
            })

        },
        handleSelectionChange2:function(e){
            let ids = [];
            e.forEach(v => {
                ids.push(v.id);
            });
            this.select_id2 = ids.join(',');
        },
        add_seckill_goods:function(){
            if(this.$isEmpty(this.select_id2)){
                return this.$message.error('没有选择');
            }
            this.$post(this.$api.adminAddSeckillGoods,{id:this.select_id2,seckill_id:this.sid}).then(res=>{
                this.dialogTableVisible = false;
                if(res.code == 200){
                    this.get_seckill_list();

                     this.get_goods_list();
                    return this.$message.success('成功！');
                }else{
                    return this.$message.error(res.msg);
                }
            });
        },
        current_change2:function(e){
            this.current_page = e;
            this.get_adv_list();
        },
    },
    created() {
        this.sid=this.$route.params.sid;
        this.get_seckill_list();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
</style>
