if (!window.jQuery.remoteMethod) {
    jQuery.remoteMethod = function(url, action, param) {
        param = typeof(param) == "string" ? "action=" + action + "&" + param : jQuery.extend({"action":action}, param);
        jQuery.ajax({
            type:"POST",
            url:url,
            dataType:"script",
            data:param
        });
    };
}
