define(["jquery","routing"],function(a,b){"use strict";var c=function(b){this.elem=b,this.$elem=a(b),this.$parent=null,this.metadata=this.$elem.data("parent-choice")};return c.prototype={defaults:{},init:function(){if(this.config=a.extend({field:null,route:null,parameter:"id"},this.defaults,this.metadata),this.config.field&&this.config.route){this.$parent=a("select#"+this.config.field);var b=this;if(this.$parent.length>0){this.$parent.bind("change",function(){b.updateChoices()});var c=parseInt(this.$elem.val());c||this.$parent.trigger("change")}}return this},updateChoices:function(){var c=this.$elem;if(!this.$parent.prop("disabled")){var d=parseInt(this.$parent.val());if(d){var e=c.find("option").eq(0);c.empty().append(e).prop("disabled",!0);var f={};f[this.config.parameter]=d;var g=a.get(b.generate(this.config.route,f));g.done(function(b){"undefined"!=typeof b.choices&&a(b.choices).length>0&&(a(b.choices).each(function(b,d){var e=a("<option />");e.attr("value",d.value).text(d.text),c.append(e)}),c.prop("disabled",!1)),c.trigger("form_choices_loaded",b)})}}}},c.defaults=c.prototype.defaults,a.fn.formChoiceParentSelectorWidget=function(a){return this.each(function(){new c(this,a).init()})},{init:function(a){a.each(function(){new c(this).init()})}}});