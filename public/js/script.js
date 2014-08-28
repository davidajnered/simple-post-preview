jQuery(document).ready(function($) {

    // Run some init functions
    setThumbnailSelectState();
    initSelect2();

    /**
     * Hide and show thumbnail select.
     */
    $(document).on('click', '.thumbnail-switch-action', function() {
        if($(this).is(':checked')) {
            $(this).parentsUntil('.thumbnail').find('.thumbnail_dropdown_wrapper').show();
        } else {
            $(this).parentsUntil('.thumbnail').find('.thumbnail_dropdown_wrapper').hide();
        }
    });

    /**
     * Some logic for re-initializing select2 when widget is added and saved.
     */
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
     * Init select2.
     */
    function initSelect2(selector)
    {
        var selector = (selector != undefined) ? selector : '.widget .simple-post-preview';
        selector += ' .select-select2';

        $(selector).select2('destroy');

        $(selector).select2({
            placeholder: 'Search for posts',
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
                var selectedId = $(element).val().split(':').pop();

                if(!selectedId) {
                    return;
                }

                var ajaxData = {
                    'action': 'spp_search_posts',
                    'selected': selectedId
                };

                $.ajax({
                    url: ajaxurl,
                    data: ajaxData,
                    dataType: 'json',
                    success: function(data) {
                        callback(data[0]);
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