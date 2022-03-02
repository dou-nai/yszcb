<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div><router-link to="/Admin/seckill/form"><el-button type="primary" icon="el-icon-plus">添加</el-button></router-link></div>
                    <!-- <div><el-input v-model="name" placeholder="请输入内容"></el-input></div>
                    <div><el-button icon="el-icon-search">条件筛选</el-button></div> -->
                </div>

                <div class="admin_main_block_right">
                    <div><el-button type="danger" icon="el-icon-delete" @click="del(select_id)">批量删除</el-button></div>
                </div>
            </div>
            <div class="admin_table_main">
                <el-table :data="list" @selection-change="handleSelectionChange" >
                    <el-table-column type="selection"></el-table-column>
                    <!-- <el-table-column prop="id" label="#" fixed="left" width="70px"></el-table-column> -->
                    <el-table-column prop="id" label="ID"  width="70px"></el-table-column>
                    <el-table-column label="活动名称" prop="name"></el-table-column>

                    <el-table-column prop="start_time" label="开始时间">
                        <template slot-scope="scope">
                            <div v-if="scope.row.adv_start<=0"> - </div>
                            <div v-else>{{scope.row.start_time|formatDate}}</div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="end_time" label="结束时间">
                        <template slot-scope="scope">
                            <div v-if="scope.row.adv_end<=0"> - </div>
                            <div v-else>{{scope.row.end_time|formatDate}}</div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="enable" label="是否启用">
                        <template slot-scope="scope">
                            <el-switch
                                    v-model="scope.row.enable"
                                    @change="switch_enable(scope.row)"
                                    active-value=1
                                    inactive-value=0
                                    active-color="#13ce66"
                                    inactive-color="#ff4949">
                            </el-switch>

                        </template>
                    </el-table-column>
                     <el-table-column prop="is_index" label="首页展示">
                        <template slot-scope="scope">
                            <el-switch
                                    v-model="scope.row.is_index"
                                    @change="switch_is_index(scope.row)"
                                    active-value=1
                                    inactive-value=0
                                    active-color="#13ce66"
                                    inactive-color="#ff4949">
                            </el-switch>
                        </template>
                    </el-table-column>

                    <el-table-column label="操作" fixed="right" width="300px">
                        <template slot-scope="scope">
                            <el-button icon="el-icon-edit" @click="$router.push({name:'seckill_form',params:{id:scope.row.id}})">编辑</el-button>
                            <el-button icon="el-icon-menu" @click="$router.push('/Admin/seckill/seckill_goods/'+scope.row.id)">出版社</el-button>
                            <el-button type="danger" icon="el-icon-delete" @click="del(scope.row.id)">删除</el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <div class="admin_table_main_pagination">
                    <el-pagination @current-change="current_change" background layout="prev, pager, next,jumper,total" :total="total_data" :page-size="page_size" :current-page="current_page"></el-pagination>
                </div>
            </div>
        </div>



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
          adv_position_id:0,
      };
    },
    watch: {},
    computed: {},
    methods: {
        switch_enable:function(e)
        {
            console.log(e);
            this.$post(this.$api.seckill_enable,{id:e.id,enable:e.enable}).then(res=>{
                this.get_seckill_list();
            });
        },
        switch_is_index:function(e)
        {
            console.log(e);
            this.$post(this.$api.seckill_is_index,{id:e.id,is_index:e.is_index}).then(res=>{
                this.get_seckill_list();
            });
        },
        handleSelectionChange:function(e){
            let ids = [];
            e.forEach(v => {
                ids.push(v.id);
            });
            this.select_id = ids.join(',');
        },
        get_seckill_list:function(){
            this.$get(this.$api.getSeckillList,{page:this.current_page}).then(res=> {

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
            this.$confirm('您确定删除吗, 是否继续?', '提示', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                type: 'warning'
            }).then(() => {
                this.$post(this.$api.delSeckill,{id:id}).then(res=>{
                    if(res.code == 200){
                        this.get_seckill_list();
                        return this.$message.success("删除成功");
                    }else{
                        return this.$message.error("删除失败");
                    }
                });
            });


        },
        current_change:function(e){
            this.current_page = e;
            this.get_seckill_list();
        },
    },
    created() {
        this.get_seckill_list();
    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
</style>
