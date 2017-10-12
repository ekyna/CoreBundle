/*!
 * VERSION: 0.0.2
 * DATE: 2017-01-02
 * UPDATES AND DOCS AT: http://greensock.com
 *
 * @license Copyright (c) 2008-2017, GreenSock. All rights reserved.
 * This work is subject to the terms at http://greensock.com/standard-license or for
 * Club GreenSock members, the software agreement that was issued with your membership.
 * 
 * @author: Jack Doyle, jack@greensock.com
 */
var _gsScope="undefined"!=typeof module&&module.exports&&"undefined"!=typeof global?global:this||window;(_gsScope._gsQueue||(_gsScope._gsQueue=[])).push(function(){"use strict";var a=function(a,b,c){var d=a.type,e=a.setRatio,f=b._tween,g=b._target;a.type=2,a.m=c,a.setRatio=function(b){var h,i,j,k=1e-6;if(1!==b||f._time!==f._duration&&0!==f._time)if(b||f._time!==f._duration&&0!==f._time||f._rawPrevTime===-1e-6)if(h=a.c*b+a.s,a.r?h=Math.round(h):k>h&&h>-k&&(h=0),d)if(1===d){for(i=a.xs0+h+a.xs1,j=1;j<a.l;j++)i+=a["xn"+j]+a["xs"+(j+1)];a.t[a.p]=c(i,g)}else-1===d?a.t[a.p]=c(a.xs0,g):e&&e.call(a,b);else a.t[a.p]=c(h+a.xs0,g);else 2!==d?a.t[a.p]=c(a.b,g):e.call(a,b);else if(2!==d)if(a.r&&-1!==d)if(h=Math.round(a.s+a.c),d){if(1===d){for(i=a.xs0+h+a.xs1,j=1;j<a.l;j++)i+=a["xn"+j]+a["xs"+(j+1)];a.t[a.p]=c(i,g)}}else a.t[a.p]=c(h+a.xs0,g);else a.t[a.p]=c(a.e,g);else e.call(a,b)}},b=function(b,c){for(var d=c._firstPT,e=b.rotation&&-1!==c._overwriteProps.join("").indexOf("bezier");d;)"function"==typeof b[d.p]?a(d,c,b[d.p]):e&&"bezier"===d.n&&-1!==d.plugin._overwriteProps.join("").indexOf("rotation")&&(d.data.mod=b.rotation),d=d._next},c=_gsScope._gsDefine.plugin({propName:"modifiers",version:"0.0.2",API:2,init:function(a,b,c){return this._tween=c,this._vars=b,!0},initAll:function(){for(var a,c,d=this._tween,e=this._vars,f=this,g=d._firstPT;g;)c=g._next,a=e[g.n],g.pg?"css"===g.t._propName?b(e,g.t):g.t!==f&&(a=e[g.t._propName],g.t._mod("object"==typeof a?a:e)):"function"==typeof a&&(2===g.f&&g.t?g.t._applyPT.m=a:(this._add(g.t,g.p,g.s,g.c,a),c&&(c._prev=g._prev),g._prev?g._prev._next=c:d._firstPT===g&&(d._firstPT=c),g._next=g._prev=null,d._propLookup[g.n]=f)),g=c;return!1}}),d=c.prototype;d._add=function(a,b,c,d,e){this._addTween(a,b,c,c+d,b,e),this._overwriteProps.push(b)},d=_gsScope._gsDefine.globals.TweenLite.version.split("."),Number(d[0])<=1&&Number(d[1])<19&&_gsScope.console&&console.log("ModifiersPlugin requires GSAP 1.19.0 or later.")}),_gsScope._gsDefine&&_gsScope._gsQueue.pop()(),function(a){"use strict";var b=function(){return(_gsScope.GreenSockGlobals||_gsScope)[a]};"function"==typeof define&&define.amd?define(["TweenLite"],b):"undefined"!=typeof module&&module.exports&&(require("../TweenLite.min.js"),module.exports=b())}("ModifiersPlugin");