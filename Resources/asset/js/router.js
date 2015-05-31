$(function() {
    var url = $('html').attr('data-fos-routes') || "/js/routing.json";
    $.ajax({
        url: url,
        dataType: "json"
    })
    .done(function(data) {
        fos.Router.setData(data);
        $(document).trigger('fos_js_routing_loaded');
    })
    .fail(function() {
        console.error("Failed to load fos js routes.");
    });
});