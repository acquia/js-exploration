(function ($, Drupal) {

  'use strict';

  var actionsSelector = '[data-drupal-selector="edit-actions"]';
  var previewPlaceholderSelector = '#comment-ajax-preview-placeholder';

  /**
   * Theme function to render the "editing status".
   *
   * @param {string} status
   *   The (translated) status.
   *
   * @return {string}
   *   The corresponding HTML.
   */
  Drupal.theme.commentAjaxEditingStatus = function (status) {
    return '<div class="comment-editing-status">' + status + '</div>';
  };

  Drupal.behaviors.commentAjaxForm = {
    attach: function (context) {
      var $commentForm = $(context).find('[data-drupal-selector="comment-form"]');
      var $previewButton = $commentForm.find('[data-drupal-selector="edit-preview"]');
      var $editButton = $commentForm.find('[data-drupal-selector="edit-comment-ajax-edit"]');
      var $saveButton = $commentForm.find('[data-drupal-selector="edit-submit"]');
      var $commentFormItems = $commentForm.children().not(actionsSelector).not(previewPlaceholderSelector);

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
        $commentForm.find(actionsSelector)
          .prepend(Drupal.theme('commentAjaxEditingStatus', Drupal.t('Previewing')));
        $previewButton.hide();
        $editButton.show();
        // @todo When none of the comment form's input fields have been modified
        // by the user since the last time it was previewed, there's no need to
        // send the AJAX request that will render the preview; we can reuse it
        // immediately.
      }

      // When the "Edit" button is triggered (which can only happen after the
      // "Preview" button was triggered):
      // 1. Revert everything startPreview() did.
      // 2. Also remove the preview.
      // 3. Prevent the default action: we already have the form available.
      function endPreview() {
        // Step 1.
        $commentFormItems.show();
        $commentForm.find('.comment-editing-status').remove();
        $editButton.hide();
        $previewButton.show();
        // Steps 2-3.
        $(previewPlaceholderSelector).empty();
        event.preventDefault();
      }

      // When the "Save" button is triggered, change the button label.
      // Note the lack of `preventDefault()`: we let the AJAX system continue to
      // do its thing, which means the preview will be inserted in the preview
      // placeholder.
      function startSave() {
        $saveButton.attr('value', Drupal.t('Saving…'));
      }

      // Bind event handlers.
      $previewButton.on('mousedown', startPreview);
      $editButton.on('mousedown', endPreview);
      $saveButton.on('mousedown', startSave);
    }
  };

  Drupal.AjaxCommands.prototype.commentAjaxUpdateUrl = function (ajax, response, status) {
    window.history.replaceState({}, '', response.data);
  };

})(jQuery, Drupal);
