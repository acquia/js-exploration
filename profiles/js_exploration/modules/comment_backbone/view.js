(function ($, Drupal) {

  'use strict';

  function getComment(commentID) {
    jQuery.ajax({
      url: Drupal.url('comment/' + commentID) + '?_format=json',
      method: 'GET',
      headers: {
        "Content-Type": "application/json"
      },
      success: function(data, status, xhr) {
        console.log(data);
      }
    });
  }

  Drupal.behaviors.commentBackbonePoC = {
    attach: function (context) {
      var $context = $(context);

      // Find all comments, if any.
      var $comments = $context.find('[data-comment-backbone]').once('contextual-render');
      if ($comments.length === 0) {
        return;
      }

      // Collect the IDs for all comments.
      var ids = [];
      $comments.each(function () {
        ids.push($(this).attr('data-comment-backbone'));
      });

      for (var i = 0; i < ids.length; i++) {
        getComment(ids[i]);
      }
    }
  }

})(jQuery, Drupal);
