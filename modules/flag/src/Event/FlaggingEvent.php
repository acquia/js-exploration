<?php
/**
 * @file
 * Contains \Drupal\flag\Event\FlaggingEvent.
 */

namespace Drupal\flag\Event;


use Drupal\Core\Entity\EntityInterface;
use Drupal\flag\FlagInterface;

/**
 * Event manages the flagging of events.
 */
class FlaggingEvent extends FlagEventBase {

  /**
   * The Flag in question.
   *
   * @var \Drupal\Core\Entity\EntityInterface
   */
  protected $entity;

  /**
   * Builds a new FlaggingEvent.
   *
   * @param \Drupal\flag\FlagInterface $flag
   *   The flag.
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity to be acted upon.
   */
  public function __construct(FlagInterface $flag, EntityInterface $entity) {
    parent::__construct($flag);

    $this->entity = $entity;
  }

  /**
   * Returns the flaggable entity associated with the Event.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *   The entity.
   */
  public function getEntity() {
    return $this->entity;
  }

}
