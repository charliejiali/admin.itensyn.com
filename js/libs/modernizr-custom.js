/*! modernizr 3.3.1 (Custom Build) | MIT *
 * https://modernizr.com/download/?-atobbtoa-backgroundsize-bgpositionxy-bgrepeatspace_bgrepeatround-bgsizecover-borderimage-borderradius-boxshadow-boxsizing-canvas-checked-cookies-csscalc-csscolumns-cssescape-cssexunit-cssfilters-cssmask-csspointerevents-csspositionsticky-cssreflections-cssremunit-cssresize-csstransforms-csstransforms3d-csstransitions-cubicbezierrange-customevent-displaytable-ellipsis-filereader-filesystem-flash-flexbox-flexboxtweener-flexwrap-fontface-formvalidation-fullscreen-generatedcontent-geolocation-hsla-json-opacity-placeholder-rgba-subpixelfont-supports-svg-textalignlast-textshadow-touchevents-userselect-video-webgl-webp-webpalpha-xhrresponsetype-xhrresponsetypeblob-xhrresponsetypedocument-xhrresponsetypejson-xhrresponsetypetext-addtest-atrule-domprefixes-hasevent-mq-prefixed-prefixedcss-prefixedcssvalue-prefixes-setclasses-testallprops-testprop-teststyles !*/
!function(e,t,n){function o(e,t){return typeof e===t}function r(){var e,t,n,r,i,a,s;for(var d in y)if(y.hasOwnProperty(d)){if(e=[],t=y[d],t.name&&(e.push(t.name.toLowerCase()),t.options&&t.options.aliases&&t.options.aliases.length))for(n=0;n<t.options.aliases.length;n++)e.push(t.options.aliases[n].toLowerCase());for(r=o(t.fn,"function")?t.fn():t.fn,i=0;i<e.length;i++)a=e[i],s=a.split("."),1===s.length?Modernizr[s[0]]=r:(!Modernizr[s[0]]||Modernizr[s[0]]instanceof Boolean||(Modernizr[s[0]]=new Boolean(Modernizr[s[0]])),Modernizr[s[0]][s[1]]=r),b.push((r?"":"no-")+s.join("-"))}}function i(e){var t=C.className,n=Modernizr._config.classPrefix||"";if(R&&(t=t.baseVal),Modernizr._config.enableJSClass){var o=new RegExp("(^|\\s)"+n+"no-js(\\s|$)");t=t.replace(o,"$1"+n+"js$2")}Modernizr._config.enableClasses&&(t+=" "+n+e.join(" "+n),R?C.className.baseVal=t:C.className=t)}function a(e,t){if("object"==typeof e)for(var n in e)E(e,n)&&a(n,e[n]);else{e=e.toLowerCase();var o=e.split("."),r=Modernizr[o[0]];if(2==o.length&&(r=r[o[1]]),"undefined"!=typeof r)return Modernizr;t="function"==typeof t?t():t,1==o.length?Modernizr[o[0]]=t:(!Modernizr[o[0]]||Modernizr[o[0]]instanceof Boolean||(Modernizr[o[0]]=new Boolean(Modernizr[o[0]])),Modernizr[o[0]][o[1]]=t),i([(t&&0!=t?"":"no-")+o.join("-")]),Modernizr._trigger(e,t)}return Modernizr}function s(){return"function"!=typeof t.createElement?t.createElement(arguments[0]):R?t.createElementNS.call(t,"http://www.w3.org/2000/svg",arguments[0]):t.createElement.apply(t,arguments)}function d(e){return e.replace(/([a-z])-([a-z])/g,function(e,t,n){return t+n.toUpperCase()}).replace(/^-/,"")}function l(e){return e.replace(/([A-Z])/g,function(e,t){return"-"+t.toLowerCase()}).replace(/^ms-/,"-ms-")}function c(){var e=t.body;return e||(e=s(R?"svg":"body"),e.fake=!0),e}function u(e,n,o,r){var i,a,d,l,u="modernizr",p=s("div"),f=c();if(parseInt(o,10))for(;o--;)d=s("div"),d.id=r?r[o]:u+(o+1),p.appendChild(d);return i=s("style"),i.type="text/css",i.id="s"+u,(f.fake?f:p).appendChild(i),f.appendChild(p),i.styleSheet?i.styleSheet.cssText=e:i.appendChild(t.createTextNode(e)),p.id=u,f.fake&&(f.style.background="",f.style.overflow="hidden",l=C.style.overflow,C.style.overflow="hidden",C.appendChild(f)),a=n(p,e),f.fake?(f.parentNode.removeChild(f),C.style.overflow=l,C.offsetHeight):p.parentNode.removeChild(p),!!a}function p(e,t){return!!~(""+e).indexOf(t)}function f(t,o){var r=t.length;if("CSS"in e&&"supports"in e.CSS){for(;r--;)if(e.CSS.supports(l(t[r]),o))return!0;return!1}if("CSSSupportsRule"in e){for(var i=[];r--;)i.push("("+l(t[r])+":"+o+")");return i=i.join(" or "),u("@supports ("+i+") { #modernizr { position: absolute; } }",function(e){return"absolute"==getComputedStyle(e,null).position})}return n}function A(e,t,r,i){function a(){c&&(delete M.style,delete M.modElem)}if(i=o(i,"undefined")?!1:i,!o(r,"undefined")){var l=f(e,r);if(!o(l,"undefined"))return l}for(var c,u,A,v,m,g=["modernizr","tspan","samp"];!M.style&&g.length;)c=!0,M.modElem=s(g.shift()),M.style=M.modElem.style;for(A=e.length,u=0;A>u;u++)if(v=e[u],m=M.style[v],p(v,"-")&&(v=d(v)),M.style[v]!==n){if(i||o(r,"undefined"))return a(),"pfx"==t?v:!0;try{M.style[v]=r}catch(h){}if(M.style[v]!=m)return a(),"pfx"==t?v:!0}return a(),!1}function v(e,t){return function(){return e.apply(t,arguments)}}function m(e,t,n){var r;for(var i in e)if(e[i]in t)return n===!1?e[i]:(r=t[e[i]],o(r,"function")?v(r,n||t):r);return!1}function g(e,t,n,r,i){var a=e.charAt(0).toUpperCase()+e.slice(1),s=(e+" "+_.join(a+" ")+a).split(" ");return o(t,"string")||o(t,"undefined")?A(s,t,r,i):(s=(e+" "+B.join(a+" ")+a).split(" "),m(s,t,n))}function h(e,t,o){return g(e,n,n,t,o)}var b=[],y=[],x={_version:"3.3.1",_config:{classPrefix:"",enableClasses:!0,enableJSClass:!0,usePrefixes:!0},_q:[],on:function(e,t){var n=this;setTimeout(function(){t(n[e])},0)},addTest:function(e,t,n){y.push({name:e,fn:t,options:n})},addAsyncTest:function(e){y.push({name:null,fn:e})}},Modernizr=function(){};Modernizr.prototype=x,Modernizr=new Modernizr,Modernizr.addTest("cookies",function(){try{t.cookie="cookietest=1";var e=-1!=t.cookie.indexOf("cookietest=");return t.cookie="cookietest=1; expires=Thu, 01-Jan-1970 00:00:01 GMT",e}catch(n){return!1}}),Modernizr.addTest("customevent","CustomEvent"in e&&"function"==typeof e.CustomEvent),Modernizr.addTest("geolocation","geolocation"in navigator),Modernizr.addTest("json","JSON"in e&&"parse"in JSON&&"stringify"in JSON),Modernizr.addTest("svg",!!t.createElementNS&&!!t.createElementNS("http://www.w3.org/2000/svg","svg").createSVGRect);var T=e.CSS;Modernizr.addTest("cssescape",T?"function"==typeof T.escape:!1);var w="CSS"in e&&"supports"in e.CSS,k="supportsCSS"in e;Modernizr.addTest("supports",w||k),Modernizr.addTest("filereader",!!(e.File&&e.FileList&&e.FileReader)),Modernizr.addTest("xhrresponsetype",function(){if("undefined"==typeof XMLHttpRequest)return!1;var e=new XMLHttpRequest;return e.open("get","/",!0),"response"in e}()),Modernizr.addTest("atobbtoa","atob"in e&&"btoa"in e,{aliases:["atob-btoa"]});var S=x._config.usePrefixes?" -webkit- -moz- -o- -ms- ".split(" "):["",""];x._prefixes=S;var C=t.documentElement,R="svg"===C.nodeName.toLowerCase(),z="Moz O ms Webkit",B=x._config.usePrefixes?z.toLowerCase().split(" "):[];x._domPrefixes=B;var E;!function(){var e={}.hasOwnProperty;E=o(e,"undefined")||o(e.call,"undefined")?function(e,t){return t in e&&o(e.constructor.prototype[t],"undefined")}:function(t,n){return e.call(t,n)}}(),x._l={},x.on=function(e,t){this._l[e]||(this._l[e]=[]),this._l[e].push(t),Modernizr.hasOwnProperty(e)&&setTimeout(function(){Modernizr._trigger(e,Modernizr[e])},0)},x._trigger=function(e,t){if(this._l[e]){var n=this._l[e];setTimeout(function(){var e,o;for(e=0;e<n.length;e++)(o=n[e])(t)},0),delete this._l[e]}},Modernizr._q.push(function(){x.addTest=a}),Modernizr.addAsyncTest(function(){var e=new Image;e.onerror=function(){a("webpalpha",!1,{aliases:["webp-alpha"]})},e.onload=function(){a("webpalpha",1==e.width,{aliases:["webp-alpha"]})},e.src="data:image/webp;base64,UklGRkoAAABXRUJQVlA4WAoAAAAQAAAAAAAAAAAAQUxQSAwAAAABBxAR/Q9ERP8DAABWUDggGAAAADABAJ0BKgEAAQADADQlpAADcAD++/1QAA=="}),Modernizr.addAsyncTest(function(){function e(e,t,n){function o(t){var o=t&&"load"===t.type?1==r.width:!1,i="webp"===e;a(e,i?new Boolean(o):o),n&&n(t)}var r=new Image;r.onerror=o,r.onload=o,r.src=t}var t=[{uri:"data:image/webp;base64,UklGRiQAAABXRUJQVlA4IBgAAAAwAQCdASoBAAEAAwA0JaQAA3AA/vuUAAA=",name:"webp"},{uri:"data:image/webp;base64,UklGRkoAAABXRUJQVlA4WAoAAAAQAAAAAAAAAAAAQUxQSAwAAAABBxAR/Q9ERP8DAABWUDggGAAAADABAJ0BKgEAAQADADQlpAADcAD++/1QAA==",name:"webp.alpha"},{uri:"data:image/webp;base64,UklGRlIAAABXRUJQVlA4WAoAAAASAAAAAAAAAAAAQU5JTQYAAAD/////AABBTk1GJgAAAAAAAAAAAAAAAAAAAGQAAABWUDhMDQAAAC8AAAAQBxAREYiI/gcA",name:"webp.animation"},{uri:"data:image/webp;base64,UklGRh4AAABXRUJQVlA4TBEAAAAvAAAAAAfQ//73v/+BiOh/AAA=",name:"webp.lossless"}],n=t.shift();e(n.name,n.uri,function(n){if(n&&"load"===n.type)for(var o=0;o<t.length;o++)e(t[o].name,t[o].uri)})});var _=x._config.usePrefixes?z.split(" "):[];x._cssomPrefixes=_;var P=function(t){var o,r=S.length,i=e.CSSRule;if("undefined"==typeof i)return n;if(!t)return!1;if(t=t.replace(/^@/,""),o=t.replace(/-/g,"_").toUpperCase()+"_RULE",o in i)return"@"+t;for(var a=0;r>a;a++){var s=S[a],d=s.toUpperCase()+"_"+o;if(d in i)return"@-"+s.toLowerCase()+"-"+t}return!1};x.atRule=P;var Q=function(){function e(e,t){var r;return e?(t&&"string"!=typeof t||(t=s(t||"div")),e="on"+e,r=e in t,!r&&o&&(t.setAttribute||(t=s("div")),t.setAttribute(e,""),r="function"==typeof t[e],t[e]!==n&&(t[e]=n),t.removeAttribute(e)),r):!1}var o=!("onblur"in t.documentElement);return e}();x.hasEvent=Q;var L=function(e,t){var n=!1,o=s("div"),r=o.style;if(e in r){var i=B.length;for(r[e]=t,n=r[e];i--&&!n;)r[e]="-"+B[i]+"-"+t,n=r[e]}return""===n&&(n=!1),n};x.prefixedCSSValue=L,Modernizr.addTest("canvas",function(){var e=s("canvas");return!(!e.getContext||!e.getContext("2d"))}),Modernizr.addTest("webgl",function(){var t=s("canvas"),n="probablySupportsContext"in t?"probablySupportsContext":"supportsContext";return n in t?t[n]("webgl")||t[n]("experimental-webgl"):"WebGLRenderingContext"in e}),Modernizr.addTest("video",function(){var e=s("video"),t=!1;try{(t=!!e.canPlayType)&&(t=new Boolean(t),t.ogg=e.canPlayType('video/ogg; codecs="theora"').replace(/^no$/,""),t.h264=e.canPlayType('video/mp4; codecs="avc1.42E01E"').replace(/^no$/,""),t.webm=e.canPlayType('video/webm; codecs="vp8, vorbis"').replace(/^no$/,""),t.vp9=e.canPlayType('video/webm; codecs="vp9"').replace(/^no$/,""),t.hls=e.canPlayType('application/x-mpegURL; codecs="avc1.42E01E"').replace(/^no$/,""))}catch(n){}return t}),Modernizr.addTest("csscalc",function(){var e="width:",t="calc(10px);",n=s("a");return n.style.cssText=e+S.join(t+e),!!n.style.length}),Modernizr.addTest("cubicbezierrange",function(){var e=s("a");return e.style.cssText=S.join("transition-timing-function:cubic-bezier(1,0,0,1.1); "),!!e.style.length}),Modernizr.addTest("opacity",function(){var e=s("a").style;return e.cssText=S.join("opacity:.55;"),/^0.55$/.test(e.opacity)}),Modernizr.addTest("csspointerevents",function(){var e=s("a").style;return e.cssText="pointer-events:auto","auto"===e.pointerEvents}),Modernizr.addTest("csspositionsticky",function(){var e="position:",t="sticky",n=s("a"),o=n.style;return o.cssText=e+S.join(t+";"+e).slice(0,-e.length),-1!==o.position.indexOf(t)}),Modernizr.addTest("cssremunit",function(){var e=s("a").style;try{e.fontSize="3rem"}catch(t){}return/rem/.test(e.fontSize)}),Modernizr.addTest("rgba",function(){var e=s("a").style;return e.cssText="background-color:rgba(150,255,150,.5)",(""+e.backgroundColor).indexOf("rgba")>-1}),Modernizr.addAsyncTest(function(){var n,o,r=function(e){C.contains(e)||C.appendChild(e)},i=function(e){e.fake&&e.parentNode&&e.parentNode.removeChild(e)},d=function(e,t){var n=!!e;if(n&&(n=new Boolean(n),n.blocked="blocked"===e),a("flash",function(){return n}),t&&A.contains(t)){for(;t.parentNode!==A;)t=t.parentNode;A.removeChild(t)}};try{o="ActiveXObject"in e&&"Pan"in new e.ActiveXObject("ShockwaveFlash.ShockwaveFlash")}catch(l){}if(n=!("plugins"in navigator&&"Shockwave Flash"in navigator.plugins||o),n||R)d(!1);else{var u,p,f=s("embed"),A=c();if(f.type="application/x-shockwave-flash",A.appendChild(f),!("Pan"in f||o))return r(A),d("blocked",f),void i(A);u=function(){return r(A),C.contains(A)?(C.contains(f)?(p=f.style.cssText,""!==p?d("blocked",f):d(!0,f)):d("blocked"),void i(A)):(A=t.body||A,f=s("embed"),f.type="application/x-shockwave-flash",A.appendChild(f),setTimeout(u,1e3))},setTimeout(u,10)}});var U=function(){var t=e.matchMedia||e.msMatchMedia;return t?function(e){var n=t(e);return n&&n.matches||!1}:function(t){var n=!1;return u("@media "+t+" { #modernizr { position: absolute; } }",function(t){n="absolute"==(e.getComputedStyle?e.getComputedStyle(t,null):t.currentStyle).position}),n}}();x.mq=U;var j=x.testStyles=u;Modernizr.addTest("touchevents",function(){var n;if("ontouchstart"in e||e.DocumentTouch&&t instanceof DocumentTouch)n=!0;else{var o=["@media (",S.join("touch-enabled),("),"heartz",")","{#modernizr{top:9px;position:absolute}}"].join("");j(o,function(e){n=9===e.offsetTop})}return n}),Modernizr.addTest("checked",function(){return j("#modernizr {position:absolute} #modernizr input {margin-left:10px} #modernizr :checked {margin-left:20px;display:block}",function(e){var t=s("input");return t.setAttribute("type","checkbox"),t.setAttribute("checked","checked"),e.appendChild(t),20===t.offsetLeft})}),j("#modernizr{display: table; direction: ltr}#modernizr div{display: table-cell; padding: 10px}",function(e){var t,n=e.childNodes;t=n[0].offsetLeft<n[1].offsetLeft,Modernizr.addTest("displaytable",t,{aliases:["display-table"]})},2),j('#modernizr{font:0/0 a}#modernizr:after{content:":)";visibility:hidden;font:7px/1 a}',function(e){Modernizr.addTest("generatedcontent",e.offsetHeight>=7)});var D=function(){var e=navigator.userAgent,t=e.match(/applewebkit\/([0-9]+)/gi)&&parseFloat(RegExp.$1),n=e.match(/w(eb)?osbrowser/gi),o=e.match(/windows phone/gi)&&e.match(/iemobile\/([0-9])+/gi)&&parseFloat(RegExp.$1)>=9,r=533>t&&e.match(/android/gi);return n||r||o}();D?Modernizr.addTest("fontface",!1):j('@font-face {font-family:"font";src:url("https://")}',function(e,n){var o=t.getElementById("smodernizr"),r=o.sheet||o.styleSheet,i=r?r.cssRules&&r.cssRules[0]?r.cssRules[0].cssText:r.cssText||"":"",a=/src/i.test(i)&&0===i.indexOf(n.split(" ")[0]);Modernizr.addTest("fontface",a)}),j("#modernizr{position: absolute; top: -10em; visibility:hidden; font: normal 10px arial;}#subpixel{float: left; font-size: 33.3333%;}",function(t){var n=t.firstChild;n.innerHTML="This is a text written in Arial",Modernizr.addTest("subpixelfont",e.getComputedStyle?"44px"!==e.getComputedStyle(n,null).getPropertyValue("width"):!1)},1,["subpixel"]),Modernizr.addTest("formvalidation",function(){var t=s("form");if(!("checkValidity"in t&&"addEventListener"in t))return!1;if("reportValidity"in t)return!0;var n,o=!1;return Modernizr.formvalidationapi=!0,t.addEventListener("submit",function(t){(!e.opera||e.operamini)&&t.preventDefault(),t.stopPropagation()},!1),t.innerHTML='<input name="modTest" required="required" /><button></button>',j("#modernizr form{position:absolute;top:-99999em}",function(e){e.appendChild(t),n=t.getElementsByTagName("input")[0],n.addEventListener("invalid",function(e){o=!0,e.preventDefault(),e.stopPropagation()},!1),Modernizr.formvalidationmessage=!!n.validationMessage,t.getElementsByTagName("button")[0].click()}),o});var N={elem:s("modernizr")};Modernizr._q.push(function(){delete N.elem}),Modernizr.addTest("cssexunit",function(){var e,t=N.elem.style;try{t.fontSize="3ex",e=-1!==t.fontSize.indexOf("ex")}catch(n){e=!1}return e}),Modernizr.addTest("hsla",function(){var e=s("a").style;return e.cssText="background-color:hsla(120,40%,100%,.5)",p(e.backgroundColor,"rgba")||p(e.backgroundColor,"hsla")});var O=function(e){if("undefined"==typeof XMLHttpRequest)return!1;var t=new XMLHttpRequest;t.open("get","/",!0);try{t.responseType=e}catch(n){return!1}return"response"in t&&t.responseType==e};Modernizr.addTest("xhrresponsetypeblob",O("blob")),Modernizr.addTest("xhrresponsetypedocument",O("document")),Modernizr.addTest("xhrresponsetypejson",O("json")),Modernizr.addTest("xhrresponsetypetext",O("text"));var M={style:N.elem.style};Modernizr._q.unshift(function(){delete M.style});var J=x.testProp=function(e,t,o){return A([e],n,t,o)};Modernizr.addTest("textshadow",J("textShadow","1px 1px")),x.testAllProps=g;var q=x.prefixed=function(e,t,n){return 0===e.indexOf("@")?P(e):(-1!=e.indexOf("-")&&(e=d(e)),t?g(e,t,n):g(e,"pfx"))};x.prefixedCSS=function(e){var t=q(e);return t&&l(t)};Modernizr.addTest("fullscreen",!(!q("exitFullscreen",t,!1)&&!q("cancelFullScreen",t,!1))),Modernizr.addTest("filesystem",!!q("requestFileSystem",e)),x.testAllProps=h,Modernizr.addTest("bgpositionxy",function(){return h("backgroundPositionX","3px",!0)&&h("backgroundPositionY","5px",!0)}),Modernizr.addTest("bgrepeatround",h("backgroundRepeat","round")),Modernizr.addTest("bgrepeatspace",h("backgroundRepeat","space")),Modernizr.addTest("backgroundsize",h("backgroundSize","100%",!0)),Modernizr.addTest("bgsizecover",h("backgroundSize","cover")),Modernizr.addTest("borderimage",h("borderImage","url() 1",!0)),Modernizr.addTest("borderradius",h("borderRadius","0px",!0)),Modernizr.addTest("boxshadow",h("boxShadow","1px 1px",!0)),Modernizr.addTest("boxsizing",h("boxSizing","border-box",!0)&&(t.documentMode===n||t.documentMode>7)),function(){Modernizr.addTest("csscolumns",function(){var e=!1,t=h("columnCount");try{(e=!!t)&&(e=new Boolean(e))}catch(n){}return e});for(var e,t,n=["Width","Span","Fill","Gap","Rule","RuleColor","RuleStyle","RuleWidth","BreakBefore","BreakAfter","BreakInside"],o=0;o<n.length;o++)e=n[o].toLowerCase(),t=h("column"+n[o]),("breakbefore"===e||"breakafter"===e||"breakinside"==e)&&(t=t||h(n[o])),Modernizr.addTest("csscolumns."+e,t)}(),Modernizr.addTest("ellipsis",h("textOverflow","ellipsis")),Modernizr.addTest("cssfilters",function(){if(Modernizr.supports)return h("filter","blur(2px)");var e=s("a");return e.style.cssText=S.join("filter:blur(2px); "),!!e.style.length&&(t.documentMode===n||t.documentMode>9)}),Modernizr.addTest("flexbox",h("flexBasis","1px",!0)),Modernizr.addTest("flexboxtweener",h("flexAlign","end",!0)),Modernizr.addTest("flexwrap",h("flexWrap","wrap",!0)),Modernizr.addTest("cssmask",h("maskRepeat","repeat-x",!0)),Modernizr.addTest("cssreflections",h("boxReflect","above",!0)),Modernizr.addTest("cssresize",h("resize","both",!0)),Modernizr.addTest("textalignlast",h("textAlignLast")),Modernizr.addTest("csstransforms",function(){return-1===navigator.userAgent.indexOf("Android 2.")&&h("transform","scale(1)",!0)}),Modernizr.addTest("csstransforms3d",function(){var e=!!h("perspective","1px",!0),t=Modernizr._config.usePrefixes;if(e&&(!t||"webkitPerspective"in C.style)){var n,o="#modernizr{width:0;height:0}";Modernizr.supports?n="@supports (perspective: 1px)":(n="@media (transform-3d)",t&&(n+=",(-webkit-transform-3d)")),n+="{#modernizr{width:7px;height:18px;margin:0;padding:0;border:0}}",j(o+n,function(t){e=7===t.offsetWidth&&18===t.offsetHeight})}return e}),Modernizr.addTest("csstransitions",h("transition","all",!0)),Modernizr.addTest("userselect",h("userSelect","none",!0)),Modernizr.addTest("placeholder","placeholder"in s("input")&&"placeholder"in s("textarea")),r(),i(b),delete x.addTest,delete x.addAsyncTest;for(var G=0;G<Modernizr._q.length;G++)Modernizr._q[G]();e.Modernizr=Modernizr}(window,document);