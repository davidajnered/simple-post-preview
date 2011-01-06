/*
 * Implement javascript and ajax stuff
 */
jQuery('document').ready(function() {

  initCheckbox();

  jQuery('#widget-simple_post_preview-3-thumbnail').live('click', function() {
    if(jQuery(this).is(':checked')) {
      jQuery('#widget-simple_post_preview-3-thumbnail_size').parent('p').show();
    } else {
      jQuery('#widget-simple_post_preview-3-thumbnail_size').parent('p').hide();
    }
  });

});

function initCheckbox() {
  if(jQuery(this).is(':checked')) {
    jQuery('#widget-simple_post_preview-3-thumbnail_size').parent('p').show();
  } else {
    jQuery('#widget-simple_post_preview-3-thumbnail_size').parent('p').hide();
  }
}