(function (jQuery, Drupal) {

  'use strict';

  var contentType = 'application/json';
  var csrfToken = null;
  function getCsrfToken() {
    jQuery
      .get(Drupal.url('rest/session/token'))
      .done(function (data) {
        csrfToken = data;
      });
  }
  getCsrfToken();

  function getComment(commentID, success, error) {
    jQuery.ajax({
      url: Drupal.url('comment/' + commentID) + '?_format=json',
      method: 'GET',
      headers: {
        'Content-Type': contentType
      },
      success: success,
      error: error
    });
  }

  function getCommentsForNode(nodeID, success, error) {
    jQuery.ajax({
      url: Drupal.url('node/' + nodeID + '/comments') + '?_format=json',
      method: 'GET',
      headers: {
        'Content-Type': contentType
      },
      success: success,
      error: error
    });
  }

  function updateComment(commentID, comment, success, error) {
    jQuery.ajax({
      url: Drupal.url('comment/' + commentID) + '?_format=json',
      method: 'PATCH',
      headers: {
        'Content-Type': contentType,
        'X-CSRF-Token': csrfToken
      },
      data: JSON.stringify(comment),
      success: success,
      error: error
    });
  }

  function postComment(comment, success, error) {
    jQuery.ajax({
      url: Drupal.url('entity/comment') + '?_format=json',
      method: 'POST',
      headers: {
        'Content-Type': contentType,
        'X-CSRF-Token': csrfToken
      },
      data: JSON.stringify(comment),
      success: success,
      error: error,
      // jQuery is plain stupid when it comes to handling 201 responses, hence
      // the need for this silly hack.
      dataFilter: function (response) {
        return (response === '') ? null : response;
      }
    });
  }

  function deleteComment(commentID, success, error) {
    jQuery.ajax({
      url: Drupal.url('comment/' + commentID) + '?_format=json',
      method: 'DELETE',
      headers: {
        'X-CSRF-Token': csrfToken
      },
      success: success,
      error: error,
      // jQuery is plain stupid when it comes to handling 204 responses, hence
      // the need for this silly hack.
      dataFilter: function (response) {
        return (response === '') ? null : response;
      }
    });
  }

  function demo() {
    // GET all Comment entities for a Node entity.
    getCommentsForNode(1, function (comments) {
      console.log('All comments for node 1:', comments);
    });

    // GET a Comment entity, then PATCH it.
    getComment(1, function (comment) {
      // Remove the fields that are read-only.
      var commentID = comment.cid[0].value;
      delete comment.cid;
      delete comment.uuid;
      delete comment.entity_type;
      delete comment.entity_id;
      delete comment.field_name;
      delete comment.created;
      delete comment.changed;
      delete comment.pid;
      delete comment.thread;

      // Modify the existing subject: append a period.
      comment.subject[0].value += '.';

      console.log('Updated comment: ', comment);

      updateComment(commentID, comment, function () {
        console.log('Updated comment ' + commentID + ': appended a period to the comment subject. Refresh the page to see it.');
      });
    });

    // POST a Comment entity, then DELETE it after 10 seconds.
    var newComment = {
      comment_body: [{
        value: '<p>This product works great with llamas!</p>',
        format: 'basic_html'
      }],
      uid: [{
        target_id: 1
      }],
      subject: [{
        value: 'Llamas are awesome!'
      }],
      field_rate_this: [{
        value: 5
      }],
      // Comment type. ('Comment' entity type's 'bundle'.)
      comment_type: [{
        target_id: 'bakeoff'
      }],
      // Which entity this comment is posted on.
      entity_type: [{
        value: 'node'
      }],
      entity_id: [{
        target_id: 1
      }],
      field_name: [{
        value: 'field_product_feedback'
      }]
    };
    postComment(newComment, function (data, status, jqXHR) {
      // A new comment was posted, but we only get its URI, nothing else.
      var newCommentUri = jqXHR.getResponseHeader('Location');

      // So, GET the full Comment entity.
      jQuery
        .get(newCommentUri + '?_format=json')
        .done(function (comment) {
          // Proudly show it.
          var commentID = comment.cid[0].value;
          console.log('Posted comment ' + commentID, comment);

          // Create a closure to delete the new comment.
          var deleteNewComment = function () {
            deleteComment(commentID, function () {
              console.log('Deleted comment ' + commentID);
            })
          };

          // Delete the new comment after letting it be admired for 10 seconds.
          window.setTimeout(deleteNewComment, 10000);
        });
    });
  }

  window.setTimeout(demo, 1000);

})(jQuery, Drupal);
