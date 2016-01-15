<?php
/**
 * @file
 * Contains \Drupal\flag\FlagAccessController.
 */

namespace Drupal\flag;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\flag\Entity\Flag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controls flagging access permission.
 */
class FlagAccessController extends ControllerBase {

  /**
   * Checks flag permission.
   *
   * @param \Drupal\flag\FlagInterface $flag
   *   The flag entity.
   *
   * @return \Drupal\Core\Access\AccessResult
   *   Returns indication value for flag access permission.
   */
  public function checkFlag(FlagInterface $flag) {
    return AccessResult::allowedIf($flag->hasActionAccess('flag'));
  }

  /**
   * Checks unflag permission.
   *
   * @param \Drupal\flag\FlagInterface $flag
   *   The flag entity.
   *
   * @return \Drupal\Core\Access\AccessResult
   *   Returns indication value for unflag access permission.
   */
  public function checkUnflag(FlagInterface $flag) {
    return AccessResult::allowedIf($flag->hasActionAccess('unflag'));
  }

}
