<?php

/**
 * @file
 * Contains \Drupal\comment_ajax\CommentAjaxController.
 */

namespace Drupal\comment_ajax;

use Drupal\comment\CommentInterface;
use Drupal\Core\Ajax\AjaxResponse;

class CommentAjaxController {

  /**
   * Deletes the given comment permanently.
   *
   * @param \Drupal\comment\CommentInterface; $comment
   *   The comment to delete.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public function delete(CommentInterface $comment) {
    // Simulate an error.
    // return new AjaxResponse('[]', 500);

    $comment->delete();

    return new AjaxResponse();
  }

}
