<?php

/**
 * @file
 * Contains \Drupal\flag\Tests\FlagServiceTest.
 */

namespace Drupal\flag\Tests;

use Drupal\flag\Entity\Flag;
use Drupal\flag\Tests\FlagTestBase;

/**
 * Tests the FlagService.
 *
 * @group flag
 */
class FlagServiceTest extends FlagTestBase {

  public static $modules = array(
    'flag',
    'node',
    'user',
  );

  /**
   * Test the FlagService.
   */
  public function testFlagService() {
    $this->doTestFlagServiceGetFlag();
    $this->doTestFlagServiceFlagExceptions();
    $this->doTestFlagServiceGetFlaggingUsers();
  }

  /**
   * Tests that flags once created can be retrieved.
   */
  public function doTestFlagServiceGetFlag() {
    $flag = Flag::create([
      'id' => 'test',
      'entity_type' => 'node',
      'bundles' => [
        'article',
      ],
      'flag_type' => 'entity:node',
      'link_type' => 'reload',
      'flagTypeConfig' => [],
      'linkTypeConfig' => [],
    ]);

    $flag->save();

    $result = $this->flagService->getFlags('node', 'article');
    $this->assertIdentical(count($result), 1, 'Found flag type');
  }

  /**
   * Test exceptions are thrown when flagging and unflagging.
   */
  public function doTestFlagServiceFlagExceptions() {
    $this->drupalCreateContentType(['type' => 'article']);
    $this->drupalCreateContentType(['type' => 'not_article']);

    $flag = Flag::create([
      'id' => 'test',
      'entity_type' => 'node',
      'bundles' => [
        'article',
      ],
      'flag_type' => 'entity:node',
      'link_type' => 'reload',
      'flagTypeConfig' => [],
      'linkTypeConfig' => [],
    ]);

    // The service methods don't check access, so our user can be anybody.
    $account = $this->drupalCreateUser();

    // Test flagging.

    // Try flagging an entity that's not a node: a user account.
    try {
      $this->flagService->flag($flag, $account, $account);
      $this->fail("The exception was not thrown.");
    }
    catch (\LogicException $e) {
      $this->pass("The flag() method throws an exception when the flag does not apply to the entity type of the flaggable entity.");
    }

    // Try flagging a node of the wrong bundle.
    $wrong_node = $this->drupalCreateNode(['type' => 'not_article']);
    try {
      $this->flagService->flag($flag, $wrong_node, $account);
      $this->fail("The exception was not thrown.");
    }
    catch (\LogicException $e) {
      $this->pass("The flag() method throws an exception when the flag does not apply to the bundle of the flaggable entity.");
    }

    // Flag the node, then try to flag it again.
    $flaggable_node = $this->drupalCreateNode(['type' => 'article']);
    $this->flagService->flag($flag, $flaggable_node, $account);

    try {
      $this->flagService->flag($flag, $flaggable_node, $account);
      $this->fail("The exception was not thrown.");
    }
    catch (\LogicException $e) {
      $this->pass("The flag() method throws an exception when the flaggable entity is already flagged by the user with the flag.");
    }

    // Test unflagging.

    // Try unflagging an entity that's not a node: a user account.
    try {
      $this->flagService->unflag($flag, $account, $account);
      $this->fail("The exception was not thrown.");
    }
    catch (\LogicException $e) {
      $this->pass("The unflag() method throws an exception when the flag does not apply to the entity type of the flaggable entity.");
    }

    // Try unflagging a node of the wrong bundle.
    try {
      $this->flagService->unflag($flag, $wrong_node, $account);
      $this->fail("The exception was not thrown.");
    }
    catch (\LogicException $e) {
      $this->pass("The unflag() method throws an exception when the flag does not apply to the bundle of the flaggable entity.");
    }

    // Create a new node that's not flagged, and try to unflag it.
    $unflagged_node = $this->drupalCreateNode(['type' => 'article']);
    try {
      $this->flagService->unflag($flag, $unflagged_node, $account);
      $this->fail("The exception was not thrown.");
    }
    catch (\LogicException $e) {
      $this->pass("The unflag() method throws an exception when the flaggable entity is not flagged by the user with the flag.");
    }
  }

  /**
   * Tests that getFlaggingUsers method returns the expected result.
   */
  public function doTestFlagServiceGetFlaggingUsers() {

    $flag = Flag::create([
      'id' => 'testFlaggingUsers',
      'entity_type' => 'node',
      'bundles' => [
        //Content type article exists already from test before.
        'article',
      ],
      'flag_type' => 'entity:node',
      'link_type' => 'reload',
      'flagTypeConfig' => [],
      'linkTypeConfig' => [],
    ]);

    $flag->save();

    // The service methods don't check access, so our user can be anybody.
    $accounts = array($this->drupalCreateUser(), $this->drupalCreateUser());

    // Flag the node.
    $flaggable_node = $this->drupalCreateNode(['type' => 'article']);
    foreach ($accounts as $account) {
      $this->flagService->flag($flag, $flaggable_node, $account);
    }

    $flagging_users = $this->flagService->getFlaggingUsers($flaggable_node, $flag);
    $this->assertTrue(is_array($flagging_users), "The method getFlaggingUsers() returns an array.");

    foreach ($accounts as $account) {
      foreach ($flagging_users as $flagging_user) {
        if ($flagging_user->id() == $account->id()) {
          break;
        }
      }
      $this->assertTrue($flagging_user->id() == $account->id(), "The returned array has the flagged account included.");
    }
  }
}
