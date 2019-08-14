!function r(s,a,d){function l(t,e){if(!a[t]){if(!s[t]){var i="function"==typeof require&&require;if(!e&&i)return i(t,!0);if(u)return u(t,!0);var o=new Error("Cannot find module '"+t+"'");throw o.code="MODULE_NOT_FOUND",o}var n=a[t]={exports:{}};s[t][0].call(n.exports,function(e){return l(s[t][1][e]||e)},n,n.exports,r,s,a,d)}return a[t].exports}for(var u="function"==typeof require&&require,e=0;e<d.length;e++)l(d[e]);return l}({1:[function(e,t,i){!function(){"use strict";Object.defineProperty(i,"__esModule",{value:!0});var t=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var i=arguments[t];for(var o in i)Object.prototype.hasOwnProperty.call(i,o)&&(e[o]=i[o])}return e};i.default={props:{disabled:{type:Boolean,default:!1},help:{type:String,default:""},label:{type:String,default:""},liststyle:{type:String,default:"table"},name:{type:[String,Number],default:""},required:{type:Boolean,default:!1},type:{type:String,default:"oembed"},value:{type:Array,default:function(){return[]}},debug:{type:Boolean,default:!1}},data:function(){return{embedCode:void 0,error:"",filledStatus:"closed",info:{},isLoading:!1,limit:1,url:""}},computed:{embedId:function(){return"embed-"+this._uid},sortedInfo:function(){var e=this.sortObjectKeys(this.info);return Object.assign({},t({title:e.title,description:e.description,provider_name:e.provider_name,provider_url:e.provider_url,html:e.html,width:e.width,height:e.height,thumbnail_url:e.thumbnail_url},e))}},watch:{value:function(e){this.url=e}},mounted:function(){var t=this;this.debug&&console.log(this.value),this.value&&this.value.length&&(this.url=this.value[0],this.url&&(this.isLoading=!0,this.getEmbed(this.url).then(function(e){t.updateUi(e)}).catch(function(e){t.error=e,t.$refs.dialog.open(),t.debug&&console.warn(e)}).finally(function(){t.isLoading=!1})))},methods:{sortObjectKeys:function(i){return Object.keys(i).sort().reduce(function(e,t){return e[t]=i[t],e},{})},onDelete:function(e){e&&(e.preventDefault(),e.stopPropagation()),this.embedCode=void 0,this.url="",this.value="",this.$emit("input",this.value)},onUrlValidate:function(e){var t=this;e&&(e.preventDefault(),e.stopPropagation()),this.embedCode=void 0,this.isLoading=!0,this.getEmbed(this.url).then(function(e){t.updateUi(e),t.value=t.url,t.$emit("input",t.value)}).catch(function(e){t.error=e,t.$refs.dialog.open(),t.debug&&console.warn(e)}).finally(function(){t.isLoading=!1})},toggle:function(e){this.filledStatus=e,this.$nextTick(function(){})},getEmbed:function(o){var n=this;return new Promise(function(t,i){if(o){var e={url:o};n.$api.post("oembed/preview",e).then(function(e){if(e&&e.data){if(e.data.xdebug_message||e.xdebug_message)throw Error(e.data.xdebug_message||e.xdebug_message);return e.data}throw Error()}).then(function(e){t(e)}).catch(function(e){n.debug&&console.log(e,n.$t("oembed.error."+e.message)),i(e&&e.message&&n.$t("oembed.error."+e.message)||n.$t("oembed.error.generic"))})}else i(n.$t("oembed.error.no_url_provided"))})},updateUi:function(e){this.embedCode=e.html,this.info=e,this.debug&&console.log(this.info)}}}}(),t.exports.__esModule&&(t.exports=t.exports.default);var o="function"==typeof t.exports?t.exports.options:t.exports;o.render=function(){var i=this,e=i.$createElement,o=i._self._c||e;return o("k-field",i._b({class:["k-oembed-field",i.status],attrs:{input:i._uid}},"k-field",i.$props,!1),[o("div",{staticClass:"k-input k-oembed-input",attrs:{"data-theme":"field"}},[o("input",{directives:[{name:"model",rawName:"v-model",value:i.url,expression:"url"}],ref:"input",staticClass:"k-text-input",attrs:{disabled:i.isLoading||i.disabled,placeholder:i.$t("oembed.placeholder")},domProps:{value:i.url},on:{input:function(e){e.target.composing||(i.url=e.target.value)}}}),i._v(" "),o("k-button",{class:[{disabled:!i.url.length}],attrs:{theme:"positive",icon:i.isLoading?"refresh":"check",disabled:i.isLoading},on:{click:i.onUrlValidate}},[i._v("\n      "+i._s(i.$t("oembed.validate"))+"\n    ")]),i._v(" "),o("k-button",{class:[{disabled:!i.url.length}],attrs:{theme:"negative",icon:i.isLoading?"dots":"cancel",disabled:i.isLoading},on:{click:i.onDelete}},[i._v("\n      "+i._s(i.$t("oembed.delete"))+"\n    ")])],1),i._v(" "),o("k-dialog",{ref:"dialog",on:{close:function(e){i.error=""}}},[o("k-text",{staticClass:"k-error-text"},[i._v("\n      "+i._s(i.$t("oembed.name"))+": "+i._s(i.$t("oembed.error"))+"\n      "),o("k-error-details",{staticClass:"k-error-details",domProps:{innerHTML:i._s(i.error)}})],1),i._v(" "),o("k-button-group",{attrs:{slot:"footer"},slot:"footer"},[o("k-button",{attrs:{icon:"check"},on:{click:function(e){return i.$refs.dialog.close()}}},[i._v("\n        "+i._s(i.$t("confirm"))+"\n      ")])],1)],1),i._v(" "),o("div",{staticClass:"k-oembed-container"},[i.embedCode?o("div",{staticClass:"oembed-container"},[o("div",{staticClass:"k-oembed-embed",attrs:{id:i.embedId},domProps:{innerHTML:i._s(i.embedCode)}}),i._v(" "),o("div",{class:["content",i.liststyle]},i._l(i.sortedInfo,function(e,t){return o("div",{staticClass:"content-block"},[o("div",{staticClass:"title"},[i._v("\n            "+i._s(i._f("capitalize")(!i.$t("oembed.embed."+t).includes("oembed")&&i.$t("oembed.embed."+t)||t))+"\n          ")]),i._v(" "),o("div",{staticClass:"value"},[i._v("\n            "+i._s(e)+"\n          ")])])}),0)]):i._e()]),i._v(" "),i.embedCode?i._e():o("k-empty",{staticClass:"k-oembed-empty",attrs:{icon:"code"},on:{click:function(e){return i.$refs.input.focus()}}},[i._v("\n    "+i._s(i.$t("oembed.empty"))+"\n  ")])],1)},o.staticRenderFns=[]},{}],2:[function(e,t,i){"use strict";var o,n=e(1),r=(o=n)&&o.__esModule?o:{default:o};panel.plugin("tasinttttttt/oembed",{fields:{oembed:r.default}})},{1:1}]},{},[2]);