define(['require', 'jquery', 'aos', 'bootstrap', 'ekyna-clipboard-copy'], function(require, $, AOS) {

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
    };

    return new EkynaCore;
});
