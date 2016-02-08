<?php
/**
 * @file
 * Contains the \Drupal\flag\Controller\AJAXLinkController class.
 */

namespace Drupal\flag\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\flag\FlagInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;

/**
 * Provides a controller for AJAX-ified flag links.
 */
class AJAXLinkController extends ControllerBase {

  /**
   * Performs a flagging when called via a route.
   *
   * This method is invoked when a user clicks an AJAX flagging link provided
   * by the AJAXactionLink plugin.
   *
   * @param \Drupal\flag\FlagInterface $flag
   *   The flag entity.
   * @param int $entity_id
   *   The flaggable ID.
   *
   * @return AjaxResponse
   *   The response object.
   *
   * @see \Drupal\flag\Plugin\ActionLink\AJAXactionLink
   */
  public function flag(FlagInterface $flag, $entity_id) {
    $flag_service = \Drupal::service('flag');
    $entity = $flag_service->getFlaggableById($flag, $entity_id);
    try {
      $flag_service->flag($flag, $entity);
    }
    catch (\LogicException $e) {
      // Fail silently and return the updated link.
    }

    return $this->generateResponse($flag, $entity);
  }

  /**
   * Performs an unflagging when called via a route.
   *
   * This method is invoked when a user clicks an AJAX unflagging link provided
   * by the AJAXactionLink plugin.
   *
   * @param \Drupal\flag\FlagInterface $flag
   *   The flag entity.
   * @param int $entity_id
   *   The entity ID to unflag.
   *
   * @return AjaxResponse
   *   The response object.
   *
   * @see \Drupal\flag\Plugin\ActionLink\AJAXactionLink
   */
  public function unflag(FlagInterface $flag, $entity_id) {
    $flag_service = \Drupal::service('flag');
    $entity = $flag_service->getFlaggableById($flag, $entity_id);
    try {
      $flag_service->unflag($flag, $entity);
    }
    catch (\LogicException $e) {
      // Fail silently and return the updated link.
    }

    return $this->generateResponse($flag, $entity);
  }

  /**
   * Generates a response object after handing the un/flag request.
   *
   * @param FlagInterface $flag
   *   The flag entity.
   * @param EntityInterface $entity
   *   The entity object.
   *
   * @return AjaxResponse
   *   The response object.
   */
  protected function generateResponse(FlagInterface $flag, EntityInterface $entity) {
    // Create a new AJAX response.
    $response = new AjaxResponse();

    // Get the link type plugin.
    $link_type = $flag->getLinkTypePlugin();

    // Generate the link render array and get the link CSS ID.
    $link = $link_type->getLink($flag, $entity);
    $link_id = '#' . $link['link']['#attributes']['id'];

    // Create a new JQuery Replace command to update the link display.
    $replace = new ReplaceCommand($link_id, drupal_render($link));
    $response->addCommand($replace);

    return $response;
  }

}
