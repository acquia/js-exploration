(function ($, Drupal) {

  'use strict';

  var actionsSelector = '[data-drupal-selector="edit-actions"]';
  var previewPlaceholderSelector = '#comment-ajax-preview-placeholder';

  Drupal.behaviors.commentAjaxPreview = {
    attach: function (context) {
      var $commentForm = $(context).find('[data-drupal-selector="comment-form"]');
      var $previewButton = $commentForm.find('[data-drupal-selector="edit-preview"]');
      var $editButton = $commentForm.find('[data-drupal-selector="edit-comment-ajax-edit"]');
      var $commentFormItems = $commentForm.children().not(actionsSelector).not(previewPlaceholderSelector);
      var statusText = Drupal.t('Previewing');

      // Hide the "Edit" button initially.
      $editButton.hide();

      // When the "Preview" button is triggered:
      // 1. hide all comment form items
      // 2. show 'Previewing' status text
      // 3. hide 'Preview' button (since we already are previewing)
      // 4. show 'Edit' button to switch back to the form
      // Note the lack of `preventDefault()`: we let the AJAX system continue to
      // do its thing, which means the preview will be inserted in the preview
      // placeholder.
      function startPreview() {
        $commentFormItems.hide();
        $commentForm.find(actionsSelector).prepend('<div id="comment-ajax-status" style="display: inline-block; margin-right: 1em;">' + statusText + '</div>');
        $previewButton.hide();
        $editButton.show();
      }

      // When the "Edit" button is triggered (which can only happen after the
      // "Preview" button was triggered):
      // 1. Revert everything startPreview() did.
      // 2. Also remove the preview.
      // 3. Prevent the default action: we already have the form available.
      function endPreview () {
        // Step 1.
        $commentFormItems.show();
        $commentForm.find('#comment-ajax-status').remove();
        $editButton.hide();
        $previewButton.show();
        // Steps 2-3.
        $(previewPlaceholderSelector).empty();
        event.preventDefault();
      }

      // Bind event handlers.
      $previewButton.on('mousedown', startPreview);
      $editButton.on('mousedown', endPreview);
    }
  };

})(jQuery, Drupal);
