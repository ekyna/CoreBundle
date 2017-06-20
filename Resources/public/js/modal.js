define(["require","jquery","bootstrap/dialog"],function(a,b,c){"use strict";function d(a){var b="html",c=a.getResponseHeader("content-type");return/json/.test(c)?b="json":/xml/.test(c)&&(b="xml"),b}var e=function(){this.dialog=new c,this.form=null,this.shown=!1;var a=this;a.dialog.onShow(function(){var c=b.Event("ekyna.modal.show");c.modal=a,b(a).trigger(c)}),a.dialog.onShown(function(){a.shown=!0;var c=b.Event("ekyna.modal.shown");c.modal=a,b(a).trigger(c)}),a.dialog.onHide(function(){a.shown=!1;var c=b.Event("ekyna.modal.hide");return c.modal=a,b(a).trigger(c),!c.isDefaultPrevented()&&void(a.form&&(a.form.destroy(),a.form=null))}),a.dialog.onHidden(function(){var c=b.Event("ekyna.modal.hidden");c.modal=a,b(a).trigger(c)})};return e.prototype={constructor:e,load:function(a){a.cache=!1;var c=this,d=b.ajax(a);return d.done(function(a,b,d){c.handleResponse(a,b,d)}),d.fail(function(){console.log("Failed to load modal.");var a=b.Event("ekyna.modal.load_fail");b(c).trigger(a)}),d},initForm:function(c){var d=this;b(d.dialog.getModal()).removeAttr("tabindex"),a(["ekyna-form"],function(a){d.form=a.create(c),d.form.init(d.dialog.getModal()),d.form.getElement().on("submit",function(a){a.preventDefault(),d.dialog.enableButtons(!1);var b=d.dialog.getButton("submit");return b&&b.spin(),d.form.save(),setTimeout(function(){d.form.getElement().ajaxSubmit({success:function(a,b,c){d.handleResponse(a,b,c)}})},100),!1})})},handleResponse:function(a,c,e){var f,g=this,h=d(e);if(g.form&&(g.form.destroy(),g.form=null),f=b.Event("ekyna.modal.response"),f.modal=g,f.contentType=h,f.content=a,b(g).trigger(f),f.isDefaultPrevented())return g.close();if("xml"!==h)return this;var i=b(a),j=i.find("content");if(!(j.size()>0))return this;h=j.attr("type"),f=b.Event("ekyna.modal.content"),f.modal=g,f.contentType=h;var k=j.text();if("data"===h)return f.content=JSON.parse(k),b(g).trigger(f),g.close();var l=b(k);if(f.content=l,b(g).trigger(f),f.isDefaultPrevented())return g.close();g.dialog.setMessage(l),"form"===h&&(g.shown?g.initForm(l):b(g).one("ekyna.modal.shown",function(){g.initForm(l)}));var m=i.find("title");m.size()>0&&g.dialog.setTitle(m.text());var n=JSON.parse(i.find("config").text());n.type&&g.dialog.setType(n.type),n.size&&g.dialog.setSize(n.size);var o=i.find("buttons");if(o.size()>0){var p=JSON.parse(o.text(),function(a,b){return b&&"string"==typeof b&&0===b.indexOf("function")?new Function("return "+b)():b});b(p).each(function(a,c){"function"!=typeof c.action&&("close"==c.id?c.action=function(a){a.enableButtons(!1),a.close()}:c.action=function(a){a.enableButtons(!1);var d=b.Event("ekyna.modal.button_click");d.modal=g,d.buttonId=c.id,b(g).trigger(d),g.form&&"submit"==c.id&&!d.isDefaultPrevented()&&g.form.getElement().submit()})}),g.dialog.setButtons(p)}else g.dialog.setButtons([]);return g.dialog.isOpened()||g.dialog.open(),this},close:function(){return this.dialog.isOpened()&&this.dialog.close(),this},getDialog:function(){return this.dialog}},b(document).on("click",'button[data-modal="true"], a[data-modal="true"], [data-modal="true"] > a',function(a){a.preventDefault();var c=new e;return c.load({url:b(this).attr("href")}),!1}),e});