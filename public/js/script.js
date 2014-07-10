jQuery(document).ready(function($) {

    setThumbnailSelectState();
    initSelect2();

    $(document).on('click', '.thumbnail-switch-action', function() {
        if($(this).is(':checked')) {
            $(this).parentsUntil('.thumbnail').find('.thumbnail_dropdown_wrapper').show();
        } else {
            $(this).parentsUntil('.thumbnail').find('.thumbnail_dropdown_wrapper').hide();
        }
    });

    $(document).ajaxSuccess(function(e, xhr, settings) {
        var settingsDataParts = decodeURI(settings.data).split('&'),
            settingsData = [];

        // Clean up settings.data
        $.each(settingsDataParts, function(index, dataPart) {
            var keyAndValue = dataPart.split('=');
            settingsData[keyAndValue[0]] = keyAndValue[1];
        });

        var widget_id_base = 'simple_post_preview';
        if (settingsData['action'] == 'save-widget' && settingsData['id_base'] == widget_id_base) {
            var widget = $('div.widget[id$="' + settingsData['widget-id'] + '"]');

            // If just added, force a save to set the widget id.
            // Without it's hard to get select2 to work.
            if (settingsData['add_new'] == 'multi') {
                widget.find('input[name=savewidget]').trigger('click');
            }

            initSelect2('#' + widget.attr('id'));
        }

        setThumbnailSelectState();
    });

    /**
     *
     */
    function initSelect2(selector)
    {
        var selector = (selector != undefined) ? selector : '.widget .simple-post-preview';
        selector += ' .select-select2';

        console.log(selector);

        $(selector).select2('destroy');

        $(selector).select2({
            placeholder: 'Search for posts',
            minimumInputLength: 2,
            ajax: {
                url: ajaxurl,
                dataType: 'json',
                data: function (searchString) {
                    return data = {
                        'action': 'spp_search_posts',
                        'search': searchString
                    };
                },
                results: function (data, page) {
                    return {results: data};
                }
            },
            initSelection: function(element, callback) {
                var data = {
                    'action': 'spp_search_posts',
                    'selected': $(element).val()
                };

                $.ajax({
                    url: ajaxurl,
                    data: data,
                    dataType: 'json',
                    success: function(data) {
                        callback(data);
                    }
                });
            }
        });
    }

    /**
     * Hide or show thumbnail select box on init or ajax call.
     */
    function setThumbnailSelectState()
    {
        $('.thumbnail-switch-action').each(function(key, object) {
            if($(object).is(':checked')) {
                $(object).parentsUntil('.thumbnail').find('.thumbnail_dropdown_wrapper').show();
            } else {
                $(object).parentsUntil('.thumbnail').find('.thumbnail_dropdown_wrapper').hide();
            }
        });
    }
});