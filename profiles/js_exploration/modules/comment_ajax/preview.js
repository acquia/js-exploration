(function ($, Drupal) {

  'use strict';

  var actionsSelector = '[data-drupal-selector="edit-actions"]';

  Drupal.behaviors.commentAjaxPreview = {
    attach: function (context) {
      var $commentForm = $(context).find('[data-drupal-selector="comment-form"]');
      var $previewButton = $commentForm.find('[data-drupal-selector="edit-preview"]');


      $previewButton.on('mousedown', function () {
        // Hide everything in the form except for the actions and the preview
        // placeholder.
        $commentForm.children().not(actionsSelector).not('#comment-ajax-preview-placeholder').hide();
      });
    }
  };

})(jQuery, Drupal);
