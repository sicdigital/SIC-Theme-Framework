(function($){
$(function() {

    $('#page_template').change(function() {
        $('#page_slider_settings').toggle($(this).val() == 'default');
    }).change();

});
})(jQuery);