<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
<!--                    <div><router-link to="/Seller/goods/chose_class"><el-button type="primary" icon="el-icon-plus">添加</el-button></router-link></div>-->
                    <div><el-input v-model="where.sn" placeholder="号码"></el-input></div>
                    <div style="width:160px;">
                        <el-select v-model="where.status" placeholder="状态">
                            <el-option label="全部" value=""></el-option>
                            <el-option label="未售出" value="0"></el-option>
                            <el-option label="已售出" value="1"></el-option>
                            <el-option label="已核销" value="2"></el-option>
                        </el-select>
                    </div>
                    <div><el-button icon="el-icon-search" @click="get_card_list()">查询</el-button></div>
                </div>

                <div class="admin_main_block_right" style="width:200px">
                    <div><el-button type="danger" icon="el-icon-delete" @click="del(select_id)">批量删除</el-button></div>
                    <div> <el-button type="primary" icon="el-icon-plus"  @click="dialogVisible = true">批量添加</el-button></div>

                </div>
            </div>
            <div class="admin_table_main">
                <el-table :data="list" @selection-change="handleSelectionChange" >
                    <el-table-column type="selection"></el-table-column>
                    <!-- <el-table-column prop="id" label="#" fixed="left" width="70px"></el-table-column> -->
                    <el-table-column prop="id" label="#"  width="70px"></el-table-column>
                    <el-table-column prop="sn" label="号码"  width="150px" ></el-table-column>
                    <el-table-column label="图书状态">
                        <template slot-scope="scope">
                            <div v-if="scope.row.status==0">
                                <el-tag type="success">未售出</el-tag>
                            </div>
                            <div v-if="scope.row.status==1" >
                                <el-tag type="danger" >已经售出</el-tag>
                            <br>
                                    订单号:[{{scope.row.order_no}}]
                            </div>
                            <div v-if="scope.row.status==2" >
                                <el-tag type="danger" >已经核销</el-tag>检测人:[{{scope.row.user.username}}]
                                <br>
                                订单号:[{{scope.row.order_no}}]<br>

                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column label="操作" fixed="right" width="120px">
                        <template slot-scope="scope">

                             <el-button v-if="scope.row.status==1" type="danger" icon="el-icon-delete" @click="write_off(scope.row.id)">核销</el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <div class="admin_table_main_pagination">
                    <el-pagination @current-change="current_change" background layout="prev, pager, next,jumper,total" :total="total_data" :page-size="page_size" :current-page="current_page"></el-pagination>
                </div>
            </div>
        </div>

        <el-dialog
                title="提示"
                :visible.sync="dialogVisible"
                width="60%"
                :before-close="handleClose">
            <div>


                <div>
                    <el-form  label-width="80px" >


                        <el-form-item label="卡号列表" ><el-input placeholder="卡号开头字符" v-model="card_sn" ></el-input></el-form-item>
                        <el-form-item label="开始序号" ><el-input placeholder="开始序号" v-model="card_start" ></el-input></el-form-item>
                        <el-form-item label="结束序号" ><el-input placeholder="结束序号" v-model="card_end" ></el-input></el-form-item>
                        <el-form-item  ><el-button type="button" @click="create_code">生成</el-button></el-form-item>
                    <el-form-item label="卡号列表" >
                        <el-input
                                  type="textarea"
                                  :rows="2"
                                  placeholder="请输入卡号"
                                  v-model="cards">
                        </el-input>
                        可以自行输入卡号列表,用逗号[,]分割

                    </el-form-item>
                    </el-form>
                </div>
            </div>
            <span slot="footer" class="dialog-footer">
    <el-button @click="dialogVisible = false">取 消</el-button>
    <el-button type="primary" @click="add()">确 定</el-button>
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
                cards:"",
                dialogVisible: false,
                list:[],
                total_data:0, // 总条数
                page_size:20,
                current_page:1,
                select_id:'',
                goods_id:0,
                where:{
                    id:'',
                },
                card_sn:"SN",
                card_start:1,
                card_end:100
            };
        },
        watch: {},
        computed: {},
        methods: {
            create_code:function(){
                if(this.card_sn)
                {
                    if(this.card_start>0 && this.card_end>0)
                    {
                        let arr=[];
                        let len=this.card_end.toString().length;
                        for(let i=this.card_start;i<=this.card_end;i++)
                        {
                            let n=(Array(len).join('0') + i.toString()).slice(-len);
                            arr.push(this.card_sn+n);
                        }
                        console.log(arr);
                        this.cards=arr.join(',');
                    }

                }
            },
            add:function(e){
                let that=this;
                this.$post(this.$api.card_add, {goods_id: this.goods_id,cards:this.cards}).then(res => {
                    console.log(res);
                    if(res.code==200) {
                        that.$message.success("添加成功!");
                        that.get_card_list();
                        that.dialogVisible=false;
                    }
                });
            },
            handleSelectionChange:function(e){
                let ids = [];
                e.forEach(v => {
                    ids.push(v.id);
                });
                this.select_id = ids.join(',');
            },
            get_card_list:function(){
                this.where.page = this.current_page;
                this.where["id"]=this.goods_id;
                this.$post(this.$api.card,this.where).then(res=>{
                    this.page_size = res.data.per_page;
                    this.total_data = res.data.total;
                    this.current_page = res.data.current_page;
                    this.list = this.$formatGoods(res.data.data);
                })

            },
            // 删除处理
            write_off:function(id){
                if(this.$isEmpty(id)){
                    return this.$message.error('请先选择核销的对象');
                }
                this.$confirm('您确定核销吗, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$post(this.$api.card_off, {id: id}).then(res => {

                        if (res.code == 200) {
                            this.get_card_list();
                            return this.$message.success("核销成功");
                        } else {
                            return this.$message.error("核销失败");
                        }
                    });
                });
            },

            current_change:function(e){
                this.current_page = e;
                this.get_card_list();
            },
        },
        created() {
            if(this.$isEmpty(this.$route.params.id)){
                this.$message.error('没有图书ID，请重新操作');
                this.$router.go(-1);
            }
            this.goods_id=this.$route.params.id;
            this.get_card_list();
        },
        mounted() {}
    };
</script>
<style lang="scss" scoped>
</style>
