<?php

/**
 * @file
 * Contains Drupal\flag\FlaggingService.
 */

namespace Drupal\flag;

use Drupal\flag\Event\FlagEvents;
use Drupal\flag\Event\FlagResetEvent;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class FlaggingService.
 *
 * Resets a flag on request and automatically when a flag is deleted.
 *
 * This implementation while correct may not scale.
 * With large numbers of flaggings to delete it will cosume an amount of
 * time which is deemed unacceptable to site users.
 *
 * Faster implementations become possible once a queue system is developed to
 * hand off multiple delettions.
 *
 * @see https://www.drupal.org/node/89181
 */
class FlaggingService implements FlaggingServiceInterface {

  /**
   * The entity query manager.
   *
   * @var \Drupal\Core\Entity\Query\QueryFactory
   */
  private $entityQueryManager;

  /**
   * The event dispatcher.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  private $eventDispatcher;

  /**
   * The Flag Service.
   *
   * @var \Drupal\flag\FlagService
   */
  private $flagService;

  /**
   * Constructor.
   *
   * @param QueryFactory $entity_query
   *   The entity query factory.
   * @param EventDispatcherInterface $event_dispatcher
   *   The event dispatcher service.
   */
  public function __construct(QueryFactory $entity_query, EventDispatcherInterface $event_dispatcher, FlagService $flag_service) {
    $this->entityQueryManager = $entity_query;
    $this->eventDispatcher = $event_dispatcher;
    $this->flagService = $flag_service;
  }

  /**
   * {@inheritdoc}
   */
  public function reset(FlagInterface $flag, EntityInterface $entity = NULL) {
    $query = $this->entityQueryManager->get('flagging')
      ->condition('flag_id', $flag->id());

    if (!empty($entity)) {
      $query->condition('entity_id', $entity->id());
    }

    // Count the number of flaggings to delete.
    $count = $query->count()
      ->execute();

    $this->eventDispatcher->dispatch(FlagEvents::FLAG_RESET, new FlagResetEvent($flag, $count));

    $flaggings = $this->flagService->getFlaggings($flag, $entity);
    foreach ($flaggings as $flagging) {
      $flagging->delete();
    }

    return $count;
  }

}
