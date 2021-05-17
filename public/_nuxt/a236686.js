(window.webpackJsonp=window.webpackJsonp||[]).push([[28],{385:function(e,t,r){"use strict";r.r(t);var n={props:["value"],components:{editor:r(396).a},data:function(){return{tinyConfig:{height:500,menubar:!1,plugins:["advlist autolink lists link image charmap print preview anchor","searchreplace visualblocks code fullscreen","insertdatetime media table paste code help wordcount"],toolbar:"undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | forecolor backcolor casechange permanentpen formatpainter removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media pageembed template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment",image_advtab:!0,image_title:!0,file_picker_types:"image",images_upload_handler:function(e,t,r){var data=new FormData;data.append("file",e.blob(),e.filename()),window.$nuxt.$axios.post("/editor/upload",data).then((function(e){t(e.data.location)})).catch((function(e){r("HTTP Error: "+e.message)}))}}}},computed:{contentVal:{get:function(){return this.value},set:function(e){this.$emit("input",e)}}}},o=r(51),component=Object(o.a)(n,(function(){var e=this,t=e.$createElement;return(e._self._c||t)("editor",{attrs:{"api-key":"lcidoke8z58yahye4qdipgpqiadzheladm0c6pbea3gt42pc",init:e.tinyConfig},model:{value:e.contentVal,callback:function(t){e.contentVal=t},expression:"contentVal"}})}),[],!1,null,null,null);t.default=component.exports},469:function(e,t,r){var content=r(525);content.__esModule&&(content=content.default),"string"==typeof content&&(content=[[e.i,content,""]]),content.locals&&(e.exports=content.locals);(0,r(16).default)("4da098b7",content,!0,{sourceMap:!1})},524:function(e,t,r){"use strict";r(469)},525:function(e,t,r){var n=r(15)(!1);n.push([e.i,".list-style-none{list-style:none;padding-left:0!important}",""]),e.exports=n},572:function(e,t,r){"use strict";r.r(t);var n=r(23),o=(r(29),r(79),{middleware:["auth","role"],components:{TinymceEditor:r(385).default},data:function(){return{valid:!0,typeOptions:[{text:"Мэдээлэл",value:"news"},{text:"Зөвлөмж",value:"advice"},{text:"Дасгал хөдөлгөөн",value:"exercise"}],nameRules:[function(e){return!!e||"Гарчиг оруулна уу"},function(e){return e&&e.length<=200||"Гарчигийн урт 200 аас хэтэрсэн байна!"}],subtitleRules:[function(e){return!!e||"Дэд тайлбар оруулна уу"},function(e){return e&&e.length<=1e3||"Дэд тайлбарын урт 1000 аас хэтэрсэн байна!"}],imageRules:[function(e){return!!e||"Зургаа оруулна уу"},function(e){return!e||e.size<2097152||"Зураг 2 MB аас хэтэрсэн байна!"}],contentRules:[function(e){return!!e||"Дэлгэрэнгүй оруулна уу"}],isBusy:!1,isLoading:!1,errorStatus:null,errors:null,categories:[],form:{categories:[],name:"",subtitle:"",is_featured:!1,image:null,content:""}}},created:function(){this.fetchData()},methods:{fetchData:function(){var e=this;return Object(n.a)(regeneratorRuntime.mark((function t(){var r;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e.isBusy=!0,t.prev=1,t.next=4,e.$axios.get("/recipe-categories");case 4:r=t.sent,e.isBusy=!1,200==r.status&&(e.categories=r.data),t.next=13;break;case 9:t.prev=9,t.t0=t.catch(1),e.isBusy=!1,console.log(t.t0);case 13:case"end":return t.stop()}}),t,null,[[1,9]])})))()},validate:function(){this.$refs.form.validate()&&this.submitForm()},submitForm:function(){var e=this;return Object(n.a)(regeneratorRuntime.mark((function t(){var r;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e.isLoading=!0,e.errors=null,t.prev=2,(r=new FormData).append("categories_id",e.form.categories),r.append("name",e.form.name),r.append("subtitle",e.form.subtitle),r.append("image",e.form.image),r.append("content",e.form.content),t.next=11,e.$axios.post("/recipes",r);case 11:t.sent.data.success&&(e.$notify.showMessage({content:"Жор нэмэгдлээ",color:"primary"}),e.$router.push("/recipes")),t.next=21;break;case 15:t.prev=15,t.t0=t.catch(2),e.isLoading=!1,e.errorStatus=t.t0.response.status,422==t.t0.response.status?e.errors=t.t0.response.data:e.errors=t.t0.response.statusText,console.log(t.t0.response);case 21:case"end":return t.stop()}}),t,null,[[2,15]])})))()}}}),l=(r(524),r(51)),c=r(58),m=r.n(c),d=r(398),f=r(447),v=r(190),h=r(415),_=r(413),x=r(165),y=r(384),k=r(448),component=Object(l.a)(o,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"pa-4"},[r("h3",[e._v("Шинэ жор")]),e._v(" "),e.errorStatus&&e.errors?r("v-alert",{attrs:{dense:"",outlined:"",type:"error"}},[422==e.errorStatus?r("ul",{staticClass:"list-style-none"},e._l(e.errors,(function(t,n){return r("li",{key:n},[r("ul",e._l(t,(function(t,i){return r("li",{key:i},[e._v("\n            "+e._s(t)+"\n          ")])})),0)])})),0):r("div",[e._v(" "+e._s(e.errors))])]):e._e(),e._v(" "),e.isBusy?r("div",{staticClass:"text-center ma-3"},[r("v-progress-circular",{attrs:{indeterminate:"",color:"primary"}})],1):e._e(),e._v(" "),e.isBusy?e._e():r("v-form",{ref:"form",staticClass:"mt-3 mb-3",attrs:{"lazy-validation":""},model:{value:e.valid,callback:function(t){e.valid=t},expression:"valid"}},[r("v-autocomplete",{attrs:{label:"Ангилал сонгох","item-text":"name","item-value":"id",header:"type",items:e.categories,multiple:"",chips:""},model:{value:e.form.categories,callback:function(t){e.$set(e.form,"categories",t)},expression:"form.categories"}}),e._v(" "),r("v-text-field",{attrs:{counter:200,rules:e.nameRules,label:"Нэр",required:"","error-message":"error"},model:{value:e.form.name,callback:function(t){e.$set(e.form,"name",t)},expression:"form.name"}}),e._v(" "),r("v-textarea",{attrs:{counter:1e3,rules:e.subtitleRules,label:"Дэд тайлбар",required:"","error-message":"error"},model:{value:e.form.subtitle,callback:function(t){e.$set(e.form,"subtitle",t)},expression:"form.subtitle"}}),e._v(" "),r("v-file-input",{attrs:{rules:e.imageRules,accept:"image/png, image/jpeg, image/bmp",placeholder:"Зураг оруулна уу","prepend-icon":"mdi-camera",label:"Зураг"},model:{value:e.form.image,callback:function(t){e.$set(e.form,"image",t)},expression:"form.image"}}),e._v(" "),r("div",{staticClass:"mt-3 mb-5"},[r("tinymce-editor",{model:{value:e.form.content,callback:function(t){e.$set(e.form,"content",t)},expression:"form.content"}})],1),e._v(" "),r("v-btn",{staticClass:"mr-4",attrs:{disabled:!e.valid||e.isLoading,loading:e.isLoading,color:"success"},on:{click:e.validate}},[e._v("\n      Нэмэх\n    ")])],1)],1)}),[],!1,null,null,null);t.default=component.exports;m()(component,{TinymceEditor:r(385).default}),m()(component,{VAlert:d.a,VAutocomplete:f.a,VBtn:v.a,VFileInput:h.a,VForm:_.a,VProgressCircular:x.a,VTextField:y.a,VTextarea:k.a})}}]);