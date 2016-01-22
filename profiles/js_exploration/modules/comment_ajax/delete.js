(function ($, Drupal) {

  'use strict';

  var commentSelector = '[data-comment-ajax]';
  var deleteLinkSelector = '.comment-delete';
  var currentDeleteLink = null;

  var deleteDialog = Drupal.dialog('<div>' + Drupal.t('Are you sure you want to delete the comment?') + '</div>', {
    dialogClass: 'comment-ajax-delete-modal',
    resizable: false,
    buttons: [
      {
        text: Drupal.t('Delete'),
        click: function () {
          // Immediate feedback: immediately hide the comment.
          var $comment = $(currentDeleteLink).closest(commentSelector);
          var commentId = $comment.attr('data-comment-ajax');
          Drupal.detachBehaviors($comment.get(0));
          $comment.hide();

          // Immediate feedback: close modal dialog.
          deleteDialog.close();

          // Now actually delete the comment.
          var deleteComment = Drupal.ajax({
            url: Drupal.url('comment_ajax/delete/' + commentId),
            success: function () {
              // Deletion succeeded on the server, also remove here.
              $comment.remove();
            },
            error: function (xmlhttprequest, uri) {
              // Deletion failed on the server, restore comment.
              $comment.show();
              throw new Drupal.AjaxError(xmlhttprequest, uri, Drupal.t('Could not delete comment. Please try again.'));
            }
          });
          deleteComment.execute();
        },
        primary: true
      }
    ]
  });

  Drupal.behaviors.commentAjaxDelete = {
    attach: function (context) {
      var $commentLinks = $(context)
        .find(commentSelector)
        .find(deleteLinkSelector)
        .find('a')
        .once('comment-ajax-delete');

      $commentLinks.on('click', function (event) {
        // The last comment whose "delete" link was clicked, is always the
        // current one: the one that the dialog should delete.
        currentDeleteLink = event.target;
        deleteDialog.showModal();
        event.preventDefault();
      });
    }
  };

})(jQuery, Drupal);
