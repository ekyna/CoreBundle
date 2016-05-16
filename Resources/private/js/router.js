define(['jquery', 'routing', 'json!routing_data'],
    function ($, router, data) {
        data.prefix = ($('html').attr('lang') || 'en') + '__RG__';
        fos.Router.setData(data);
        return router;
    }
);
