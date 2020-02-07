define(['jquery', 'bootstrap'], function ($) {
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
});
