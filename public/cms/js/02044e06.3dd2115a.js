(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["02044e06"],{"632f":function(t,i,s){},8071:function(t,i,s){"use strict";s.r(i);var M=function(){var t=this,i=t.$createElement,M=t._self._c||i;return M("q-layout",{staticClass:"bg-grey-3",attrs:{view:"hHh lpR lFf"}},[M("q-header",{staticClass:"bg-primary text-white"},[M("q-toolbar",[M("q-btn",{attrs:{dense:"",flat:"",round:"",icon:"menu"},on:{click:function(i){t.left=!t.left}}}),M("q-toolbar-title",[M("q-avatar",[M("img",{attrs:{src:s("9b19")}})])],1),t.$store.state.ui.toolbar.editButton?M("q-btn",{staticClass:"bg-accent no-border-radius action-button last-action-button",attrs:{flat:"",size:"lg",icon:"edit",type:"a",label:t.$t("general.edit")},on:{click:function(i){return t.editBus.$emit("on-edit",{})}}}):t._e(),t.$store.state.ui.toolbar.saveButton?M("q-btn",{staticClass:"no-border-radius q-mr-sm",attrs:{flat:"",size:"md",type:"a",label:t.$t("general.cancel")},on:{click:function(i){return t.saveBus.$emit("on-cancel",{})}}}):t._e(),t.$store.state.ui.toolbar.saveButton?M("q-btn",{staticClass:"bg-positive no-border-radius action-button last-action-button",attrs:{flat:"",size:"lg",icon:"cloud_upload",type:"a",label:t.$t("general.save")},on:{click:function(i){return t.saveBus.$emit("on-save",{})}}}):t._e()],1)],1),M("q-drawer",{attrs:{side:"left",bordered:"",mini:t.miniState},on:{mouseover:function(i){t.miniState=!1},mouseout:function(i){t.miniState=!0}},model:{value:t.left,callback:function(i){t.left=i},expression:"left"}},[M("q-list",{staticClass:"rounded-borders text-info q-mt-lg"},t._l(t.$store.getters["ui/menu"],function(i){return M("q-item",{directives:[{name:"ripple",rawName:"v-ripple"}],key:i.label,attrs:{clickable:"",active:!1,"active-class":"bg-info text-white",to:{name:i.name,params:i.params}}},[M("q-item-section",{attrs:{avatar:""}},[M("q-icon",{attrs:{name:i.icon}})],1),M("q-item-section",[t._v(t._s(t.$t(i.label)))])],1)}),1)],1),M("q-page-container",[M("div",{staticClass:"q-pa-lg"},[M("router-view",{attrs:{editBus:t.editBus,saveBus:t.saveBus}})],1)])],1)},e=[],a=s("2b0e"),c={name:"InternalLayout",data:function(){return{left:!0,miniState:!0,editBus:new a["a"],saveBus:new a["a"]}}},w=c,n=(s("9803"),s("2877")),L=Object(n["a"])(w,M,e,!1,null,null,null);i["default"]=L.exports},9803:function(t,i,s){"use strict";var M=s("632f"),e=s.n(M);e.a},"9b19":function(t,i){t.exports="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDI0IDEwMjQiPjxkZWZzPjxzdHlsZT4uY2xzLTF7ZmlsbDojMjkyOTI5O30uY2xzLTJ7ZmlsbDojZmNmY2ZjO30uY2xzLTN7ZmlsbDojZmZmO30uY2xzLTR7ZmlsbDojMDc2REI1O308L3N0eWxlPjwvZGVmcz48dGl0bGU+RXhwb3J0YXI8L3RpdGxlPjxnIGlkPSJDaXJjdWxvIj48cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik01NDMuOTMsMUM1NDIuOTIsOS4zNiw1MjksMTYsNTEyLDE2UzQ4MS4wOCw5LjM2LDQ4MC4wNywxQzIxMi4xNywxNy40NywwLDI0MCwwLDUxMmMwLDI4Mi43NywyMjkuMjMsNTEyLDUxMiw1MTJzNTEyLTIyOS4yMyw1MTItNTEyQzEwMjQsMjQwLDgxMS44MywxNy40Nyw1NDMuOTMsMVoiLz48L2c+PGcgaWQ9IlBhdGFzIj48cGF0aCBjbGFzcz0iY2xzLTIiIGQ9Ik05MjMuODIsNDg1YTMxLjgzLDMxLjgzLDAsMCwxLTE4LjcyLTYuMDdDNzkwLjE4LDM5NS44OSw2NTQuMjUsMzUyLDUxMiwzNTJTMjMzLjgyLDM5NS44OSwxMTguOSw0NzguOTJhMzIsMzIsMCwxLDEtMzcuNDgtNTEuODcsNzM1LjE3LDczNS4xNywwLDAsMSw4NjEuMTYsMEEzMiwzMiwwLDAsMSw5MjMuODIsNDg1WiIvPjxwYXRoIGNsYXNzPSJjbHMtMiIgZD0iTTkxNS42Miw2NDUuMDhhMzEuODksMzEuODksMCwwLDEtMjIuNDItOS4xNyw1NDMuNDYsNTQzLjQ2LDAsMCwwLTc2Mi40LDBBMzIsMzIsMCwwLDEsODYsNTkwLjI1YTYwNy40Niw2MDcuNDYsMCwwLDEsODUyLjEsMCwzMiwzMiwwLDAsMS0yMi40Myw1NC44M1oiLz48cGF0aCBjbGFzcz0iY2xzLTMiIGQ9Ik04NTkuMzUsNzczYTMyLDMyLDAsMCwxLTI0LjgyLTExLjc3LDQxNiw0MTYsMCwwLDAtNjQ1LjA2LDAsMzIsMzIsMCwxLDEtNDkuNTktNDAuNDZDMjMxLjUzLDYwOC40NCwzNjcuMTYsNTQ0LDUxMiw1NDRzMjgwLjQ3LDY0LjQ0LDM3Mi4xMiwxNzYuOEEzMiwzMiwwLDAsMSw4NTkuMzUsNzczWiIvPjxwYXRoIGNsYXNzPSJjbHMtMyIgZD0iTTc3MS43MSw4NjlhMzIsMzIsMCwwLDEtMjYtMTMuMjgsMjkwLjcsMjkwLjcsMCwwLDAtMTAwLjQyLTg3LjA5LDI4OC41NiwyODguNTYsMCwwLDAtMjY2LjYyLDAsMjkwLjcsMjkwLjcsMCwwLDAtMTAwLjQyLDg3LjA5LDMyLDMyLDAsMCwxLTUxLjkxLTM3LjQ1LDM1MS45MiwzNTEuOTIsMCwwLDEsNTcxLjI4LDBBMzIsMzIsMCwwLDEsNzcxLjcxLDg2OVoiLz48L2c+PGcgaWQ9IkN1ZXJwbyI+PGNpcmNsZSBjbGFzcz0iY2xzLTMiIGN4PSI1MTIiIGN5PSI1MTIiIHI9IjI1NiIvPjxjaXJjbGUgY2xhc3M9ImNscy00IiBjeD0iNTEyIiBjeT0iNTEyIiByPSIxOTIiLz48cGF0aCBjbGFzcz0iY2xzLTIiIGQ9Ik01NDQsMXEtMTUuODctMS0zMi0xVDQ4MCwxVjI4My42aDY0WiIvPjxjaXJjbGUgY2xhc3M9ImNscy0zIiBjeD0iNDQ4IiBjeT0iNjQwIiByPSIzMiIvPjxjaXJjbGUgY2xhc3M9ImNscy0zIiBjeD0iNTc2IiBjeT0iNjQwIiByPSIzMiIvPjwvZz48L3N2Zz4="}}]);