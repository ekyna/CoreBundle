!function(a,b){function c(a,b){var c=a.createElement("p"),d=a.getElementsByTagName("head")[0]||a.documentElement;return c.innerHTML="x<style>"+b+"</style>",d.insertBefore(c.lastChild,d.firstChild)}function d(){var a=t.elements;return"string"==typeof a?a.split(" "):a}function e(a,b){var c=t.elements;"string"!=typeof c&&(c=c.join(" ")),"string"!=typeof a&&(a=a.join(" ")),t.elements=c+" "+a,j(b)}function f(a){var b=s[a[q]];return b||(b={},r++,a[q]=r,s[r]=b),b}function g(a,c,d){if(c||(c=b),l)return c.createElement(a);d||(d=f(c));var e;return e=d.cache[a]?d.cache[a].cloneNode():p.test(a)?(d.cache[a]=d.createElem(a)).cloneNode():d.createElem(a),!e.canHaveChildren||o.test(a)||e.tagUrn?e:d.frag.appendChild(e)}function h(a,c){if(a||(a=b),l)return a.createDocumentFragment();c=c||f(a);for(var e=c.frag.cloneNode(),g=0,h=d(),i=h.length;i>g;g++)e.createElement(h[g]);return e}function i(a,b){b.cache||(b.cache={},b.createElem=a.createElement,b.createFrag=a.createDocumentFragment,b.frag=b.createFrag()),a.createElement=function(c){return t.shivMethods?g(c,a,b):b.createElem(c)},a.createDocumentFragment=Function("h,f","return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&("+d().join().replace(/[\w\-:]+/g,function(a){return b.createElem(a),b.frag.createElement(a),'c("'+a+'")'})+");return n}")(t,b.frag)}function j(a){a||(a=b);var d=f(a);return!t.shivCSS||k||d.hasCSS||(d.hasCSS=!!c(a,"article,aside,dialog,figcaption,figure,footer,header,hgroup,main,nav,section{display:block}mark{background:#FF0;color:#000}template{display:none}")),l||i(a,d),a}var k,l,m="3.7.2",n=a.html5||{},o=/^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i,p=/^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i,q="_html5shiv",r=0,s={};!function(){try{var a=b.createElement("a");a.innerHTML="<xyz></xyz>",k="hidden"in a,l=1==a.childNodes.length||function(){b.createElement("a");var a=b.createDocumentFragment();return"undefined"==typeof a.cloneNode||"undefined"==typeof a.createDocumentFragment||"undefined"==typeof a.createElement}()}catch(c){k=!0,l=!0}}();var t={elements:n.elements||"abbr article aside audio bdi canvas data datalist details dialog figcaption figure footer header hgroup main mark meter nav output picture progress section summary template time video",version:m,shivCSS:n.shivCSS!==!1,supportsUnknownElements:l,shivMethods:n.shivMethods!==!1,type:"default",shivDocument:j,createElement:g,createDocumentFragment:h,addElements:e};a.html5=t,j(b)}(this,document),!function(a){"use strict";a.matchMedia=a.matchMedia||function(a){var b,c=a.documentElement,d=c.firstElementChild||c.firstChild,e=a.createElement("body"),f=a.createElement("div");return f.id="mq-test-1",f.style.cssText="position:absolute;top:-100em",e.style.background="none",e.appendChild(f),function(a){return f.innerHTML='&shy;<style media="'+a+'"> #mq-test-1 { width: 42px; }</style>',c.insertBefore(e,d),b=42===f.offsetWidth,c.removeChild(e),{matches:b,media:a}}}(a.document)}(this),function(a){"use strict";function b(){u(!0)}var c={};a.respond=c,c.update=function(){};var d=[],e=function(){var b=!1;try{b=new a.XMLHttpRequest}catch(c){b=new a.ActiveXObject("Microsoft.XMLHTTP")}return function(){return b}}(),f=function(a,b){var c=e();c&&(c.open("GET",a,!0),c.onreadystatechange=function(){4!==c.readyState||200!==c.status&&304!==c.status||b(c.responseText)},4!==c.readyState&&c.send(null))};if(c.ajax=f,c.queue=d,c.regex={media:/@media[^\{]+\{([^\{\}]*\{[^\}\{]*\})+/gi,keyframes:/@(?:\-(?:o|moz|webkit)\-)?keyframes[^\{]+\{(?:[^\{\}]*\{[^\}\{]*\})+[^\}]*\}/gi,urls:/(url\()['"]?([^\/\)'"][^:\)'"]+)['"]?(\))/g,findStyles:/@media *([^\{]+)\{([\S\s]+?)$/,only:/(only\s+)?([a-zA-Z]+)\s?/,minw:/\([\s]*min\-width\s*:[\s]*([\s]*[0-9\.]+)(px|em)[\s]*\)/,maxw:/\([\s]*max\-width\s*:[\s]*([\s]*[0-9\.]+)(px|em)[\s]*\)/},c.mediaQueriesSupported=a.matchMedia&&null!==a.matchMedia("only all")&&a.matchMedia("only all").matches,!c.mediaQueriesSupported){var g,h,i,j=a.document,k=j.documentElement,l=[],m=[],n=[],o={},p=30,q=j.getElementsByTagName("head")[0]||k,r=j.getElementsByTagName("base")[0],s=q.getElementsByTagName("link"),t=function(){var a,b=j.createElement("div"),c=j.body,d=k.style.fontSize,e=c&&c.style.fontSize,f=!1;return b.style.cssText="position:absolute;font-size:1em;width:1em",c||(c=f=j.createElement("body"),c.style.background="none"),k.style.fontSize="100%",c.style.fontSize="100%",c.appendChild(b),f&&k.insertBefore(c,k.firstChild),a=b.offsetWidth,f?k.removeChild(c):c.removeChild(b),k.style.fontSize=d,e&&(c.style.fontSize=e),a=i=parseFloat(a)},u=function(b){var c="clientWidth",d=k[c],e="CSS1Compat"===j.compatMode&&d||j.body[c]||d,f={},o=s[s.length-1],r=(new Date).getTime();if(b&&g&&p>r-g)return a.clearTimeout(h),void(h=a.setTimeout(u,p));g=r;for(var v in l)if(l.hasOwnProperty(v)){var w=l[v],x=w.minw,y=w.maxw,z=null===x,A=null===y,B="em";x&&(x=parseFloat(x)*(x.indexOf(B)>-1?i||t():1)),y&&(y=parseFloat(y)*(y.indexOf(B)>-1?i||t():1)),w.hasquery&&(z&&A||!(z||e>=x)||!(A||y>=e))||(f[w.media]||(f[w.media]=[]),f[w.media].push(m[w.rules]))}for(var C in n)n.hasOwnProperty(C)&&n[C]&&n[C].parentNode===q&&q.removeChild(n[C]);n.length=0;for(var D in f)if(f.hasOwnProperty(D)){var E=j.createElement("style"),F=f[D].join("\n");E.type="text/css",E.media=D,q.insertBefore(E,o.nextSibling),E.styleSheet?E.styleSheet.cssText=F:E.appendChild(j.createTextNode(F)),n.push(E)}},v=function(a,b,d){var e=a.replace(c.regex.keyframes,"").match(c.regex.media),f=e&&e.length||0;b=b.substring(0,b.lastIndexOf("/"));var g=function(a){return a.replace(c.regex.urls,"$1"+b+"$2$3")},h=!f&&d;b.length&&(b+="/"),h&&(f=1);for(var i=0;f>i;i++){var j,k,n,o;h?(j=d,m.push(g(a))):(j=e[i].match(c.regex.findStyles)&&RegExp.$1,m.push(RegExp.$2&&g(RegExp.$2))),n=j.split(","),o=n.length;for(var p=0;o>p;p++)k=n[p],l.push({media:k.split("(")[0].match(c.regex.only)&&RegExp.$2||"all",rules:m.length-1,hasquery:k.indexOf("(")>-1,minw:k.match(c.regex.minw)&&parseFloat(RegExp.$1)+(RegExp.$2||""),maxw:k.match(c.regex.maxw)&&parseFloat(RegExp.$1)+(RegExp.$2||"")})}u()},w=function(){if(d.length){var b=d.shift();f(b.href,function(c){v(c,b.href,b.media),o[b.href]=!0,a.setTimeout(function(){w()},0)})}},x=function(){for(var b=0;b<s.length;b++){var c=s[b],e=c.href,f=c.media,g=c.rel&&"stylesheet"===c.rel.toLowerCase();e&&g&&!o[e]&&(c.styleSheet&&c.styleSheet.rawCssText?(v(c.styleSheet.rawCssText,e,f),o[e]=!0):(!/^([a-zA-Z:]*\/\/)/.test(e)&&!r||e.replace(RegExp.$1,"").split("/")[0]===a.location.host)&&("//"===e.substring(0,2)&&(e=a.location.protocol+e),d.push({href:e,media:f})))}w()};x(),c.update=x,c.getEmValue=t,a.addEventListener?a.addEventListener("resize",b,!1):a.attachEvent&&a.attachEvent("onresize",b)}}(this),document.createElement("canvas").getContext||function(){function a(){return this.context_||(this.context_=new j(this))}function b(a,b){var c=x.call(arguments,2);return function(){return a.apply(b,c.concat(x.call(arguments)))}}function c(a){var b=a.srcElement;switch(a.propertyName){case"width":b.style.width=b.attributes.width.nodeValue+"px",b.getContext().clearRect();break;case"height":b.style.height=b.attributes.height.nodeValue+"px",b.getContext().clearRect()}}function d(a){var b=a.srcElement;b.firstChild&&(b.firstChild.style.width=b.clientWidth+"px",b.firstChild.style.height=b.clientHeight+"px")}function e(){return[[1,0,0],[0,1,0],[0,0,1]]}function f(a,b){for(var c=e(),d=0;3>d;d++)for(var f=0;3>f;f++){for(var g=0,h=0;3>h;h++)g+=a[d][h]*b[h][f];c[d][f]=g}return c}function g(a,b){b.fillStyle=a.fillStyle,b.lineCap=a.lineCap,b.lineJoin=a.lineJoin,b.lineWidth=a.lineWidth,b.miterLimit=a.miterLimit,b.shadowBlur=a.shadowBlur,b.shadowColor=a.shadowColor,b.shadowOffsetX=a.shadowOffsetX,b.shadowOffsetY=a.shadowOffsetY,b.strokeStyle=a.strokeStyle,b.globalAlpha=a.globalAlpha,b.arcScaleX_=a.arcScaleX_,b.arcScaleY_=a.arcScaleY_,b.lineScale_=a.lineScale_}function h(a){var b,c=1;if(a=String(a),"rgb"==a.substring(0,3)){var d=a.indexOf("(",3),e=a.indexOf(")",d+1),f=a.substring(d+1,e).split(",");b="#";for(var g=0;3>g;g++)b+=z[Number(f[g])];4==f.length&&"a"==a.substr(3,1)&&(c=f[3])}else b=a;return{color:b,alpha:c}}function i(a){switch(a){case"butt":return"flat";case"round":return"round";case"square":default:return"square"}}function j(a){this.m_=e(),this.mStack_=[],this.aStack_=[],this.currentPath_=[],this.fillStyle=this.strokeStyle="#000",this.lineWidth=1,this.lineJoin="miter",this.lineCap="butt",this.miterLimit=1*v,this.globalAlpha=1,this.canvas=a;var b=a.ownerDocument.createElement("div");b.style.width=a.clientWidth+"px",b.style.height=a.clientHeight+"px",b.style.overflow="hidden",b.style.position="absolute",a.appendChild(b),this.element_=b,this.lineScale_=this.arcScaleY_=this.arcScaleX_=1}function k(a,b,c,d){a.currentPath_.push({type:"bezierCurveTo",cp1x:b.x,cp1y:b.y,cp2x:c.x,cp2y:c.y,x:d.x,y:d.y}),a.currentX_=d.x,a.currentY_=d.y}function l(a){for(var b=0;3>b;b++)for(var c=0;2>c;c++)if(!isFinite(a[b][c])||isNaN(a[b][c]))return!1;return!0}function m(a,b,c){l(b)&&(a.m_=b,c&&(a.lineScale_=u(t(b[0][0]*b[1][1]-b[0][1]*b[1][0]))))}function n(a){this.type_=a,this.r1_=this.y1_=this.x1_=this.r0_=this.y0_=this.x0_=0,this.colors_=[]}function o(){}var p=Math,q=p.round,r=p.sin,s=p.cos,t=p.abs,u=p.sqrt,v=10,w=v/2,x=Array.prototype.slice,y={init:function(a){if(/MSIE/.test(navigator.userAgent)&&!window.opera){var c=a||document;c.createElement("canvas"),c.attachEvent("onreadystatechange",b(this.init_,this,c))}},init_:function(a){if(a.namespaces.g_vml_||a.namespaces.add("g_vml_","urn:schemas-microsoft-com:vml","#default#VML"),a.namespaces.g_o_||a.namespaces.add("g_o_","urn:schemas-microsoft-com:office:office","#default#VML"),!a.styleSheets.ex_canvas_){var b=a.createStyleSheet();b.owningElement.id="ex_canvas_",b.cssText="canvas{display:inline-block;overflow:hidden;text-align:left;width:300px;height:150px}g_vml_\\:*{behavior:url(#default#VML)}g_o_\\:*{behavior:url(#default#VML)}"}for(var c=a.getElementsByTagName("canvas"),d=0;d<c.length;d++)this.initElement(c[d])},initElement:function(b){if(!b.getContext){b.getContext=a,b.innerHTML="",b.attachEvent("onpropertychange",c),b.attachEvent("onresize",d);var e=b.attributes;e.width&&e.width.specified?b.style.width=e.width.nodeValue+"px":b.width=b.clientWidth,e.height&&e.height.specified?b.style.height=e.height.nodeValue+"px":b.height=b.clientHeight}return b}};y.init();for(var z=[],A=0;16>A;A++)for(var B=0;16>B;B++)z[16*A+B]=A.toString(16)+B.toString(16);var C=j.prototype;C.clearRect=function(){this.element_.innerHTML=""},C.beginPath=function(){this.currentPath_=[]},C.moveTo=function(a,b){var c=this.getCoords_(a,b);this.currentPath_.push({type:"moveTo",x:c.x,y:c.y}),this.currentX_=c.x,this.currentY_=c.y},C.lineTo=function(a,b){var c=this.getCoords_(a,b);this.currentPath_.push({type:"lineTo",x:c.x,y:c.y}),this.currentX_=c.x,this.currentY_=c.y},C.bezierCurveTo=function(a,b,c,d,e,f){var g=this.getCoords_(e,f),h=this.getCoords_(a,b),i=this.getCoords_(c,d);k(this,h,i,g)},C.quadraticCurveTo=function(a,b,c,d){var e=this.getCoords_(a,b),f=this.getCoords_(c,d),g={x:this.currentX_+.6666666666666666*(e.x-this.currentX_),y:this.currentY_+.6666666666666666*(e.y-this.currentY_)};k(this,g,{x:g.x+(f.x-this.currentX_)/3,y:g.y+(f.y-this.currentY_)/3},f)},C.arc=function(a,b,c,d,e,f){c*=v;var g=f?"at":"wa",h=a+s(d)*c-w,i=b+r(d)*c-w,j=a+s(e)*c-w,k=b+r(e)*c-w;h!=j||f||(h+=.125);var l=this.getCoords_(a,b),m=this.getCoords_(h,i),n=this.getCoords_(j,k);this.currentPath_.push({type:g,x:l.x,y:l.y,radius:c,xStart:m.x,yStart:m.y,xEnd:n.x,yEnd:n.y})},C.rect=function(a,b,c,d){this.moveTo(a,b),this.lineTo(a+c,b),this.lineTo(a+c,b+d),this.lineTo(a,b+d),this.closePath()},C.strokeRect=function(a,b,c,d){var e=this.currentPath_;this.beginPath(),this.moveTo(a,b),this.lineTo(a+c,b),this.lineTo(a+c,b+d),this.lineTo(a,b+d),this.closePath(),this.stroke(),this.currentPath_=e},C.fillRect=function(a,b,c,d){var e=this.currentPath_;this.beginPath(),this.moveTo(a,b),this.lineTo(a+c,b),this.lineTo(a+c,b+d),this.lineTo(a,b+d),this.closePath(),this.fill(),this.currentPath_=e},C.createLinearGradient=function(a,b,c,d){var e=new n("gradient");return e.x0_=a,e.y0_=b,e.x1_=c,e.y1_=d,e},C.createRadialGradient=function(a,b,c,d,e,f){var g=new n("gradientradial");return g.x0_=a,g.y0_=b,g.r0_=c,g.x1_=d,g.y1_=e,g.r1_=f,g},C.drawImage=function(a){var b,c,d,e,f,g,h,i,j=a.runtimeStyle.width,k=a.runtimeStyle.height;a.runtimeStyle.width="auto",a.runtimeStyle.height="auto";var l=a.width,m=a.height;if(a.runtimeStyle.width=j,a.runtimeStyle.height=k,3==arguments.length)b=arguments[1],c=arguments[2],f=g=0,h=d=l,i=e=m;else if(5==arguments.length)b=arguments[1],c=arguments[2],d=arguments[3],e=arguments[4],f=g=0,h=l,i=m;else{if(9!=arguments.length)throw Error("Invalid number of arguments");f=arguments[1],g=arguments[2],h=arguments[3],i=arguments[4],b=arguments[5],c=arguments[6],d=arguments[7],e=arguments[8]}var n=this.getCoords_(b,c),o=[];if(o.push(" <g_vml_:group",' coordsize="',10*v,",",10*v,'"',' coordorigin="0,0"',' style="width:',10,"px;height:",10,"px;position:absolute;"),1!=this.m_[0][0]||this.m_[0][1]){var r=[];r.push("M11=",this.m_[0][0],",","M12=",this.m_[1][0],",","M21=",this.m_[0][1],",","M22=",this.m_[1][1],",","Dx=",q(n.x/v),",","Dy=",q(n.y/v),"");var s=n,t=this.getCoords_(b+d,c),u=this.getCoords_(b,c+e),w=this.getCoords_(b+d,c+e);s.x=p.max(s.x,t.x,u.x,w.x),s.y=p.max(s.y,t.y,u.y,w.y),o.push("padding:0 ",q(s.x/v),"px ",q(s.y/v),"px 0;filter:progid:DXImageTransform.Microsoft.Matrix(",r.join(""),", sizingmethod='clip');")}else o.push("top:",q(n.y/v),"px;left:",q(n.x/v),"px;");o.push(' ">','<g_vml_:image src="',a.src,'"',' style="width:',v*d,"px;"," height:",v*e,'px;"',' cropleft="',f/l,'"',' croptop="',g/m,'"',' cropright="',(l-f-h)/l,'"',' cropbottom="',(m-g-i)/m,'"'," />","</g_vml_:group>"),this.element_.insertAdjacentHTML("BeforeEnd",o.join(""))},C.stroke=function(a){var b=[],c=h(a?this.fillStyle:this.strokeStyle),d=c.color,e=c.alpha*this.globalAlpha;b.push("<g_vml_:shape",' filled="',!!a,'"',' style="position:absolute;width:',10,"px;height:",10,'px;"',' coordorigin="0 0" coordsize="',10*v," ",10*v,'"',' stroked="',!a,'"',' path="');for(var f={x:null,y:null},g={x:null,y:null},j=0;j<this.currentPath_.length;j++){var k=this.currentPath_[j];switch(k.type){case"moveTo":b.push(" m ",q(k.x),",",q(k.y));break;case"lineTo":b.push(" l ",q(k.x),",",q(k.y));break;case"close":b.push(" x "),k=null;break;case"bezierCurveTo":b.push(" c ",q(k.cp1x),",",q(k.cp1y),",",q(k.cp2x),",",q(k.cp2y),",",q(k.x),",",q(k.y));break;case"at":case"wa":b.push(" ",k.type," ",q(k.x-this.arcScaleX_*k.radius),",",q(k.y-this.arcScaleY_*k.radius)," ",q(k.x+this.arcScaleX_*k.radius),",",q(k.y+this.arcScaleY_*k.radius)," ",q(k.xStart),",",q(k.yStart)," ",q(k.xEnd),",",q(k.yEnd))}k&&((null==f.x||k.x<f.x)&&(f.x=k.x),(null==g.x||k.x>g.x)&&(g.x=k.x),(null==f.y||k.y<f.y)&&(f.y=k.y),(null==g.y||k.y>g.y)&&(g.y=k.y))}if(b.push(' ">'),a)if("object"==typeof this.fillStyle){var l=this.fillStyle,m=0,n={x:0,y:0},o=0,r=1;if("gradient"==l.type_){var s=l.x1_/this.arcScaleX_,t=l.y1_/this.arcScaleY_,u=this.getCoords_(l.x0_/this.arcScaleX_,l.y0_/this.arcScaleY_),w=this.getCoords_(s,t);m=180*Math.atan2(w.x-u.x,w.y-u.y)/Math.PI,0>m&&(m+=360),1e-6>m&&(m=0)}else{var u=this.getCoords_(l.x0_,l.y0_),x=g.x-f.x,y=g.y-f.y;n={x:(u.x-f.x)/x,y:(u.y-f.y)/y},x/=this.arcScaleX_*v,y/=this.arcScaleY_*v;var z=p.max(x,y);o=2*l.r0_/z,r=2*l.r1_/z-o}var A=l.colors_;A.sort(function(a,b){return a.offset-b.offset});for(var B=A.length,C=A[0].color,D=A[B-1].color,E=A[0].alpha*this.globalAlpha,F=A[B-1].alpha*this.globalAlpha,G=[],j=0;B>j;j++){var H=A[j];G.push(H.offset*r+o+" "+H.color)}b.push('<g_vml_:fill type="',l.type_,'"',' method="none" focus="100%"',' color="',C,'"',' color2="',D,'"',' colors="',G.join(","),'"',' opacity="',F,'"',' g_o_:opacity2="',E,'"',' angle="',m,'"',' focusposition="',n.x,",",n.y,'" />')}else b.push('<g_vml_:fill color="',d,'" opacity="',e,'" />');else{var I=this.lineScale_*this.lineWidth;1>I&&(e*=I),b.push("<g_vml_:stroke",' opacity="',e,'"',' joinstyle="',this.lineJoin,'"',' miterlimit="',this.miterLimit,'"',' endcap="',i(this.lineCap),'"',' weight="',I,'px"',' color="',d,'" />')}b.push("</g_vml_:shape>"),this.element_.insertAdjacentHTML("beforeEnd",b.join(""))},C.fill=function(){this.stroke(!0)},C.closePath=function(){this.currentPath_.push({type:"close"})},C.getCoords_=function(a,b){var c=this.m_;return{x:v*(a*c[0][0]+b*c[1][0]+c[2][0])-w,y:v*(a*c[0][1]+b*c[1][1]+c[2][1])-w}},C.save=function(){var a={};g(this,a),this.aStack_.push(a),this.mStack_.push(this.m_),this.m_=f(e(),this.m_)},C.restore=function(){g(this.aStack_.pop(),this),this.m_=this.mStack_.pop()},C.translate=function(a,b){m(this,f([[1,0,0],[0,1,0],[a,b,1]],this.m_),!1)},C.rotate=function(a){var b=s(a),c=r(a);m(this,f([[b,c,0],[-c,b,0],[0,0,1]],this.m_),!1)},C.scale=function(a,b){this.arcScaleX_*=a,this.arcScaleY_*=b,m(this,f([[a,0,0],[0,b,0],[0,0,1]],this.m_),!0)},C.transform=function(a,b,c,d,e,g){m(this,f([[a,b,0],[c,d,0],[e,g,1]],this.m_),!0)},C.setTransform=function(a,b,c,d,e,f){m(this,[[a,b,0],[c,d,0],[e,f,1]],!0)},C.clip=function(){},C.arcTo=function(){},C.createPattern=function(){return new o},n.prototype.addColorStop=function(a,b){b=h(b),this.colors_.push({offset:a,color:b.color,alpha:b.alpha})},G_vmlCanvasManager=y,CanvasRenderingContext2D=j,CanvasGradient=n,CanvasPattern=o}();