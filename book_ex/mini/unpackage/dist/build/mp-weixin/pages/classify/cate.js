(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/classify/cate"],{"4bcd":function(t,e,n){"use strict";(function(t){function n(t,e){var n;if("undefined"===typeof Symbol||null==t[Symbol.iterator]){if(Array.isArray(t)||(n=i(t))||e&&t&&"number"===typeof t.length){n&&(t=n);var o=0,r=function(){};return{s:r,n:function(){return o>=t.length?{done:!0}:{done:!1,value:t[o++]}},e:function(t){throw t},f:r}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var a,s=!0,c=!1;return{s:function(){n=t[Symbol.iterator]()},n:function(){var t=n.next();return s=t.done,t},e:function(t){c=!0,a=t},f:function(){try{s||null==n.return||n.return()}finally{if(c)throw a}}}}function i(t,e){if(t){if("string"===typeof t)return o(t,e);var n=Object.prototype.toString.call(t).slice(8,-1);return"Object"===n&&t.constructor&&(n=t.constructor.name),"Map"===n||"Set"===n?Array.from(t):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?o(t,e):void 0}}function o(t,e){(null==e||e>t.length)&&(e=t.length);for(var n=0,i=new Array(e);n<e;n++)i[n]=t[n];return i}Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var r={data:function(){return{list:[],sel_cateid:[],sel_disabled:!1,CustomBar:this.CustomBar}},onShow:function(){},onLoad:function(){this.fav_list(),this.getClass()},methods:{fav_list:function(){var e=this,n={};this.GetData("/user/edit_user_info",n,(function(n){if(200!=n.code)return t.setStorageSync("userinfo",""),t.setStorageSync("zhanghao",""),void t.setStorageSync("token","");e.isEmpty(n.data.like_cate_ids)||(e.sel_cateid=n.data.like_cate_ids.split(","),e.sel_cateid=e.sel_cateid.map(Number))}),"get",(function(){t.stopPullDownRefresh()}))},edit_fav:function(){var e=this;if(!e.sel_disabled)if(e.sel_cateid.length<=0)t.showToast({title:"至少选择1类",icon:"none"});else{if(e.sel_disabled=!0,this.isEmpty(t.getStorageSync("userinfo")))return t.showToast({title:"请先登录！",duration:2e3,icon:"none"}),void setTimeout((function(){t.reLaunch({url:"/pages/my/login"})}),1e3);var n={like_cate_ids:e.sel_cateid.join()};this.GetData("/user/edit_user_info",n,(function(n){e.sel_disabled=!1,200==n.code?(t.showToast({title:"保存成功",icon:"none"}),setTimeout((function(){t.redirectTo({url:"../my/index"})}),1e3)):t.showToast({title:n.msg,icon:"none"})}),"post",(function(){t.stopPullDownRefresh()}))}},select:function(e){var i=this,o=i.list[e];if(console.log(i.sel_cateid.indexOf(o.id)),i.sel_cateid.indexOf(o.id)>-1){var r,a=[],s=n(i.sel_cateid);try{for(s.s();!(r=s.n()).done;){var c=r.value;console.log(c),c!=o.id&&a.push(Number(c))}}catch(u){s.e(u)}finally{s.f()}i.sel_cateid=a}else{if(i.sel_cateid.length>=3)return void t.showToast({title:"最多选择3类",icon:"none"});var l=i.sel_cateid;l.push(o.id),i.sel_cateid=l}console.log(i.sel_cateid)},cancel:function(){t.redirectTo({url:"../my/index"})},save:function(){t.redirectTo({url:"./list"})},getClass:function(){var e=this;t.showLoading(),this.GetData("/api/get_goods_class_list",{},(function(n){e.list=e.list.concat(n.data),t.hideLoading()}),"get",(function(){t.stopPullDownRefresh()}))}}};e.default=r}).call(this,n("543d")["default"])},6115:function(t,e,n){"use strict";var i=n("a669"),o=n.n(i);o.a},"614a":function(t,e,n){"use strict";(function(t){n("340d");i(n("66fd"));var e=i(n("9784"));function i(t){return t&&t.__esModule?t:{default:t}}t(e.default)}).call(this,n("543d")["createPage"])},9784:function(t,e,n){"use strict";n.r(e);var i=n("d31d"),o=n("e9b5");for(var r in o)"default"!==r&&function(t){n.d(e,t,(function(){return o[t]}))}(r);n("6115");var a,s=n("f0c5"),c=Object(s["a"])(o["default"],i["b"],i["c"],!1,null,null,null,!1,i["a"],a);e["default"]=c.exports},a669:function(t,e,n){},d31d:function(t,e,n){"use strict";var i;n.d(e,"b",(function(){return o})),n.d(e,"c",(function(){return r})),n.d(e,"a",(function(){return i}));var o=function(){var t=this,e=t.$createElement,n=(t._self._c,t.__map(t.list,(function(e,n){var i=t.__get_orig(e),o=t.sel_cateid.indexOf(e.id);return{$orig:i,g0:o}})));t.$mp.data=Object.assign({},{$root:{l0:n}})},r=[]},e9b5:function(t,e,n){"use strict";n.r(e);var i=n("4bcd"),o=n.n(i);for(var r in i)"default"!==r&&function(t){n.d(e,t,(function(){return i[t]}))}(r);e["default"]=o.a}},[["614a","common/runtime","common/vendor"]]]);