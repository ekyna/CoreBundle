define(['require', 'jquery', 'aos', 'bootstrap'], function(require, $, AOS) {

    var EkynaCore = function() {};

    EkynaCore.prototype.init = function(aos) {

        AOS.init($.extend({}, aos, {
            offset: 200
        }));

        // Forms
        var $forms = $('form');
        if (0 < $forms.length) {
            require(['ekyna-form'], function(Form) {
                $forms.each(function(i, f) {
                    var form = Form.create(f);
                    form.init();
                });
            });
        }

        // Toggle details
        $(document).on('click', 'a[data-toggle-details]', function(e) {
            e.preventDefault();

            var $this = $(this), $target = $('#' + $this.data('toggle-details'));

            if (1 === $target.size()) {
                if ($target.is(':visible')) {
                    $target.hide();
                } else {
                    $target.show();
                }
            }

            return false;
        });


        $(document).on('click', '[data-clipboard-copy]', function (e) {
            if (typeof window['ontouchstart'] !== 'undefined') {
                return true;
            }

            e.preventDefault();
            e.stopPropagation();

            var element = e.currentTarget;
            element.addEventListener('copy', function (event) {
                event.preventDefault();
                if (event.clipboardData) {
                    event.clipboardData.setData("text/plain", $(element).data('clipboard-copy'));

                    $(element)
                        .tooltip({
                            title: 'Copied to clipboard',
                            placement: 'auto',
                            trigger: 'manual',
                            container: 'body'
                        })
                        .tooltip('show');

                    setTimeout(function () {
                        $(element).tooltip('hide');
                    }, 1500);
                }
            });

            document.execCommand("Copy");

            return false;
        });
    };

    return new EkynaCore;
});