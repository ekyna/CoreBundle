/// <reference path="../../../../../../typings/index.d.ts" />

import * as jQuery from 'jquery';

(function ($) {

    let template = `
<div class="ui-loading-container">
    <div class="ui-loading-spinner">
        <div class="circle circle-1"></div>
        <div class="circle circle-2"></div>
        <div class="circle circle-3"></div>
        <div class="circle circle-4"></div>
        <div class="circle circle-5"></div>
        <div class="circle circle-6"></div>
        <div class="circle circle-7"></div>
        <div class="circle circle-8"></div>
        <div class="circle circle-9"></div>
        <div class="circle circle-10"></div>
        <div class="circle circle-11"></div>
        <div class="circle circle-12"></div>
    </div>
</div>`;

    $.fn.loadingSpinner = function (action?:string) {

        let run:Function;

        if (action == 'off') {
            run = function($element:JQuery) {
                $element
                    .removeClass('ui-loading')
                    .find('.ui-loading-container')
                    .remove();
            };
        } else if (action == 'on' || action == undefined) {
            run = function($element:JQuery) {
                if ('static' === <string>$element.css('position')) {
                    $element.css('position', 'relative');
                }

                $element.addClass('ui-loading');

                let $loader:JQuery = $element.find('.ui-loading-container');
                if (0 == $loader.length) {
                    $element.append($(template));
                }
            };
        }

        return this.each(function () {
            run($(this));
        });
    };

})(jQuery);
