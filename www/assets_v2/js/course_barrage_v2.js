// 弹幕
/*!Copyright(c) CommentCoreLibrary (//github.com/jabbany/CommentCoreLibrary) - Licensed under the MIT License */
function CommentFilter(){this.modifiers=[],this.runtime=null,this.allowTypes={1:!0,4:!0,5:!0,6:!0,7:!0,8:!0,17:!0},this.doModify=function(a){for(var b=0;b<this.modifiers.length;b++)a=this.modifiers[b](a);return a},this.beforeSend=function(a){return a},this.doValidate=function(a){return!!this.allowTypes[a.mode]},this.addRule=function(a){},this.addModifier=function(a){this.modifiers.push(a)},this.runtimeFilter=function(a){return null==this.runtime?a:this.runtime(a)},this.setRuntimeFilter=function(a){this.runtime=a}}function AcfunParser(a){var b=[];try{var c=JSON.parse(a)}catch(a){return console.log("Error: Could not parse json list!"),[]}for(var d=0;d<c.length;d++){var e={},f=c[d].c.split(",");if(f.length>0){if(e.stime=1e3*parseFloat(f[0]),e.color=parseInt(f[1]),e.mode=parseInt(f[2]),e.size=parseInt(f[3]),e.hash=f[4],e.date=parseInt(f[5]),e.position="absolute",7!=e.mode?(e.text=c[d].m.replace(/(\/n|\\n|\n|\r\n|\\r)/g,"\n"),e.text=e.text.replace(/\r/g,"\n"),e.text=e.text.replace(/\s/g," ")):e.text=c[d].m,7==e.mode){try{var g=JSON.parse(e.text)}catch(a){console.log("[Err] Error parsing internal data for comment"),console.log("[Dbg] "+e.text);continue}if(e.position="relative",e.text=g.n,e.text=e.text.replace(/\ /g," "),null!=g.a?e.opacity=g.a:e.opacity=1,null!=g.p?(e.x=g.p.x/1e3,e.y=g.p.y/1e3):(e.x=0,e.y=0),e.shadow=g.b,e.dur=4e3,null!=g.l&&(e.moveDelay=1e3*g.l),null!=g.z&&g.z.length>0){e.movable=!0,e.motion=[];for(var h=0,i={x:e.x,y:e.y,alpha:e.opacity,color:e.color},j=0;j<g.z.length;j++){var k=null!=g.z[j].l?1e3*g.z[j].l:500;h+=k;var l={x:{from:i.x,to:g.z[j].x/1e3,dur:k,delay:0},y:{from:i.y,to:g.z[j].y/1e3,dur:k,delay:0}};i.x=l.x.to,i.y=l.y.to,g.z[j].t!==i.alpha&&(l.alpha={from:i.alpha,to:g.z[j].t,dur:k,delay:0},i.alpha=l.alpha.to),null!=g.z[j].c&&g.z[j].c!==i.color&&(l.color={from:i.color,to:g.z[j].c,dur:k,delay:0},i.color=l.color.to),e.motion.push(l)}e.dur=h+(e.moveDelay?e.moveDelay:0)}null!=g.r&&null!=g.k&&(e.rX=g.r,e.rY=g.k)}b.push(e)}}return b}function BilibiliParser(a,b,c){function d(a){return a.replace(/\t/,"\\t")}function e(a){if("["==a.charAt(0))switch(a.charAt(a.length-1)){case"]":return a;case'"':return a+"]";case",":return a.substring(0,a.length-1)+'"]';default:return e(a.substring(0,a.length-1))}if("["!==a.charAt(0))return a}if(null!==a)var f=a.getElementsByTagName("d");else{if(!document||!document.createElement)return[];if(c){if(!confirm("XML Parse Error. \n Allow tag soup parsing?\n[WARNING: This is unsafe.]"))return[]}else b=b.replace(new RegExp("</([^d])","g"),"</disabled $1"),b=b.replace(new RegExp("</(S{2,})","g"),"</disabled $1"),b=b.replace(new RegExp("<([^d/]W*?)","g"),"<disabled $1"),b=b.replace(new RegExp("<([^/ ]{2,}W*?)","g"),"<disabled $1");var g=document.createElement("div");g.innerHTML=b;var f=g.getElementsByTagName("d")}for(var h=[],i=0;i<f.length;i++)if(null!=f[i].getAttribute("p")){var j=f[i].getAttribute("p").split(",");if(!f[i].childNodes[0])continue;var b=f[i].childNodes[0].nodeValue,k={};if(k.stime=Math.round(1e3*parseFloat(j[0])),k.size=parseInt(j[2]),k.color=parseInt(j[3]),k.mode=parseInt(j[1]),k.date=parseInt(j[4]),k.pool=parseInt(j[5]),k.position="absolute",null!=j[7]&&(k.dbid=parseInt(j[7])),k.hash=j[6],k.border=!1,k.mode<7)k.text=b.replace(/(\/n|\\n|\n|\r\n)/g,"\n");else if(7==k.mode)try{if(adv=JSON.parse(d(e(b))),k.shadow=!0,k.x=parseFloat(adv[0]),k.y=parseFloat(adv[1]),(Math.floor(k.x)<k.x||Math.floor(k.y)<k.y)&&(k.position="relative"),k.text=adv[4].replace(/(\/n|\\n|\n|\r\n)/g,"\n"),k.rZ=0,k.rY=0,adv.length>=7&&(k.rZ=parseInt(adv[5],10),k.rY=parseInt(adv[6],10)),k.motion=[],k.movable=!1,adv.length>=11){k.movable=!0;var l=500,m={x:{from:k.x,to:parseFloat(adv[7]),dur:l,delay:0},y:{from:k.y,to:parseFloat(adv[8]),dur:l,delay:0}};if(""!==adv[9]&&(l=parseInt(adv[9],10),m.x.dur=l,m.y.dur=l),""!==adv[10]&&(m.x.delay=parseInt(adv[10],10),m.y.delay=parseInt(adv[10],10)),adv.length>11&&(k.shadow=adv[11],"true"===k.shadow&&(k.shadow=!0),"false"===k.shadow&&(k.shadow=!1),null!=adv[12]&&(k.font=adv[12]),adv.length>14)){"relative"===k.position&&(console.log("Cannot mix relative and absolute positioning"),k.position="absolute");for(var n=adv[14],o={x:m.x.from,y:m.y.from},p=[],q=new RegExp("([a-zA-Z])\\s*(\\d+)[, ](\\d+)","g"),r=n.split(/[a-zA-Z]/).length-1,s=q.exec(n);null!==s;){switch(s[1]){case"M":o.x=parseInt(s[2],10),o.y=parseInt(s[3],10);break;case"L":p.push({x:{from:o.x,to:parseInt(s[2],10),dur:l/r,delay:0},y:{from:o.y,to:parseInt(s[3],10),dur:l/r,delay:0}}),o.x=parseInt(s[2],10),o.y=parseInt(s[3],10)}s=q.exec(n)}m=null,k.motion=p}null!==m&&k.motion.push(m)}k.dur=2500,adv[3]<12&&(k.dur=1e3*adv[3]);var g=adv[2].split("-");if(null!=g&&g.length>1){var t=parseFloat(g[0]),u=parseFloat(g[1]);k.opacity=t,t!==u&&(k.alpha={from:t,to:u})}}catch(a){console.log("[Err] Error occurred in JSON parsing"),console.log("[Dbg] "+b)}else 8==k.mode&&(k.code=b);null!=k.text&&(k.text=k.text.replace(/\u25a0/g,"█")),h.push(k)}return h}var BinArray=function(){var a={};return a.bsearch=function(a,b,c){if(0===a.length)return 0;if(c(b,a[0])<0)return 0;if(c(b,a[a.length-1])>=0)return a.length;for(var d=0,e=0,f=0,g=a.length-1;d<=g;){if(e=Math.floor((g+d+1)/2),f++,c(b,a[e-1])>=0&&c(b,a[e])<0)return e;c(b,a[e-1])<0?g=e-1:c(b,a[e])>=0?d=e:console.error("Program Error"),f>1500&&console.error("Too many run cycles.")}return-1},a.binsert=function(b,c,d){var e=a.bsearch(b,c,d);return b.splice(e,0,c),e},a}(),__extends=this&&this.__extends||function(a,b){function c(){this.constructor=a}for(var d in b)b.hasOwnProperty(d)&&(a[d]=b[d]);a.prototype=null===b?Object.create(b):(c.prototype=b.prototype,new c)},CommentSpaceAllocator=function(){function a(a,b){void 0===a&&(a=0),void 0===b&&(b=0),this._pools=[[]],this.avoid=1,this._width=a,this._height=b}return a.prototype.willCollide=function(a,b){return a.stime+a.ttl>=b.stime+b.ttl/2},a.prototype.pathCheck=function(a,b,c){for(var d=a+b.height,e=b.right,f=0;f<c.length;f++)if(!(c[f].y>d||c[f].bottom<a)){if(!(c[f].right<b.x||c[f].x>e))return!1;if(this.willCollide(c[f],b))return!1}return!0},a.prototype.assign=function(a,b){for(;this._pools.length<=b;)this._pools.push([]);var c=this._pools[b];if(0===c.length)return a.cindex=b,0;if(this.pathCheck(0,a,c))return a.cindex=b,0;for(var d=0,e=0;e<c.length&&(d=c[e].bottom+this.avoid,!(d+a.height>this._height));e++)if(this.pathCheck(d,a,c))return a.cindex=b,d;return this.assign(a,b+1)},a.prototype.add=function(a){a.height>this._height?(a.cindex=-2,a.y=0):(a.y=this.assign(a,0),BinArray.binsert(this._pools[a.cindex],a,function(a,b){return a.bottom<b.bottom?-1:a.bottom>b.bottom?1:0}))},a.prototype.remove=function(a){if(!(a.cindex<0)){if(a.cindex>=this._pools.length)throw new Error("cindex out of bounds");var b=this._pools[a.cindex].indexOf(a);b<0||this._pools[a.cindex].splice(b,1)}},a.prototype.setBounds=function(a,b){this._width=a,this._height=b},a}(),AnchorCommentSpaceAllocator=function(a){function b(){a.apply(this,arguments)}return __extends(b,a),b.prototype.add=function(b){a.prototype.add.call(this,b),b.x=(this._width-b.width)/2},b.prototype.willCollide=function(a,b){return!0},b.prototype.pathCheck=function(a,b,c){for(var d=a+b.height,e=0;e<c.length;e++)if(!(c[e].y>d||c[e].bottom<a))return!1;return!0},b}(CommentSpaceAllocator),__extends=this&&this.__extends||function(a,b){function c(){this.constructor=a}for(var d in b)b.hasOwnProperty(d)&&(a[d]=b[d]);a.prototype=null===b?Object.create(b):(c.prototype=b.prototype,new c)},CoreComment=function(){function a(b,c){if(void 0===c&&(c={}),this.mode=1,this.stime=0,this.text="",this.ttl=4e3,this.dur=4e3,this.cindex=-1,this.motion=[],this.movable=!0,this._alphaMotion=null,this.absolute=!0,this.align=0,this._alpha=1,this._size=25,this._color=16777215,this._border=!1,this._shadow=!0,this._font="",!b)throw new Error("Comment not bound to comment manager.");if(this.parent=b,c.hasOwnProperty("stime")&&(this.stime=c.stime),c.hasOwnProperty("mode")?this.mode=c.mode:this.mode=1,c.hasOwnProperty("dur")&&(this.dur=c.dur,this.ttl=this.dur),this.dur*=this.parent.options.global.scale,this.ttl*=this.parent.options.global.scale,c.hasOwnProperty("text")&&(this.text=c.text),c.hasOwnProperty("motion")){this._motionStart=[],this._motionEnd=[],this.motion=c.motion;for(var d=0,e=0;e<c.motion.length;e++){this._motionStart.push(d);var f=0;for(var g in c.motion[e]){var h=c.motion[e][g];f=Math.max(h.dur,f),null!==h.easing&&void 0!==h.easing||(c.motion[e][g].easing=a.LINEAR)}d+=f,this._motionEnd.push(d)}this._curMotion=0}c.hasOwnProperty("color")&&(this._color=c.color),c.hasOwnProperty("size")&&(this._size=c.size),c.hasOwnProperty("border")&&(this._border=c.border),c.hasOwnProperty("opacity")&&(this._alpha=c.opacity),c.hasOwnProperty("alpha")&&(this._alphaMotion=c.alpha),c.hasOwnProperty("font")&&(this._font=c.font),c.hasOwnProperty("x")&&(this._x=c.x),c.hasOwnProperty("y")&&(this._y=c.y),c.hasOwnProperty("shadow")&&(this._shadow=c.shadow),c.hasOwnProperty("position")&&"relative"===c.position&&(this.absolute=!1,this.mode<7&&console.warn("Using relative position for CSA comment."))}return a.prototype.init=function(a){void 0===a&&(a=null),null!==a?this.dom=a.dom:this.dom=document.createElement("div"),this.dom.className=this.parent.options.global.className,this.dom.innerHTML = this.text,this.size=this._size,16777215!=this._color&&(this.color=this._color),this.shadow=this._shadow,this._border&&(this.border=this._border),""!==this._font&&(this.font=this._font),void 0!==this._x&&(this.x=this._x),void 0!==this._y&&(this.y=this._y),(1!==this._alpha||this.parent.options.global.opacity<1)&&(this.alpha=this._alpha),this.motion.length>0&&this.animate()},Object.defineProperty(a.prototype,"x",{get:function(){return null!==this._x&&void 0!==this._x||(this.align%2===0?this._x=this.dom.offsetLeft:this._x=this.parent.width-this.dom.offsetLeft-this.width),this.absolute?this._x:this._x/this.parent.width},set:function(a){this._x=a,this.absolute||(this._x*=this.parent.width),this.align%2===0?this.dom.style.left=this._x+"px":this.dom.style.right=this._x+"px"},enumerable:!0,configurable:!0}),Object.defineProperty(a.prototype,"y",{get:function(){return null!==this._y&&void 0!==this._y||(this.align<2?this._y=this.dom.offsetTop:this._y=this.parent.height-this.dom.offsetTop-this.height),this.absolute?this._y:this._y/this.parent.height},set:function(a){this._y=a,this.absolute||(this._y*=this.parent.height),this.align<2?this.dom.style.top=this._y+"px":this.dom.style.bottom=this._y+"px"},enumerable:!0,configurable:!0}),Object.defineProperty(a.prototype,"bottom",{get:function(){return this.y+this.height},enumerable:!0,configurable:!0}),Object.defineProperty(a.prototype,"right",{get:function(){return this.x+this.width},enumerable:!0,configurable:!0}),Object.defineProperty(a.prototype,"width",{get:function(){return null!==this._width&&void 0!==this._width||(this._width=this.dom.offsetWidth),this._width},set:function(a){this._width=a,this.dom.style.width=this._width+"px"},enumerable:!0,configurable:!0}),Object.defineProperty(a.prototype,"height",{get:function(){return null!==this._height&&void 0!==this._height||(this._height=this.dom.offsetHeight),this._height},set:function(a){this._height=a,this.dom.style.height=this._height+"px"},enumerable:!0,configurable:!0}),Object.defineProperty(a.prototype,"size",{get:function(){return this._size},set:function(a){this._size=a,this.dom.style.fontSize=this._size+"px"},enumerable:!0,configurable:!0}),Object.defineProperty(a.prototype,"color",{get:function(){return this._color},set:function(a){this._color=a;var b=a.toString(16);b=b.length>=6?b:new Array(6-b.length+1).join("0")+b,this.dom.style.color="#"+b,0===this._color&&(this.dom.className=this.parent.options.global.className+" rshadow")},enumerable:!0,configurable:!0}),Object.defineProperty(a.prototype,"alpha",{get:function(){return this._alpha},set:function(a){this._alpha=a,this.dom.style.opacity=Math.min(this._alpha,this.parent.options.global.opacity)+""},enumerable:!0,configurable:!0}),Object.defineProperty(a.prototype,"border",{get:function(){return this._border},set:function(a){this._border=a,this._border?this.dom.style.border="1px solid #00ffff":this.dom.style.border="none"},enumerable:!0,configurable:!0}),Object.defineProperty(a.prototype,"shadow",{get:function(){return this._shadow},set:function(a){this._shadow=a,this._shadow||(this.dom.className=this.parent.options.global.className+" noshadow")},enumerable:!0,configurable:!0}),Object.defineProperty(a.prototype,"font",{get:function(){return this._font},set:function(a){this._font=a,this._font.length>0?this.dom.style.fontFamily=this._font:this.dom.style.fontFamily=""},enumerable:!0,configurable:!0}),a.prototype.time=function(a){this.ttl-=a,this.ttl<0&&(this.ttl=0),this.movable&&this.update(),this.ttl<=0&&this.finish()},a.prototype.update=function(){this.animate()},a.prototype.invalidate=function(){this._x=null,this._y=null,this._width=null,this._height=null},a.prototype._execMotion=function(a,b){for(var c in a)if(a.hasOwnProperty(c)){var d=a[c];this[c]=d.easing(Math.min(Math.max(b-d.delay,0),d.dur),d.from,d.to-d.from,d.dur)}},a.prototype.animate=function(){if(this._alphaMotion&&(this.alpha=(this.dur-this.ttl)*(this._alphaMotion.to-this._alphaMotion.from)/this.dur+this._alphaMotion.from),0!==this.motion.length){var a=Math.max(this.ttl,0),b=this.dur-a-this._motionStart[this._curMotion];return this._execMotion(this.motion[this._curMotion],b),this.dur-a>this._motionEnd[this._curMotion]?(this._curMotion++,void(this._curMotion>=this.motion.length&&(this._curMotion=this.motion.length-1))):void 0}},a.prototype.finish=function(){this.parent.finish(this)},a.prototype.toString=function(){return["[",this.stime,"|",this.ttl,"/",this.dur,"]","(",this.mode,")",this.text].join("")},a.LINEAR=function(a,b,c,d){return a*c/d+b},a}(),ScrollComment=function(a){function b(b,c){a.call(this,b,c),this.dur*=this.parent.options.scroll.scale,this.ttl*=this.parent.options.scroll.scale}return __extends(b,a),Object.defineProperty(b.prototype,"alpha",{set:function(a){this._alpha=a,this.dom.style.opacity=Math.min(Math.min(this._alpha,this.parent.options.global.opacity),this.parent.options.scroll.opacity)+""},enumerable:!0,configurable:!0}),b.prototype.init=function(b){void 0===b&&(b=null),a.prototype.init.call(this,b),this.x=this.parent.width,this.parent.options.scroll.opacity<1&&(this.alpha=this._alpha),this.absolute=!0},b.prototype.update=function(){this.x=this.ttl/this.dur*(this.parent.width+this.width)-this.width},b}(CoreComment),CommentManager=function(){function a(a){var b=0;this._listeners={},this._lastPosition=0,this.stage=a,this.options={global:{opacity:1,scale:1,className:"cmt"},scroll:{opacity:1,scale:1},limit:0},this.timeline=[],this.runline=[],this.position=0,this.limiter=0,this.filter=null,this.csa={scroll:new CommentSpaceAllocator(0,0),top:new AnchorCommentSpaceAllocator(0,0),bottom:new AnchorCommentSpaceAllocator(0,0),reverse:new CommentSpaceAllocator(0,0),scrollbtm:new CommentSpaceAllocator(0,0)},this.width=this.stage.offsetWidth,this.height=this.stage.offsetHeight,this.startTimer=function(){if(!(b>0)){var a=(new Date).getTime(),c=this;b=window.setInterval(function(){var b=(new Date).getTime()-a;a=(new Date).getTime(),c.onTimerEvent(b,c)},10)}},this.stopTimer=function(){window.clearInterval(b),b=0}}var b=function(a,b){for(var c=Math.PI/180,d=a*c,e=b*c,f=Math.cos,g=Math.sin,h=[f(d)*f(e),f(d)*g(e),g(d),0,-g(e),f(e),0,0,-g(d)*f(e),-g(d)*g(e),f(d),0,0,0,0,1],i=0;i<h.length;i++)Math.abs(h[i])<1e-6&&(h[i]=0);return"matrix3d("+h.join(",")+")"};return a.prototype.stop=function(){this.stopTimer()},a.prototype.start=function(){this.startTimer()},a.prototype.seek=function(a){this.position=BinArray.bsearch(this.timeline,a,function(a,b){return a<b.stime?-1:a>b.stime?1:0})},a.prototype.validate=function(a){return null!=a&&this.filter.doValidate(a)},a.prototype.load=function(a){this.timeline=a,this.timeline.sort(function(a,b){return a.stime>b.stime?2:a.stime<b.stime?-2:a.date>b.date?1:a.date<b.date?-1:null!=a.dbid&&null!=b.dbid?a.dbid>b.dbid?1:a.dbid<b.dbid?-1:0:0}),this.dispatchEvent("load")},a.prototype.insert=function(a){var b=BinArray.binsert(this.timeline,a,function(a,b){return a.stime>b.stime?2:a.stime<b.stime?-2:a.date>b.date?1:a.date<b.date?-1:null!=a.dbid&&null!=b.dbid?a.dbid>b.dbid?1:a.dbid<b.dbid?-1:0:0});b<=this.position&&this.position++,this.dispatchEvent("insert")},a.prototype.clear=function(){for(;this.runline.length>0;)this.runline[0].finish();this.dispatchEvent("clear")},a.prototype.setBounds=function(){this.width=this.stage.offsetWidth,this.height=this.stage.offsetHeight,this.dispatchEvent("resize");for(var a in this.csa)this.csa[a].setBounds(this.width,this.height);this.stage.style.perspective=this.width*Math.tan(40*Math.PI/180)/2+"px",this.stage.style.webkitPerspective=this.width*Math.tan(40*Math.PI/180)/2+"px"},a.prototype.init=function(){this.setBounds(),null==this.filter&&(this.filter=new CommentFilter)},a.prototype.time=function(a){if(a-=1,this.position>=this.timeline.length||Math.abs(this._lastPosition-a)>=2e3){if(this.seek(a),this._lastPosition=a,this.timeline.length<=this.position)return}else this._lastPosition=a;for(;this.position<this.timeline.length&&this.timeline[this.position].stime<=a;this.position++)this.options.limit>0&&this.runline.length>this.limiter||this.validate(this.timeline[this.position])&&this.send(this.timeline[this.position])},a.prototype.rescale=function(){},a.prototype.send=function(a){if(8===a.mode)return console.log(a),void(this.scripting&&console.log(this.scripting.eval(a.code)));if(null==this.filter||(a=this.filter.doModify(a),null!=a)){if(1===a.mode||2===a.mode||6===a.mode)var c=new ScrollComment(this,a);else var c=new CoreComment(this,a);switch(c.mode){case 1:c.align=0;break;case 2:c.align=2;break;case 4:c.align=2;break;case 5:c.align=0;break;case 6:c.align=1}switch(c.init(),this.stage.appendChild(c.dom),c.mode){default:case 1:this.csa.scroll.add(c);break;case 2:this.csa.scrollbtm.add(c);break;case 4:this.csa.bottom.add(c);break;case 5:this.csa.top.add(c);break;case 6:this.csa.reverse.add(c);break;case 17:case 7:0===a.rY&&0===a.rZ||(c.dom.style.transform=b(a.rY,a.rZ),c.dom.style.webkitTransform=b(a.rY,a.rZ),c.dom.style.OTransform=b(a.rY,a.rZ),c.dom.style.MozTransform=b(a.rY,a.rZ),c.dom.style.MSTransform=b(a.rY,a.rZ))}c.y=c.y,this.dispatchEvent("enterComment",c),this.runline.push(c)}},a.prototype.sendComment=function(a){console.log("CommentManager.sendComment is deprecated. Please use send instead"),this.send(a)},a.prototype.finish=function(a){this.dispatchEvent("exitComment",a),this.stage.removeChild(a.dom);var b=this.runline.indexOf(a);switch(b>=0&&this.runline.splice(b,1),a.mode){default:case 1:this.csa.scroll.remove(a);break;case 2:this.csa.scrollbtm.remove(a);break;case 4:this.csa.bottom.remove(a);break;case 5:this.csa.top.remove(a);break;case 6:this.csa.reverse.remove(a);break;case 7:}},a.prototype.addEventListener=function(a,b){"undefined"!=typeof this._listeners[a]?this._listeners[a].push(b):this._listeners[a]=[b]},a.prototype.dispatchEvent=function(a,b){if("undefined"!=typeof this._listeners[a])for(var c=0;c<this._listeners[a].length;c++)try{this._listeners[a][c](b)}catch(a){console.err(a.stack)}},a.prototype.onTimerEvent=function(a,b){for(var c=0;c<b.runline.length;c++){var d=b.runline[c];d.hold||d.time(a)}},a}();

$(function(){
        var config = {
        	size: [22,24,26],
        	xSize: 26,
            tSize: 26,
        	color: [0xffffff,0x00ccff,0xfbdb5c,0x80fc74],
        	xColor: 0xffffff,
            tColor: 0xff5346
        };
        var getText = function(content,type,thumb){
            var c = '';
            if(type == 'teacher'){
                c = '<span class="DM_teacher"><img class="head-photo" src="'+(!thumb?'':thumb)+'">'+content+'</span>'
            }else{
                c = content;
            }
            return c;
        };
        var getSize = function(type){
                var s = '';
        	if(type == 'self'){
        		s = config.xSize;
        	}else if(type == 'teacher'){
                s = config.tSize;
            }else{
        		s = config.size[Math.floor(Math.random()*config.size.length)];
        	}
            return s;
        };
        var getColor = function(type){
                var c = '';
        	if(type == 'teacher'){
        	       c = config.tColor;
        	}else if(type == 'self'){
                    c = config.xColor;
            }else{	
        	       c = config.color[Math.floor(Math.random()*config.size.length)]
        	}
            return c;
        };
        var getContent = function(opt){
            var op = {
                text: opt.text,
                type: opt.type || null,
                ex: opt.ex || {}
            };
        	var dft = {
        		mode: 1,
        		stime: op.stime || 0,
        		size: getSize(op.type),
        		color: getColor(op.type),
                text: getText(op.text,op.type,opt.thumb&&opt.thumb)
        	};
        	return $.extend({},dft,op.ex);
        };

        var DM = function(opt){
                if(!document.getElementById(opt.id)){
                    return false;
                }
                var dft = {
                    id: '', //弹幕漂浮容器 string
                    dur: 8, //弹幕生存时间 int
                    opacity: 0.8, //弹幕透明度 float 0-1
                    init: '', //初始化时执行的回调函数 function
                    seek: '', //时间跳转时的回调函数 function
                    load: '', //载入数据的回调函数 function
                    play: '', //载入数据的回调函数 function
                    pause: '', //载入数据的回调函数 function
                    send: '', //发送实时弹幕的回调函数 function
                    insert: '', //发送实时弹幕的回调函数 function
                    preData: [] //预置弹幕数据 [{},{},{}],
                };
                this.op = $.extend({},dft,opt);
                this.dbidList = [];
                this.container = document.getElementById(this.op.id);
                var CM = new CommentManager(this.container);
                CM.init();
                CM.options.scroll.scale = parseInt(this.op.dur) / 4;
                CM.options.global.opacity = 1;
                CM.options.limit = 1;
                CM.limiter = 300;
                this.CM = CM;
                this.lastSeek = 0;
                this.init();
        };
        DM.prototype = {
        	seek: function(time){//时间跳转时的回调函数
        		this.CM.time(time);
                this.CM.seek(time-1);
                if(typeof this.op.seek == 'function'){
                    this.op.seek.call(this,time);
                }
        	},
        	resize: function(){
                this.CM.setBounds();
        	},
        	load: function(data){//加载已有弹幕数据
                this.CM.load(data);
                if(typeof this.op.load == 'function'){
                    this.op.load.call(this,data);
                }
        	},
        	send: function(op){//发射弹幕
        		this.CM.send(getContent(op));
                if(typeof this.op.send == 'function'){
                    this.op.send.call(this);
                }
        	},
        	insert: function(op){
                if(op.ex && op.ex.dbid && this.dbidList.indexOf(op.ex.dbid) > -1){
                    return false;
                }
                op.ex && op.ex.dbid && this.dbidList.push(op.ex.dbid);
            	this.CM.insert(getContent(op));
                if(typeof this.op.insert == 'function'){
                    this.op.insert.call(this);
                }
        	},
        	play: function(){//开启弹幕
        		this.CM.start();
                if(typeof this.op.play == 'function'){
                    this.op.play.call(this);
                }
        	},
        	pause: function(){//暂停弹幕
        		this.CM.stop();
                if(typeof this.op.pause == 'function'){
                    this.op.pause.call(this);
                }
        	},
        	clean: function(){
        		this.CM.clear();
        	},
            remove: function(id){
                if(this.dbidList.indexOf(id) > -1){
                    var index = null;
                    this.list().forEach(function(x,i){
                        if(x.dbid = id){
                            index = i;
                        }
                    });
                    this.list().splice(index, 1);
                }
            },
            edit: function(id,newText){
                if(this.dbidList.indexOf(id) > -1){
                    var self = this;
                    this.list().forEach(function(x,i){
                        if(x.dbid = id){
                            self.list()[i].text = newText;
                        }
                    });
                }
            },
            list: function(){
                    return this.CM.timeline;
            },
            open: function(){
                var self = this;
                self.clean();
                $('.DM_box').show(0,function(){
                    self.resize();
                    self.clean();
                    self.play();
                });
            },
            close: function(){
                this.pause();
                this.clean();
                $('.DM_box').hide();
            },
        	init: function(){
                if(needDM){
                    this.play();
                }
                if(this.op.preData && this.op.preData.length > 0){
                    this.load(this.op.preData);
                }
                if(typeof this.op.init == 'function'){
                    this.op.init.call(this);
                }
        	}
        };

        // 定时加载函数
        var loadTimeList = [];
        var lazyLoadList = function(curTime){
            var nowLoadtime = Math.floor(curTime/10);
            if(loadTimeList.indexOf(nowLoadtime) < 0){
                var self = this;
                var data = message.getLiveTexts((nowLoadtime + 1) * 10,10);
                if(data == 'waiting'){
                    setTimeout(function(){
                        lazyLoadList.call(self,curTime);
                    },300);
                }else if(data.length > 0){
                    loadTimeList.push(nowLoadtime);
                    data.forEach(function(x){
                        if(self.dbidList.indexOf(x.msg_id) < 0){
                            var DM_content = {
                                text: x.c.replace(_flag2urlReg, flag2img),
                                type: x.is_teacher?'teacher':x.user_from_id == userId?'self':null,
                                ex:{
                                    dbid: x.msg_id,
                                    date: x.last_updated || '',
                                    stime: x.ls
                                }
                            };
                            if(x.is_teacher){
                                DM_content.thumb = filecdn+x.uf_t;
                            }
                            self.insert(DM_content);
                        }
                    });
                }
            }
        };

        // 公共接口
        window.DMS = {
                lastCurTime: 0,
                config:config,
                lastSeek: 0,
                init_comment: function(){
                    // 初始化 评论弹幕
                    var DM_comment_opt = {
                        id: 'DM_comment_stage',
                        init: function(){
                            this.isNew = false;
                            if(!isLiving){
                                lazyLoadList.call(this, -10);
                            }
                        }
                    };
                    if(!isLiving){
                        DM_comment_opt.seek = lazyLoadList;
                    }
                    window.DM_comment = new DM(DM_comment_opt);
                },
                init_note:function(){
                            // 初始化 笔记弹幕
                    window.DM_note = new DM({
                        id: 'DM_note_stage'
                    });
                },
                init: function(){
                    this.init_comment();
                    this.init_note();
                },
                resize: function(){
                        DM_comment.resize();
                        DM_note.resize();
                },
                updateTime: function(curTime){
                    if(curTime == this.lastCurTime){
                        return false;
                    }
                    this.lastCurTime = curTime;
                    DM_comment.seek(curTime);
                    DM_note.seek(curTime);
                },
                getContent: getContent,
                playOrPause: function(flag){
                    if(!flag){
                        // 暂停
                        DM_comment.pause();
                        DM_note.pause();
                    }else{
                        // 继续
                        DM_comment.play();
                        DM_note.play();
                    }
                },
                openOrClose: function(flag){
                    if(!flag){
                        // 关闭
                        DM_comment.close();
                        DM_note.close();
                        $.removeCookie('barrageOpen', { path: '/' });
                        needDM = false;
                    }else{
                        // 开启
                        DM_comment.open();
                        DM_note.open();
                        $.cookie('barrageOpen', '1',{ expires: 365,path: '/' });
                        needDM = true;
                    }
                }

        };
        DMS.init();


});