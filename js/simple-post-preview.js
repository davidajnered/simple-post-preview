/*
 * Implement javascript and ajax stuff
 */
jQuery(document).ready(function() {

  initCheckbox();

  jQuery('#widget-simple_post_preview-3-thumbnail').live('click', function() {
    if(jQuery(this).is(':checked')) {
      jQuery('#widget-simple_post_preview-3-thumbnail_size').parent('p').show();
    } else {
      jQuery('#widget-simple_post_preview-3-thumbnail_size').parent('p').hide();
    }
  });

});

jQuery(document).ajaxSuccess(function() {
  initCheckbox();
});

function initCheckbox() {
  if(jQuery('#widget-simple_post_preview-3-thumbnail').is(':checked')) {
    jQuery('#widget-simple_post_preview-3-thumbnail_size').parent('p').show();
  } else {
    jQuery('#widget-simple_post_preview-3-thumbnail_size').parent('p').hide();
  }
}