<template>
    <div class="qingwu">
        <div class="admin_main_block">
            <div class="admin_main_block_top">
                <div class="admin_main_block_left">
                    <div>广告编辑</div>
                </div>

                <div class="admin_main_block_right">
                    <div><el-button icon="el-icon-back" @click="$router.go(-1)">返回</el-button></div>
                </div>
            </div>

            <div class="admin_form_main">
                <el-form  label-width="100px" ref="info" :model="info">
                    <el-form-item label="广告位" prop="ap_id">
                        <el-select v-model="info.ap_id" placeholder="请选择广告位" @change="selectChange()">
                            <el-option label="请选择广告位" :value="0"></el-option>
                            <el-option v-for="(v,k) in list" :label="v.ap_name" :key="k" :value="v.id"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="广告名" prop="adv_title" :rules="[{required:true,message:'广告名不能为空',trigger: 'blur' }]"><el-input placeholder="请输入内容" v-model="info.adv_title"></el-input></el-form-item>
                    <el-form-item label="广告图" class="is-required" prop="adv_image"><el-upload class="avatar-uploader" :action="$api.advUpload" :headers="upload_headers" :show-file-list="false" :on-success="handleAvatarSuccess" >
                        <img v-if="info.adv_image" :src="info.adv_image" class="avatar-upload">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload></el-form-item>

                    <!-- <el-form-item label="背景颜色" prop="bg_color"><el-input placeholder="#fff" v-model="info.bg_color"></el-input></el-form-item> -->
                   <!-- <el-form-item label="开始时间">
                        <el-date-picker format="yyyy-MM-dd HH:mm" v-model="info.adv_date" type="daterange" range-separator="至" start-placeholder="开始日期" end-placeholder="结束日期"></el-date-picker>
                    </el-form-item>
                    -->
                     <el-form-item label="开始时间">
                        <el-date-picker format="yyyy-MM-dd HH:mm" v-model="info.adv_start" type="datetime" ></el-date-picker>

                    </el-form-item>
                    <el-form-item label="结束时间">
                        <el-date-picker format="yyyy-MM-dd HH:mm" v-model="info.adv_end" type="datetime" ></el-date-picker>

                    </el-form-item>

                    <el-form-item label="广告类型" prop="adv_type">
                        <el-select v-model="info.adv_type" placeholder="请选择栏目" @change="selectChange()">
                            <el-option label="活动链接" :value="0"></el-option>
                            <el-option label="图书链接" :value="1"></el-option>
                            <el-option label="出版社链接" :value="2"></el-option>
                            <el-option label="内容" :value="3"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item v-if="info.adv_type!=3" label="提示">
                        <el-tag type="danger" v-if="info.adv_type==0">广告类型为活动链接,填写对应的活动ID,点击广告即可查看相应的活动详情;不填则不做跳转。</el-tag>
                        <el-tag type="danger" v-if="info.adv_type==1">广告类型为图书链接,填写对应的图书ID,点击广告即可查看相应的图书详情;不填则不做跳转。</el-tag>
                        <el-tag type="danger" v-if="info.adv_type==2">广告类型为出版社链接,填写对应的出版社ID,点击广告即可查看相应的出版社详情;不填则不做跳转。</el-tag>
                    </el-form-item>

                    <el-form-item v-if="info.adv_type==0" label="活动ID" prop="adv_link"><el-input type="number" placeholder="请输入" v-model="info.adv_link"></el-input>
                        <router-link style="color:#1999ff;cursor:pointer;" target="_blank" to="/Admin/seckill/index">点击前往活动列表查看对应ID</router-link>
                    </el-form-item>
                    <el-form-item v-if="info.adv_type==1" label="图书ID" prop="adv_link"><el-input type="number" placeholder="请输入" v-model="info.adv_link"></el-input>
                        <router-link style="color:#1999ff;cursor:pointer;" target="_blank" to="/Admin/goods/index">点击前往图书列表查看对应ID</router-link>
                    </el-form-item>
                    <el-form-item v-if="info.adv_type==2" label="出版社ID" prop="adv_link"><el-input type="number" placeholder="请输入" v-model="info.adv_link"></el-input>
                      <router-link style="color:#1999ff;cursor:pointer;" target="_blank" to="/Admin/store/index">点击前往出版社列表查看对应ID</router-link>

                    </el-form-item>

                    <el-form-item label="广告内容" class="width_auto_70">
                        <wangeditor @goods_content="adv_content" :contents="info.adv_content"></wangeditor>
                    </el-form-item>

                    <el-form-item label="排序" prop="adv_sort"><el-input placeholder="请输入内容" type="number"  v-model="info.adv_sort"></el-input></el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="submitForm('info')">提交</el-button>
                        <!-- <el-button @click="resetForm('info')">重置</el-button> -->
                    </el-form-item>
                </el-form>
            </div>
        </div>
    </div>
</template>

<script>
import wangeditor from "@/components/seller/wangeditor.vue"
import choseSpec from "@/components/seller/attr_spec/chose_spec.vue"
import choseFreightTemplate from "@/components/seller/freight_template/chose_freight_template.vue"
export default {
    components: {
        wangeditor,
        choseSpec,
        choseFreightTemplate,
    },
    props: {},
    data() {
      return {
          edit_id:0,
          info:{
              adv_sort:0,
              adv_type:0,
              ap_id:0,
              adv_date:['2020-10-20T16:00:00.000Z'],
              adv_start:"",
              adv_end:"",
              adv_image:''
          },
          list:[],
          upload_headers:{},
      };
    },
    watch: {},
    computed: {},
    methods: {
        resetForm:function(e){
            this.$refs[e].resetFields();
        },
        submitForm:function(e){
            let _this = this;

            // 验证表单
            this.$refs[e].validate(function(res){
                if(res){
                     // 图片 验证
                    if(_this.info.adv_image=='' || _this.info.adv_image=='h'){
                        return _this.$message.error('广告图不能为空');
                    }

                    //  判断是Add 或者 Edit
                    let url = _this.$api.addAdv;
                    if(_this.edit_id>0){
                        url = _this.$api.editAdv+_this.edit_id;
                    }

                    // Http 请求
                    _this.$post(url,_this.info).then(function(res){

                        if(res.code == 200){
                            // 判断是否是编辑
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
        get_adv_info:function(){
            let _this = this;
            this.$get(this.$api.editAdv+this.edit_id).then(function(res){
                _this.info = res.data;
                _this.list = res.data.list;
            })

        },
        get_adv_list:function(){
            let _this = this;
            this.$get(this.$api.addAdv).then(function(res){
                _this.list = res.data;
            })

        },
        selectChange:function(){
            this.$forceUpdate();
        },
        handleAvatarSuccess(res) {
            this.info.adv_image = res.data;
            this.$forceUpdate();
        },
         // 富文本编辑内容变化
        adv_content:function(data){
            console.log(data);
            this.info.content = data;
            this.info.adv_content = data;
            this.$forceUpdate();
        },
    },
    created() {
        this.upload_headers.Authorization = 'Bearer '+localStorage.getItem('token'); // 要保证取到

        if(!this.$isEmpty(this.$route.params.adv_position_id)){
            this.info.ap_id = this.$route.params.adv_position_id;
        }

        // 判断是否是编辑
        if(!this.$isEmpty(this.$route.params.id)){
            this.edit_id = this.$route.params.id;
        }

        if(this.edit_id>0){
            this.get_adv_info();
        }else{
            this.get_adv_list();
        }

    },
    mounted() {}
};
</script>
<style lang="scss" scoped>
.line{text-align: center}
</style>
