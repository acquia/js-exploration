<?php

/**
 * @file
 * Contains \Drupal\flag\Controller\ReloadLinkController.
 */

namespace Drupal\flag\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\flag\FlagInterface;
use Drupal\flag\FlagServiceInterface;

/**
 * Provides a controller to flag and unflag when routed from a normal link.
 */
class ReloadLinkController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * The flag service.
   *
   * @var \Drupal\flag\FlagServiceInterface
   */
  protected $flagService;

  /**
   * Constructor.
   *
   * @param FlagServiceInterface $flag
   *   The flag service.
   */
  public function __construct(FlagServiceInterface $flag) {
    $this->flagService = $flag;
  }

  /**
   * Create.
   *
   * @param ContainerInterface $container
   *   The container object.
   *
   * @return ReloadLinkController
   *   The reload link controller.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('flag')
    );
  }

  /**
   * Performs a flagging when called via a route.
   *
   * @param \Drupal\flag\FlagInterface $flag
   *   The flag entity.
   * @param int $entity_id
   *   The flaggable ID.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   The response object.
   *
   * @see \Drupal\flag\Plugin\Reload
   */
  public function flag(FlagInterface $flag, $entity_id) {
    /* @var \Drupal\Core\Entity\EntityInterface $entity */
    $entity = $this->flagService->getFlaggableById($flag, $entity_id);

    try {
      /* @var \Drupal\flag\FlaggingInterface $flagging */
      $flagging = $this->flagService->flag($flag, $entity);
    }
    catch (\LogicException $e) {
      // Fail silently so we return to the entity, which will show an updated
      // link for the existing state of the flag.
    }

    // Redirect back to the entity. A passed in destination query parameter
    // will automatically override this.
    $url_info = $entity->urlInfo();
    return $this->redirect($url_info->getRouteName(), $url_info->getRouteParameters());
  }

  /**
   * Performs a flagging when called via a route.
   *
   * @param \Drupal\flag\FlagInterface $flag
   *   The flag entity.
   * @param int $entity_id
   *   The flagging ID.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   The response object.
   *
   * @see \Drupal\flag\Plugin\Reload
   */
  public function unflag(FlagInterface $flag, $entity_id) {
    /* @var \Drupal\Core\Entity\EntityInterface $entity */
    $entity = $this->flagService->getFlaggableById($flag, $entity_id);

    try {
      $this->flagService->unflag($flag, $entity);
    }
    catch (\LogicException $e) {
      // Fail silently so we return to the entity, which will show an updated
      // link for the existing state of the flag.
    }

    // Redirect back to the entity. A passed in destination query parameter
    // will automatically override this.
    $url_info = $entity->urlInfo();
    return $this->redirect($url_info->getRouteName(), $url_info->getRouteParameters());
  }

}
