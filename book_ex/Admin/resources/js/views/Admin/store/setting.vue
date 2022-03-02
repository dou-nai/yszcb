<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>出版社管理</div>
                </div>
            </div>

            <div class="admin_form_main">
                <el-form  label-width="100px" ref="info" :model="info">
                    <el-form-item label="出版社名称" prop="store_name" :rules="[{required:true,message:'出版社名称不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.store_name"></el-input></el-form-item>
                    <el-form-item label="公司介绍" prop="store_description"><el-input type="textarea" placeholder="请输入内容" maxlength="200" show-word-limit v-model="info.store_description"></el-input></el-form-item>
                    <el-form-item label="出版社LOGO" prop="store_logo" class="is-required"><el-upload class="avatar-uploader" :action="$api.storeLogoUpload" :headers="upload_headers" :show-file-list="false" :on-success="handleAvatarSuccess" >
                        <img v-if="info.store_logo" :src="info.store_logo" class="avatar-upload">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload></el-form-item>
                    <el-form-item label="公司名称" prop="store_company_name"><el-input placeholder="请输入内容" v-model="info.store_company_name"></el-input></el-form-item>
                    <el-form-item label="联系电话" prop="store_mobile"><el-input placeholder="请输入内容" v-model="info.store_mobile"></el-input></el-form-item>
                    <el-form-item label="公司地址" prop="store_address"><el-input placeholder="请输入内容"  v-model="info.store_address"></el-input></el-form-item>
                    <el-form-item label="公司网站" prop="company_web"><el-input placeholder="请输入内容"  v-model="info.company_web"></el-input></el-form-item>
                    <el-form-item label="展区位置" prop="hall_address"><el-input placeholder="请输入内容"  v-model="info.hall_address"></el-input></el-form-item>
                    <el-form-item label="出版社状态" prop="store_status">
                        <el-switch v-model="info.store_status" active-color="#13ce66" :active-value="1" :inactive-value="0" :active-text="info.store_status==1?'开启':'关闭'"></el-switch>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="submitForm('info')">保存</el-button>
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
      let _this = this;
      return {
          edit_id:0,
          info:{
               area_info:[],
               store_description:'',
               store_name: '',
               store_description: '',
               store_logo:'',
               store_company_name:'',
               store_mobile:'',
               store_address:'',
               company_web:'',
               hall_address:'',
          },
          area_list:[],
          // 省市区动态加载
          props:{
              value:'area_id',
              label:'name',
          },
          amap_config:{
              center:[116.397428, 39.90923],
              position:[116.397428, 39.90923],
              plugin:[
                    {pName: 'ToolBar'},
                    {pName:'Scale'},
              ],
              // 地图对象处理
              mapEvents:{
                  init(map) {
                      map.on('click',function(e){
                          let position = [];
                          position.push(e.lnglat.lng);
                          position.push(e.lnglat.lat);
                          _this.amap_config.position = position;
                          _this.info.lng = e.lnglat.lng;
                          _this.info.lat = e.lnglat.lat;
                      });
                  }
              },
          },
          upload_headers:{},
      };
    },
    watch: {},
    computed: {},
    methods: {
        handleZhengshuSuccess(res, file) {
            console.log(res);
            console.log(file);
            this.info.sp_zhengshu=res.data;
        },
        submitForm:function(e){
            var _this=this;
            this.$refs[e].validate(res=>{
                if(res){
                      //  判断是Add 或者 Edit
                    let url = _this.$api.addStore;
                    if(_this.edit_id>0){
                        url = _this.$api.editStore+_this.edit_id;
                    }

                    // Http 请求
                    this.$post(url,this.info).then(postRes=>{
                        if(postRes.code == 200){
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
        handleAvatarSuccess(res) {
            this.info.store_logo = res.data;
            this.$forceUpdate();
        },
        get_store_info(){
             let _this = this;
            this.$get(this.$api.editStore+this.edit_id).then(function(res){
                _this.info = res.data;
            });
        }
    },
    created() {

        this.upload_headers.Authorization = 'Bearer '+localStorage.getItem('token'); // 要保证取到

        // 判断是否是编辑

        if(!this.$isEmpty(this.$route.params.id)){
            this.edit_id = this.$route.params.id;
        }
        if(this.edit_id>0){
            this.get_store_info();
        }
     },
    mounted() {
    }
};
</script>
<style lang="scss" scoped>
.amap_demo {
  height: 400px;
}
</style>
