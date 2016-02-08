<?php

/**
 * @file
 * Contains Drupal\flag\FlagLinkBuilder.
 */

namespace Drupal\flag;

use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\flag\FlagServiceInterface;

/**
 * Provides a lazy builder for flag links.
 */
class FlagLinkBuilder implements FlagLinkBuilderInterface {

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityManager;

  /**
   * The flag service.
   *
   * @var \Drupal\flag\FlagServiceInterface
   */
  protected $flagService;

  /**
   * Constructor.
   *
   * @param Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *   The entity manager.
   * @param Drupal\flag\FlagServiceInterface $flag_service
   *   The flag service.
   */
  public function __construct(EntityManagerInterface $entity_manager, FlagServiceInterface $flag_service) {
    $this->entityManager = $entity_manager;
    $this->flagService = $flag_service;
  }

  /**
   * {@inheritdoc}
   */
  public function build($entity_type_id, $entity_id, $flag_id) {
    $entity = $this->entityManager->getStorage($entity_type_id)->load($entity_id);
    $flag = $this->flagService->getFlagById($flag_id);

    $link_type_plugin = $flag->getLinkTypePlugin();
    $link = $link_type_plugin->getLink($flag, $entity);
    return $link;
  }

}
