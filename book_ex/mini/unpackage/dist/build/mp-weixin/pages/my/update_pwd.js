(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/my/update_pwd"],{"316c":function(t,e,s){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var s={data:function(){return{agreeColor:"#DCDCDC",account:t.getStorageSync("zhanghao"),smsCode:"",password:"",resPassword:"",yzm:!0,djs:60,submit_disabled:!1,yzm_disabled:!1}},onShow:function(){console.log("success")},methods:{getYzm:function(){var e=this;11==e.account.length?(e.yzm_disabled=!0,e.send_sms()):t.showToast({title:"请输入正确的手机号",icon:"none"})},send_sms:function(){var e=this;this.GetData("/user/send_sms",{phone:e.account,is_type:2},(function(s){t.showToast({title:s.msg,icon:"none"}),e.yzm_disabled=!1,200==s.code&&(e.yzm=!e.yzm,e.timer=setInterval((function(){0==e.djs&&(e.yzm=!e.yzm,e.djs=60,clearInterval(e.timer)),e.djs=e.djs-1}),1e3))}),"post",(function(){t.stopPullDownRefresh()}))},formSubmit:function(e){var s=this,n=e.detail.value;if(this.isEmpty(s.account))t.showToast({title:"请输入手机号!",icon:"none"});else if(11==s.account.length)if(this.isEmpty(n.smsCode))t.showToast({title:"请输入验证码!",icon:"none"});else if(this.isEmpty(n.password))t.showToast({title:"请输入密码!",icon:"none"});else if(this.isEmpty(n.resPassword))t.showToast({title:"请输入确认密码!",icon:"none"});else if(n.password==n.resPassword){var o={phone:s.account,password:n.password,code:n.smsCode};s.submit_disabled=!0,this.GetData("/user/forget_password",o,(function(e){s.submit_disabled=!1,200==e.code?(t.showToast({title:"重置成功",icon:"success"}),setTimeout((function(){t.setStorageSync("userinfo",""),t.setStorageSync("zhanghao",""),t.setStorageSync("token",""),t.reLaunch({url:"/pages/my/login"})}),1e3)):t.showToast({title:e.msg,icon:"none"})}),"post",(function(){t.stopPullDownRefresh()}))}else t.showToast({title:"两次密码不一致!",icon:"none"});else t.showToast({title:"请输入正确的手机号",icon:"none"})}}};e.default=s}).call(this,s("543d")["default"])},"37ab":function(t,e,s){"use strict";var n=s("bd15"),o=s.n(n);o.a},"5a8c":function(t,e,s){"use strict";s.r(e);var n=s("9b8c"),o=s("b1d9");for(var a in o)"default"!==a&&function(t){s.d(e,t,(function(){return o[t]}))}(a);s("37ab");var i,c=s("f0c5"),u=Object(c["a"])(o["default"],n["b"],n["c"],!1,null,null,null,!1,n["a"],i);e["default"]=u.exports},"9b8c":function(t,e,s){"use strict";var n;s.d(e,"b",(function(){return o})),s.d(e,"c",(function(){return a})),s.d(e,"a",(function(){return n}));var o=function(){var t=this,e=t.$createElement,s=(t._self._c,t.yMobile(t.account));t.$mp.data=Object.assign({},{$root:{m0:s}})},a=[]},aff7:function(t,e,s){"use strict";(function(t){s("340d");n(s("66fd"));var e=n(s("5a8c"));function n(t){return t&&t.__esModule?t:{default:t}}t(e.default)}).call(this,s("543d")["createPage"])},b1d9:function(t,e,s){"use strict";s.r(e);var n=s("316c"),o=s.n(n);for(var a in n)"default"!==a&&function(t){s.d(e,t,(function(){return n[t]}))}(a);e["default"]=o.a},bd15:function(t,e,s){}},[["aff7","common/runtime","common/vendor"]]]);