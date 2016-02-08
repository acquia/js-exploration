<?php
/**
 * @file
 * Contains \Drupal\flag\FlaggingInterface.
 */

namespace Drupal\flag;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * The interface for flagging entities.
 */
interface FlaggingInterface extends ContentEntityInterface {

  /**
   * Returns the parent flag entity.
   *
   * @return \Drupal\Core\Entity\EntityInterface|\Drupal\flag\FlagInterface
   *   The flag related to this flagging.
   */
  public function getFlag();

  /**
   * Returns the flaggable entity.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *   The entity object.
   */
  public function getFlaggable();

}
