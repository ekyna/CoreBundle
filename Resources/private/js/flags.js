define([], function () {
    const ID = 'core-flags-stylesheet';

    return {
        load: function () {
            if (document.getElementById(ID)) {
                return;
            }

            var stylesheet = document.createElement('link');
            stylesheet.id = ID;
            stylesheet.href = document.documentElement.getAttribute('data-asset-base-url') + '/bundles/ekynacore/css/flags.css';
            stylesheet.media = 'screen';
            stylesheet.rel = 'stylesheet';
            stylesheet.type = 'text/css';
            document.head.appendChild(stylesheet);
        },
    }
});
