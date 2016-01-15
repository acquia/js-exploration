<?php
/**
 * @file
 * Contains \Drupal\Flag\Event\FlagResetEvent.
 */

namespace Drupal\flag\Event;

use Drupal\flag\FlagInterface;

/**
 * Event to handle a reset of Flag.
 */
class FlagResetEvent extends FlagEventBase {

  /**
   * The number of flaggings that will be deleted.
   *
   * @var int
   */
  protected $flagging_count;

  /**
   * Build the flag reset event.
   *
   * @param \Drupal\flag\FlagInterface $flag
   *  The flag that is being reset.
   * @param int $flagging_count
   *  The number of flaggings that will be deleted.
   */
  public function __construct(FlagInterface $flag, $flagging_count) {
    parent::__construct($flag);
    $this->flagging_count = $flagging_count;
  }

  /**
   * Get the number of flaggings that will be deleted after the reset.
   *
   * @return int
   *  The number of flaggings that will be deleted after the reset.
   */
  public function flaggingCount() {
    return $this->flagging_count;
  }
}
