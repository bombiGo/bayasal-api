(window.webpackJsonp=window.webpackJsonp||[]).push([[13],{472:function(e,t,n){var content=n(531);content.__esModule&&(content=content.default),"string"==typeof content&&(content=[[e.i,content,""]]),content.locals&&(e.exports=content.locals);(0,n(16).default)("e04580e2",content,!0,{sourceMap:!1})},530:function(e,t,n){"use strict";n(472)},531:function(e,t,n){var r=n(15)(!1);r.push([e.i,".course-content img,.file-viewer img{max-width:100%!important;height:auto!important}",""]),e.exports=r},575:function(e,t,n){"use strict";n.r(t);var r=n(112),o=n(23),c=(n(28),n(30),n(60),n(260),n(19),n(74),n(79),{middleware:["auth","role"],data:function(){return{breadcrumbs:[{text:"Нүүр",disabled:!1,to:"/",exact:!0},{text:"Хөтөлбөрүүд",disabled:!1,to:"/courses",exact:!0},{text:"",disabled:!0}],isBusy:!1,course:null,dialog:!1,selectedLesson:"",groupLessons:{}}},created:function(){this.fetchData()},methods:{fetchData:function(){var e=this;return Object(o.a)(regeneratorRuntime.mark((function t(){var n;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e.isBusy=!0,t.prev=1,t.next=4,e.$axios.get("/courses/"+e.$route.params.id);case 4:n=t.sent,e.isBusy=!1,200==n.status&&(console.log(n.data),e.course=n.data.course,e.breadcrumbs[2].text=e.course.title,e.groupCategory(e.course.lessons)),t.next=13;break;case 9:t.prev=9,t.t0=t.catch(1),e.isBusy=!1,console.log(t.t0);case 13:case"end":return t.stop()}}),t,null,[[1,9]])})))()},groupCategory:function(e){var t=e.reduce((function(e,a){return e[a.day]=[].concat(Object(r.a)(e[a.day]||[]),[a]),e}),{});this.groupLessons=t},formatPrice:function(e){return(e/1).toFixed(0).replace(".",",").toString().replace(/\B(?=(\d{3})+(?!\d))/g,".")},showDialog:function(e){this.dialog=!0,this.selectedLesson=e,console.log(e)},remove:function(e){var t=this;return Object(o.a)(regeneratorRuntime.mark((function n(){return regeneratorRuntime.wrap((function(n){for(;;)switch(n.prev=n.next){case 0:if(!confirm("Та энэ хичээлийг устгах уу?")){n.next=11;break}return n.prev=1,n.next=4,t.$axios.delete("/lessons/"+e);case 4:n.sent.data.success&&(t.$notify.showMessage({content:"Хичээл устгагдлаа",color:"primary"}),t.fetchData()),n.next=11;break;case 8:n.prev=8,n.t0=n.catch(1),console.log(n.t0.response);case 11:case"end":return n.stop()}}),n,null,[[1,8]])})))()}}}),l=(n(530),n(51)),v=n(58),d=n.n(v),_=n(479),m=n(190),f=n(473),h=n(422),x=n(414),y=n(454),C=n(554),w=n(458),L=n(555),V=n(556),k=n(557),B=n(558),P=n(166),j=n(141),D=n(105),$=n(170),E=n(69),I=n(165),M=n(463),O=n(379),component=Object(l.a)(c,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("v-row",[e.isBusy?n("v-col",{attrs:{cols:"12"}},[e.isBusy?n("div",{staticClass:"text-center ma-3"},[n("v-progress-circular",{attrs:{indeterminate:"",color:"primary"}})],1):e._e()]):e._e(),e._v(" "),e.isBusy?e._e():n("v-col",{attrs:{cols:"12"}},[n("v-breadcrumbs",{attrs:{items:e.breadcrumbs,divider:"/"}})],1),e._v(" "),e.isBusy?e._e():n("v-col",{attrs:{cols:"12",md:"6"}},[n("v-card",{attrs:{elevation:"2"}},[n("v-card-text",[e.course.image?n("div",{staticClass:"mt-1 mb-4"},[n("v-img",{attrs:{"lazy-src":e.course.image,"max-width":"300",src:e.course.image}})],1):e._e(),e._v(" "),n("h2",{staticClass:"text--primary"},[e._v("\n          "+e._s(e.course.title)+"\n        ")])]),e._v(" "),n("v-list-item",[n("v-list-item-action",[e._v("\n          Үнэ:\n        ")]),e._v(" "),n("v-list-item-content",[e._v("\n          "+e._s(e.formatPrice(e.course.price))+" төг\n        ")])],1),e._v(" "),n("v-list-item",[n("v-list-item-action",[e._v("\n          Төлбөртэй:\n        ")]),e._v(" "),n("v-list-item-content",[n("div",[e.course.is_paid?n("v-chip",{attrs:{color:"success"}},[e._v("Тийм")]):n("v-chip",[e._v("Үгүй")])],1)])],1),e._v(" "),n("v-list-item",[n("v-list-item-action",[e._v("\n          Өдөр:\n        ")]),e._v(" "),n("v-list-item-content",[n("div",[e._v(e._s(e.course.day))])])],1),e._v(" "),n("v-list-item",[n("v-list-item-action",[e._v("\n          Өдрийн гарчиг:\n        ")]),e._v(" "),n("v-list-item-content",[n("div",[e._v(e._s(e.course.day_title))])])],1),e._v(" "),n("div",{staticClass:"pa-4 course-content"},[n("div",{staticClass:"text--primary"},[e._v("Дэлгэрэнгүй:")]),e._v(" "),n("div",{staticClass:"ma-4",domProps:{innerHTML:e._s(e.course.content)}})])],1)],1),e._v(" "),e.isBusy?e._e():n("v-col",{attrs:{cols:"12",md:"6"}},[n("v-card",{attrs:{elevation:"2"}},[n("v-card-title",[e._v("Хичээлүүд ("+e._s(e.course.lessons.length)+")")]),e._v(" "),n("v-btn",{staticClass:"ma-4 mt-0",attrs:{color:"primary",elevation:"2",to:"/courses/"+this.$route.params.id+"/lessons/create",small:""}},[n("v-icon",{attrs:{left:"",dark:""}},[e._v("mdi-plus")]),e._v("\n        Хичээл нэмэх\n      ")],1),e._v(" "),n("v-expansion-panels",{staticClass:"pa-4"},e._l(Object.keys(e.groupLessons),(function(t){return n("v-expansion-panel",{key:t},[n("v-expansion-panel-header",[e._v("\n            "+e._s(t)+"-р өдөр "),n("small",{staticClass:"ml-2 text--secondary"},[e._v("("+e._s(e.groupLessons[t].length)+" хичээл)")])]),e._v(" "),n("v-expansion-panel-content",e._l(e.groupLessons[t],(function(r,o){return n("v-list-item",{key:r.id},[n("v-list-item-content",[n("div",{staticClass:"body-2"},[e._v("Гарчиг: "+e._s(r.title))]),e._v(" "),n("div",{staticClass:"body-2"},[e._v("Төрөл: "+e._s(r.mode))]),e._v(" "),n("div",{staticClass:"body-2"},[e._v(e._s(r.duration))]),e._v(" "),n("div",{staticClass:"body-2"},[e._v("\n                  Файл үзэх: \n                  "),n("a",{attrs:{href:"javascript:;"},on:{click:function(t){return e.showDialog(r)}}},[e._v("Энд дарна уу")])]),e._v(" "),n("div",{staticClass:"mt-1 mb-1"},[n("v-chip",{attrs:{small:"",dark:"",color:r.is_locked?"orange":"primary"}},[e._v("\n                    "+e._s(r.is_locked?"Түгжээтэй":"Түгжээгүй")+"\n                  ")])],1),e._v(" "),n("div",{staticClass:"mt-4 mb-4"},[n("v-btn",{attrs:{elevation:"2",small:"",color:"blue",dark:"",to:"/courses/"+e.$route.params.id+"/lessons/"+r.id+"/edit"}},[e._v("\n                    Засах\n                  ")]),e._v(" "),n("v-btn",{attrs:{elevation:"2",small:"",color:"red",dark:""},on:{click:function(t){return e.remove(r.id)}}},[e._v("\n                    Устгах\n                  ")])],1),e._v(" "),o<e.groupLessons[t].length-1?n("v-divider"):e._e()],1)],1)})),1)],1)})),1)],1),e._v(" "),n("v-dialog",{attrs:{width:"500"},model:{value:e.dialog,callback:function(t){e.dialog=t},expression:"dialog"}},[n("v-card",[n("v-card-title",{staticClass:"headline grey lighten-2"},[e._v("\n          Файл үзэх\n        ")]),e._v(" "),e.selectedLesson?n("div",{staticClass:"pa-4 pb-0 file-viewer"},["content"==e.selectedLesson.mode?n("div",{domProps:{innerHTML:e._s(e.selectedLesson.content)}}):e._e(),e._v(" "),"pdf"==e.selectedLesson.mode?n("object",{attrs:{data:e.selectedLesson.pdf_url,type:"application/pdf",height:"500"}},[n("embed",{attrs:{src:e.selectedLesson.pdf_url,type:"application/pdf"}})]):e._e(),e._v(" "),"vimeo"==e.selectedLesson.mode?n("div",[n("iframe",{attrs:{src:"https://player.vimeo.com/video/"+e.selectedLesson.video_id,width:"100%",height:"360",frameborder:"0",allow:"autoplay; fullscreen; picture-in-picture",allowfullscreen:""}})]):e._e()]):e._e(),e._v(" "),n("v-divider"),e._v(" "),n("v-card-actions",["pdf"==e.selectedLesson.mode?n("a",{attrs:{href:e.selectedLesson.pdf_url,target:"_blank"}},[e._v("Томруулж харах")]):e._e(),e._v(" "),n("v-spacer"),e._v(" "),n("v-btn",{attrs:{text:""},on:{click:function(t){e.dialog=!1}}},[e._v("\n            Хаах\n          ")])],1)],1)],1)],1)],1)}),[],!1,null,null,null);t.default=component.exports;d()(component,{VBreadcrumbs:_.a,VBtn:m.a,VCard:f.a,VCardActions:h.a,VCardText:h.b,VCardTitle:h.c,VChip:x.a,VCol:y.a,VDialog:C.a,VDivider:w.a,VExpansionPanel:L.a,VExpansionPanelContent:V.a,VExpansionPanelHeader:k.a,VExpansionPanels:B.a,VIcon:P.a,VImg:j.a,VListItem:D.a,VListItemAction:$.a,VListItemContent:E.a,VProgressCircular:I.a,VRow:M.a,VSpacer:O.a})}}]);