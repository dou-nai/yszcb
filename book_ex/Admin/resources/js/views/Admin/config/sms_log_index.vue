<template>
    <div>
        <div class="admin_main_block">
        <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div><router-link to="/Admin/config/sms_form"><el-button type="primary" icon="el-icon-plus">添加</el-button></router-link></div>
                </div>

                <div class="admin_main_block_right">
                    <div><el-button type="danger" icon="el-icon-delete" @click="del(select_id)">批量删除</el-button></div>
                </div>
        </div>
        <div class="sms_table">
            <el-table
                :data="list"
                style="width: 100%"
                @selection-change="handleSelectionChange"
                >

               <el-table-column type="selection"></el-table-column>

                <el-table-column
                    prop="id"
                    label="id"
                    width="90">
                </el-table-column>

                <el-table-column
                    prop="add_time"
                    label="添加时间"
                    width="135">
                    <template slot-scope="scope">
                            <div v-if="scope.row.adv_start<=0"> - </div>
                            <div v-else>{{scope.row.add_time|formatDate}}</div>
                    </template>
                </el-table-column>

                <el-table-column
                    prop="code"
                    label="验证码"
                    width="135">
                </el-table-column>

                <el-table-column
                    prop="is_use"
                    label="是否在使用"
                    width="100">
                    <template v-slot:default="scope">
                        <el-tag v-if="scope.row.is_use == '0'"
                        type="success">是</el-tag>
                        <el-tag v-else type="danger"
                        >否</el-tag>
                    </template>
                </el-table-column>

                <el-table-column
                    prop="is_type"
                    label="短信类型"
                    width="105">
                    <template v-slot:default="scope">

                        <el-tag v-if="scope.row.is_type == '2'"
                        type="success">移动</el-tag>

                        <el-tag v-else-if="scope.row.is_type == '3'"
                        type="warning">联通</el-tag>

                        <el-tag v-else
                        >电信</el-tag>

                    </template>
                </el-table-column>


                <el-table-column
                    prop="name"
                    label="name"
                    width="135">
                </el-table-column>

                <el-table-column
                    prop="phone"
                    label="phone"
                    width="135">
                </el-table-column>

                <el-table-column
                    prop="status"
                    label="状态"
                    width="100">
                    <template v-slot:default="scope">
                        <el-tag v-if="scope.row.status == '1'"
                        type="success">正常</el-tag>
                        <el-tag v-else type="danger"
                        >关闭</el-tag>
                    </template>
                </el-table-column>

                 <el-table-column

                    label="操作"
                    width="240">
                    <template slot-scope="scope">
                        <!-- 由于动态路由也是传递params的，所以在$this.$router.push()方法中path不能和params一起使用，否则params无效。因此需要name指定页面。 -->
                        <el-button icon="el-icon-edit" @click="$router.push({name:'sms_form',params:{id:scope.row.id}})">编辑</el-button>
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

data(){
    return{
        list:[],
        total_data:0, // 总条数
        page_size:20,
        current_page:1,
        select_id:'',
    }
},
methods:{

     //1:选中一个就获取其id值
     handleSelectionChange:function(e){
            let ids = [];
            e.forEach(v => {
                ids.push(v.id);
            });
            this.select_id = ids.join(',');
        },

     //1：获取信息列表数据
     get_sms_list:function(){
            let _this = this;
            this.$get(this.$api.getSmsLogList,{page:this.current_page}).then(function(res){
                _this.page_size = res.data.per_page;
                _this.total_data = res.data.total;
                _this.current_page = res.data.current_page;
                _this.list = res.data.data;
                // if(_this.list.is_use==0){
                //     is_show=true
                // }
                //console.log(_this.list)
            })

        },

      //2:分页改变
        current_change:function(e){
            this.current_page = e;
            this.get_sms_list();
        },

        //3:删除处理
        del:function(id){
            //console.log(id)
            if(this.$isEmpty(id)){
                return this.$message.error('请先选择删除的对象');
            }
            this.$confirm('您确定删除吗, 是否继续?', '提示', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                type: 'warning'
            }).then(() => {
                this.$post(this.$api.delSmsLog,{id:id}).then(res=>{
                    if(res.code == 200){
                        this.get_sms_list();
                        return this.$message.success("删除成功");
                    }else{
                        return this.$message.error("删除失败");
                    }
                });
            });


        },


    },

created(){
    this.get_sms_list()
}

}

</script>

<style lang="less" scoped>

</style>
