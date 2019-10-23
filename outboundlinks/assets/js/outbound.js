/**
 * A jQuery document ready block
 * @param {type} param
 */
jQuery(document).ready(function () {

    //Adding Class to the Outbound Links
    jQuery("a[rel='outbound']").addClass('external-link');

    //Adding Outbound tags to the external Links
    jQuery("a.external-link").each(function () {
        var original_url = (jQuery(this).attr("href"));
        jQuery(this).attr("href", original_url + "?rel=outbound")
    });

});