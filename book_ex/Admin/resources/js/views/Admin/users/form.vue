<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>用户编辑</div>
                </div>

                <div class="admin_main_block_right">
                    <div><el-button icon="el-icon-back" @click="$router.go(-1)">返回</el-button></div>
                </div>
            </div>

            <div class="admin_form_main">
                <el-form  label-width="100px" ref="info" :model="info">
                    <el-form-item label="用户名" prop="username" :rules="[{required:true,message:'用户名不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.username"></el-input></el-form-item>
                    <el-form-item label="昵称" prop="nickname" :rules="[{required:true,message:'昵称不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.nickname"></el-input></el-form-item>
                    <el-form-item label="手机号" prop="phone" :rules="[{required:true,message:'手机不能为空',trigger: 'blur' }]"><el-input placeholder="请输入手机号" v-model="info.phone"></el-input></el-form-item>
                    <el-form-item v-if="edit_id==0" label="密码" prop="password" :rules="[{required:true,message:'密码不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.password"></el-input></el-form-item>
                    <el-form-item v-else label="密码" prop="password" ><el-input placeholder="不修改则不填" v-model="info.password"></el-input></el-form-item>
                    <el-form-item label="角色" class="width_auto">
                        <el-radio style="margin-bottom:10px;" @change="role_change(v)" v-model="role_id" v-for="(v,k) in roles" :key="k" :label="v.id" border>{{v.name}}</el-radio>
                    </el-form-item>
                    <el-form-item label="所属供应商" class="width_auto" v-if="show_store">
                        <el-select v-model="info.store_id" placeholder="请选择">
                            <el-option
                                    v-for="item in store_list"
                                    :key="item.id"
                                    :label="item.store_name"
                                    :value="item.id">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="submitForm('info')">提交</el-button>
                        <el-button @click="resetForm('info')">重置</el-button>
                    </el-form-item>
                </el-form>
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
          edit_id:0,
          info:{
              username:'',
              nickname:'',
              phone:''
          },
          roles_list:[],
          roles:[],
          role_id:0,
          show_store:false,
          store_list:[],
          store_id:null
      };
    },
    watch: {},
    computed: {},
    methods: {
        resetForm:function(e){
            this.$refs[e].resetFields();
        },
        role_change:function(d){
            console.log(d);
            console.log(this.role_id);
            if(d.name=="供应商")
            {
                this.show_store=true;
            }else{
                this.show_store=false;
            }
        },
        submitForm:function(e){
            let _this = this;

            // 验证表单
            this.$refs[e].validate(function(res){
                if(res){
                    //  判断是Add 或者 Edit
                    let url = _this.$api.addUsers;
                    if(_this.edit_id>0){
                        url = _this.$api.editUsers+_this.edit_id;
                        delete _this.info.roles
                    }

                    _this.info['roles_list'] = [_this.role_id];
                    console.log(_this.info);
                    // Http 请求
                    _this.$post(url,_this.info).then(function(res){
                        if(res.code == 200){
                            if(_this.edit_id>0){
                                  _this.$message.success("编辑成功");
                            }else{
                                 _this.$message.success("添加成功");
                            }
                            _this.$router.go(-1);
                        }else{
                            if(_this.edit_id>0){
                                  _this.$message.success("编辑失败");
                            }else{
                                 _this.$message.success("添加失败");
                            }
                        }
                    });
                }
            });
        },

        get_users_info:function(){
            let _this = this;
            this.$get(this.$api.editUsers+this.edit_id).then(function(res){
                _this.info = res.data['info'];

                if(res.data.info.roles.length>0){
                    _this.roles_list = [];
                    // res.data.info.roles.forEach(roleRes => {
                    //     _this.roles_list.push(roleRes.id);
                    // });
                    _this.role_id=res.data.info.roles[0].id;
                    if(res.data.info.roles[0].name=="供应商")
                    {
                        _this.show_store=true;
                    }
                }
                _this.info.password = '';
                _this.roles = res.data['roles_list'];

            })

        },
        get_add_roles_info:function(){
            let _this = this;
            this.$get(this.$api.addUsers).then(function(res){
                _this.roles = res.data['roles_list'];
            });

        },
        getStoreList:function(){
            let _this=this;
            this.$get(this.$api.getStoreList,{"page_size":100}).then(function (res) {
                console.log(res);
                _this.store_list=res.data.data;
            });
        }
    },
    created() {
        this.getStoreList();
        // 判断是否是编辑
        if(!this.$isEmpty(this.$route.params.id)){
            this.edit_id = this.$route.params.id;
        }else{
            this.get_add_roles_info();
        }

        if(this.edit_id>0){
            this.get_users_info();

        }

    },
    mounted() {}
};
</script>
<style lang="scss" scoped>

</style>
