(window.webpackJsonp=window.webpackJsonp||[]).push([[10],{385:function(e,t,r){"use strict";r.r(t);var o={props:["value"],components:{editor:r(396).a},data:function(){return{tinyConfig:{height:500,menubar:!1,plugins:["advlist autolink lists link image charmap print preview anchor","searchreplace visualblocks code fullscreen","insertdatetime media table paste code help wordcount"],toolbar:"undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | forecolor backcolor casechange permanentpen formatpainter removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media pageembed template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment",image_advtab:!0,image_title:!0,file_picker_types:"image",images_upload_handler:function(e,t,r){var data=new FormData;data.append("file",e.blob(),e.filename()),window.$nuxt.$axios.post("/editor/upload",data).then((function(e){t(e.data.location)})).catch((function(e){r("HTTP Error: "+e.message)}))}}}},computed:{contentVal:{get:function(){return this.value},set:function(e){this.$emit("input",e)}}}},n=r(51),component=Object(n.a)(o,(function(){var e=this,t=e.$createElement;return(e._self._c||t)("editor",{attrs:{"api-key":"lcidoke8z58yahye4qdipgpqiadzheladm0c6pbea3gt42pc",init:e.tinyConfig},model:{value:e.contentVal,callback:function(t){e.contentVal=t},expression:"contentVal"}})}),[],!1,null,null,null);t.default=component.exports},394:function(e,t,r){var content=r(395);content.__esModule&&(content=content.default),"string"==typeof content&&(content=[[e.i,content,""]]),content.locals&&(e.exports=content.locals);(0,r(16).default)("197fcea4",content,!0,{sourceMap:!1})},395:function(e,t,r){var o=r(15)(!1);o.push([e.i,'.v-chip:not(.v-chip--outlined).accent,.v-chip:not(.v-chip--outlined).error,.v-chip:not(.v-chip--outlined).info,.v-chip:not(.v-chip--outlined).primary,.v-chip:not(.v-chip--outlined).secondary,.v-chip:not(.v-chip--outlined).success,.v-chip:not(.v-chip--outlined).warning{color:#fff}.theme--light.v-chip{border-color:rgba(0,0,0,.12);color:rgba(0,0,0,.87)}.theme--light.v-chip:not(.v-chip--active){background:#e0e0e0}.theme--light.v-chip:hover:before{opacity:.04}.theme--light.v-chip--active:before,.theme--light.v-chip--active:hover:before,.theme--light.v-chip:focus:before{opacity:.12}.theme--light.v-chip--active:focus:before{opacity:.16}.theme--dark.v-chip{border-color:hsla(0,0%,100%,.12);color:#fff}.theme--dark.v-chip:not(.v-chip--active){background:#555}.theme--dark.v-chip:hover:before{opacity:.08}.theme--dark.v-chip--active:before,.theme--dark.v-chip--active:hover:before,.theme--dark.v-chip:focus:before{opacity:.24}.theme--dark.v-chip--active:focus:before{opacity:.32}.v-chip{align-items:center;cursor:default;display:inline-flex;line-height:20px;max-width:100%;outline:none;overflow:hidden;padding:0 12px;position:relative;text-decoration:none;transition-duration:.28s;transition-property:box-shadow,opacity;transition-timing-function:cubic-bezier(.4,0,.2,1);vertical-align:middle;white-space:nowrap}.v-chip:before{background-color:currentColor;bottom:0;border-radius:inherit;content:"";left:0;opacity:0;position:absolute;pointer-events:none;right:0;top:0}.v-chip .v-avatar{height:24px!important;min-width:24px!important;width:24px!important}.v-chip .v-icon{font-size:24px}.v-application--is-ltr .v-chip .v-avatar--left,.v-application--is-ltr .v-chip .v-icon--left{margin-left:-6px;margin-right:6px}.v-application--is-ltr .v-chip .v-avatar--right,.v-application--is-ltr .v-chip .v-icon--right,.v-application--is-rtl .v-chip .v-avatar--left,.v-application--is-rtl .v-chip .v-icon--left{margin-left:6px;margin-right:-6px}.v-application--is-rtl .v-chip .v-avatar--right,.v-application--is-rtl .v-chip .v-icon--right{margin-left:-6px;margin-right:6px}.v-chip:not(.v-chip--no-color) .v-icon{color:inherit}.v-chip .v-chip__close.v-icon{font-size:18px;max-height:18px;max-width:18px;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}.v-application--is-ltr .v-chip .v-chip__close.v-icon.v-icon--right{margin-right:-4px}.v-application--is-rtl .v-chip .v-chip__close.v-icon.v-icon--right{margin-left:-4px}.v-chip .v-chip__close.v-icon:active,.v-chip .v-chip__close.v-icon:focus,.v-chip .v-chip__close.v-icon:hover{opacity:.72}.v-chip .v-chip__content{align-items:center;display:inline-flex;height:100%;max-width:100%}.v-chip--active .v-icon{color:inherit}.v-chip--link:before{transition:opacity .3s cubic-bezier(.25,.8,.5,1)}.v-chip--link:focus:before{opacity:.32}.v-chip--clickable{cursor:pointer;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}.v-chip--clickable:active{box-shadow:0 3px 1px -2px rgba(0,0,0,.2),0 2px 2px 0 rgba(0,0,0,.14),0 1px 5px 0 rgba(0,0,0,.12)}.v-chip--disabled{opacity:.4;pointer-events:none;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}.v-chip__filter{max-width:24px}.v-chip__filter.v-icon{color:inherit}.v-chip__filter.expand-x-transition-enter,.v-chip__filter.expand-x-transition-leave-active{margin:0}.v-chip--pill .v-chip__filter{margin-right:0 16px 0 0}.v-chip--pill .v-avatar{height:32px!important;width:32px!important}.v-application--is-ltr .v-chip--pill .v-avatar--left{margin-left:-12px}.v-application--is-ltr .v-chip--pill .v-avatar--right,.v-application--is-rtl .v-chip--pill .v-avatar--left{margin-right:-12px}.v-application--is-rtl .v-chip--pill .v-avatar--right{margin-left:-12px}.v-chip--label{border-radius:4px!important}.v-chip.v-chip--outlined{border-width:thin;border-style:solid}.v-chip.v-chip--outlined.v-chip--active:before{opacity:.08}.v-chip.v-chip--outlined .v-icon{color:inherit}.v-chip.v-chip--outlined.v-chip.v-chip{background-color:transparent!important}.v-chip.v-chip--selected{background:transparent}.v-chip.v-chip--selected:after{opacity:.28}.v-chip.v-size--x-small{border-radius:8px;font-size:10px;height:16px}.v-chip.v-size--small{border-radius:12px;font-size:12px;height:24px}.v-chip.v-size--default{border-radius:16px;font-size:14px;height:32px}.v-chip.v-size--large{border-radius:27px;font-size:16px;height:54px}.v-chip.v-size--x-large{border-radius:33px;font-size:18px;height:66px}',""]),e.exports=o},414:function(e,t,r){"use strict";r(9),r(6),r(11),r(8),r(12);var o=r(14),n=r(2),c=(r(10),r(394),r(7)),l=r(182),h=r(94),v=r(24),d=r(114),f=r(27),m=r(45),x=r(75),y=r(115),_=r(5);function k(object,e){var t=Object.keys(object);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(object);e&&(r=r.filter((function(e){return Object.getOwnPropertyDescriptor(object,e).enumerable}))),t.push.apply(t,r)}return t}function w(e){for(var i=1;i<arguments.length;i++){var source=null!=arguments[i]?arguments[i]:{};i%2?k(Object(source),!0).forEach((function(t){Object(n.a)(e,t,source[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(source)):k(Object(source)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(source,t))}))}return e}t.a=Object(c.a)(v.a,y.a,x.a,f.a,Object(d.a)("chipGroup"),Object(m.b)("inputValue")).extend({name:"v-chip",props:{active:{type:Boolean,default:!0},activeClass:{type:String,default:function(){return this.chipGroup?this.chipGroup.activeClass:""}},close:Boolean,closeIcon:{type:String,default:"$delete"},closeLabel:{type:String,default:"$vuetify.close"},disabled:Boolean,draggable:Boolean,filter:Boolean,filterIcon:{type:String,default:"$complete"},label:Boolean,link:Boolean,outlined:Boolean,pill:Boolean,tag:{type:String,default:"span"},textColor:String,value:null},data:function(){return{proxyClass:"v-chip--active"}},computed:{classes:function(){return w(w(w(w({"v-chip":!0},x.a.options.computed.classes.call(this)),{},{"v-chip--clickable":this.isClickable,"v-chip--disabled":this.disabled,"v-chip--draggable":this.draggable,"v-chip--label":this.label,"v-chip--link":this.isLink,"v-chip--no-color":!this.color,"v-chip--outlined":this.outlined,"v-chip--pill":this.pill,"v-chip--removable":this.hasClose},this.themeClasses),this.sizeableClasses),this.groupClasses)},hasClose:function(){return Boolean(this.close)},isClickable:function(){return Boolean(x.a.options.computed.isClickable.call(this)||this.chipGroup)}},created:function(){var e=this;[["outline","outlined"],["selected","input-value"],["value","active"],["@input","@active.sync"]].forEach((function(t){var r=Object(o.a)(t,2),n=r[0],c=r[1];e.$attrs.hasOwnProperty(n)&&Object(_.a)(n,c,e)}))},methods:{click:function(e){this.$emit("click",e),this.chipGroup&&this.toggle()},genFilter:function(){var e=[];return this.isActive&&e.push(this.$createElement(h.a,{staticClass:"v-chip__filter",props:{left:!0}},this.filterIcon)),this.$createElement(l.b,e)},genClose:function(){var e=this;return this.$createElement(h.a,{staticClass:"v-chip__close",props:{right:!0,size:18},attrs:{"aria-label":this.$vuetify.lang.t(this.closeLabel)},on:{click:function(t){t.stopPropagation(),t.preventDefault(),e.$emit("click:close"),e.$emit("update:active",!1)}}},this.closeIcon)},genContent:function(){return this.$createElement("span",{staticClass:"v-chip__content"},[this.filter&&this.genFilter(),this.$slots.default,this.hasClose&&this.genClose()])}},render:function(e){var t=[this.genContent()],r=this.generateRouteLink(),o=r.tag,data=r.data;data.attrs=w(w({},data.attrs),{},{draggable:this.draggable?"true":void 0,tabindex:this.chipGroup&&!this.disabled?0:data.attrs.tabindex}),data.directives.push({name:"show",value:this.active}),data=this.setBackgroundColor(this.color,data);var n=this.textColor||this.outlined&&this.color;return e(o,this.setTextColor(n,data),t)}})},423:function(e,t,r){"use strict";var o=r(1),n=r(0);t.a=o.a.extend({name:"comparable",props:{valueComparator:{type:Function,default:n.i}}})},471:function(e,t,r){var content=r(529);content.__esModule&&(content=content.default),"string"==typeof content&&(content=[[e.i,content,""]]),content.locals&&(e.exports=content.locals);(0,r(16).default)("d5ca7f22",content,!0,{sourceMap:!1})},528:function(e,t,r){"use strict";r(471)},529:function(e,t,r){var o=r(15)(!1);o.push([e.i,".list-style-none{list-style:none;padding-left:0!important}",""]),e.exports=o},574:function(e,t,r){"use strict";r.r(t);var o=r(23),n=(r(79),{middleware:["auth","role"],components:{TinymceEditor:r(385).default},data:function(){return{valid:!0,titleRules:[function(e){return!!e||"Гарчиг оруулна уу"},function(e){return e&&e.length<=200||"Гарчигийн урт 200 аас хэтэрсэн байна!"}],priceRules:[function(e){return!!e||"Үнээ оруулна уу"}],imageRules:[function(e){return!e||e.size<2097152||"Зураг 2 MB аас хэтэрсэн байна!"}],dayRules:[function(e){return!!e||"Өдрөө оруулна уу"}],daytitleRules:[function(e){return!!e||"Өдрийн гарчиг оруулна уу"}],contentRules:[function(e){return!!e||"Дэлгэрэнгүй оруулна уу"}],isBusy:!1,isLoading:!1,errorStatus:null,errors:null,form:{title:"",price:"",is_paid:!1,image:null,day:"",day_title:"",content:""},imageUrl:""}},created:function(){this.fetchData()},methods:{fetchData:function(){var e=this;return Object(o.a)(regeneratorRuntime.mark((function t(){var r;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e.isBusy=!0,t.prev=1,t.next=4,e.$axios.get("/courses/"+e.$route.params.id+"/edit");case 4:r=t.sent,e.isBusy=!1,200==r.status&&e.setFormData(r.data.course),t.next=13;break;case 9:t.prev=9,t.t0=t.catch(1),e.isBusy=!1,console.log(t.t0);case 13:case"end":return t.stop()}}),t,null,[[1,9]])})))()},setFormData:function(e){console.log("course",e),this.form.title=e.title,this.form.price=e.price,this.form.is_paid=!!e.is_paid,this.form.content=e.content,this.form.day=e.day,this.form.day_title=e.day_title,this.imageUrl=e.image},validate:function(){this.$refs.form.validate()&&this.submitForm()},submitForm:function(){var e=this;return Object(o.a)(regeneratorRuntime.mark((function t(){var r;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e.isLoading=!0,e.errors=null,t.prev=2,(r=new FormData).append("title",e.form.title),r.append("price",e.form.price),r.append("is_paid",e.form.is_paid?1:0),r.append("image",e.form.image),r.append("day",e.form.day),r.append("day_title",e.form.day_title),r.append("content",e.form.content),t.next=13,e.$axios.post("/courses/"+e.$route.params.id+"/update",r);case 13:t.sent.data.success&&(e.$notify.showMessage({content:"Хөтөлбөр засагдлаа",color:"primary"}),e.$router.push("/courses")),t.next=23;break;case 17:t.prev=17,t.t0=t.catch(2),e.isLoading=!1,e.errorStatus=t.t0.response.status,422==t.t0.response.status?e.errors=t.t0.response.data:e.errors=t.t0.response.statusText,console.log(t.t0.response);case 23:case"end":return t.stop()}}),t,null,[[2,17]])})))()}}}),c=(r(528),r(51)),l=r(58),h=r.n(l),v=r(398),d=r(190),f=r(415),m=r(413),x=r(141),y=r(165),_=r(553),k=r(384),component=Object(c.a)(n,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"pa-4"},[r("h3",[e._v("Хөтөлбөр засах")]),e._v(" "),e.errorStatus&&e.errors?r("v-alert",{attrs:{dense:"",outlined:"",type:"error"}},[422==e.errorStatus?r("ul",{staticClass:"list-style-none"},e._l(e.errors,(function(t,o){return r("li",{key:o},[r("ul",e._l(t,(function(t,i){return r("li",{key:i},[e._v("\n            "+e._s(t)+"\n          ")])})),0)])})),0):r("div",[e._v(" "+e._s(e.errors))])]):e._e(),e._v(" "),e.isBusy?r("div",{staticClass:"text-center ma-3"},[r("v-progress-circular",{attrs:{indeterminate:"",color:"primary"}})],1):e._e(),e._v(" "),e.isBusy?e._e():r("v-form",{ref:"form",staticClass:"mt-3 mb-3",attrs:{"lazy-validation":""},model:{value:e.valid,callback:function(t){e.valid=t},expression:"valid"}},[r("v-text-field",{attrs:{rules:e.titleRules,label:"Гарчиг",required:"","error-message":"error"},model:{value:e.form.title,callback:function(t){e.$set(e.form,"title",t)},expression:"form.title"}}),e._v(" "),r("v-text-field",{attrs:{rules:e.priceRules,label:"Үнэ",required:"",type:"number","error-message":"error"},model:{value:e.form.price,callback:function(t){e.$set(e.form,"price",t)},expression:"form.price"}}),e._v(" "),r("v-file-input",{attrs:{rules:e.imageRules,accept:"image/png, image/jpeg, image/bmp",placeholder:"Зураг оруулна уу","prepend-icon":"mdi-camera",label:"Зураг"},model:{value:e.form.image,callback:function(t){e.$set(e.form,"image",t)},expression:"form.image"}}),e._v(" "),e.imageUrl?r("div",{staticClass:"mt-1 mb-4"},[r("v-img",{attrs:{"lazy-src":e.imageUrl,"max-height":"50","max-width":"100",src:e.imageUrl}})],1):e._e(),e._v(" "),r("v-switch",{attrs:{label:"Төлбөртэй болгох"},model:{value:e.form.is_paid,callback:function(t){e.$set(e.form,"is_paid",t)},expression:"form.is_paid"}}),e._v(" "),r("v-text-field",{attrs:{rules:e.dayRules,label:"Өдөр",required:"",type:"number","error-message":"error"},model:{value:e.form.day,callback:function(t){e.$set(e.form,"day",t)},expression:"form.day"}}),e._v(" "),r("v-text-field",{attrs:{rules:e.daytitleRules,label:"Өдрийн гарчиг",required:"","error-message":"error"},model:{value:e.form.day_title,callback:function(t){e.$set(e.form,"day_title",t)},expression:"form.day_title"}}),e._v(" "),r("div",{staticClass:"mt-3 mb-5"},[r("tinymce-editor",{model:{value:e.form.content,callback:function(t){e.$set(e.form,"content",t)},expression:"form.content"}})],1),e._v(" "),r("v-btn",{staticClass:"mr-4",attrs:{disabled:!e.valid||e.isLoading,loading:e.isLoading,color:"success"},on:{click:e.validate}},[e._v("\n      Засах\n    ")])],1)],1)}),[],!1,null,null,null);t.default=component.exports;h()(component,{TinymceEditor:r(385).default}),h()(component,{VAlert:v.a,VBtn:d.a,VFileInput:f.a,VForm:m.a,VImg:x.a,VProgressCircular:y.a,VSwitch:_.a,VTextField:k.a})}}]);