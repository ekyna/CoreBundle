define(['jquery', 'router', 'json!routes'],
    function ($, Router, routes) {
        routes.prefix = ($('html').attr('lang') || 'en') + '__RG__';
        Router.setRoutingData(routes);
        return Router;
    }
);
