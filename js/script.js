jQuery(document).ready(function($) {

    $('.chosen-select').chosen({width: '100%'});

    initCheckbox();

    $('#thumbnail-action').live('click', function() {
        if($(this).is(':checked')) {
            $(this).parentsUntil('.spp-thumbnail').find('.spp_thumbnail_dropdown_wrapper').show();
        } else {
            $(this).parentsUntil('.spp-thumbnail').find('.spp_thumbnail_dropdown_wrapper').hide();
        }
    });

    $(document).ajaxSuccess(function() {
        initCheckbox();
    });

    function initCheckbox() {
        $('#thumbnail-action').each(function(key, object) {
            if($(object).is(':checked')) {
                $(object).parentsUntil('.spp-thumbnail').find('.spp_thumbnail_dropdown_wrapper').show();
            } else {
                $(object).parentsUntil('.spp-thumbnail').find('.spp_thumbnail_dropdown_wrapper').hide();
            }
        });
    }
});