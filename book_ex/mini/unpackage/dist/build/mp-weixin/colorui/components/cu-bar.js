(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["colorui/components/cu-bar"],{c115:function(e,n,t){"use strict";t.r(n);var a=t("f88b"),o=t.n(a);for(var r in a)"default"!==r&&function(e){t.d(n,e,(function(){return a[e]}))}(r);n["default"]=o.a},d441:function(e,n,t){"use strict";var a;t.d(n,"b",(function(){return o})),t.d(n,"c",(function(){return r})),t.d(n,"a",(function(){return a}));var o=function(){var e=this,n=e.$createElement;e._self._c},r=[]},d9ab:function(e,n,t){"use strict";t.r(n);var a=t("d441"),o=t("c115");for(var r in o)"default"!==r&&function(e){t.d(n,e,(function(){return o[e]}))}(r);var c,u=t("f0c5"),i=Object(u["a"])(o["default"],a["b"],a["c"],!1,null,null,null,!1,a["a"],c);n["default"]=i.exports},f88b:function(e,n,t){"use strict";(function(e){Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0;var t={name:"cu-bar",data:function(){return{}},props:{PageCur:{type:String,default:"home"},Height:{type:Number,default:120}},methods:{NavChange:function(n){var t=n.currentTarget.dataset.cur;"home"==t?e.reLaunch({url:"/pages/home/index"}):"classify"==t?(console.log(t),e.reLaunch({url:"/pages/classify/index"})):"activity"==t?e.reLaunch({url:"/pages/activity/index"}):"my"==t&&(this.isEmpty(e.getStorageSync("userinfo"))?e.reLaunch({url:"/pages/my/login"}):e.reLaunch({url:"/pages/my/index"}))},toTiao:function(){var n=!1;switch(e.getSystemInfoSync().platform){case"android":console.log("运行Android上");break;case"ios":console.log("运行iOS上");break;default:n=!0,console.log("运行在开发者工具上");break}n?e.navigateToMiniProgram({appId:"wx46c940ba5de1f3cd",path:"",extraData:{data1:"test"},success:function(e){console.log(e)},fail:function(e){console.log(e)}}):e.showToast({title:"只有在小程序环境下才可使用该功能！",duration:2e3,icon:"none"})}}};n.default=t}).call(this,t("543d")["default"])}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'colorui/components/cu-bar-create-component',
    {
        'colorui/components/cu-bar-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("d9ab"))
        })
    },
    [['colorui/components/cu-bar-create-component']]
]);
