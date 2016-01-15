<?php

/**
 * @file
 * Contains \Drupal\flag\FlagSimpleTest.
 */

namespace Drupal\flag\Tests;

use Drupal\user\RoleInterface;
use Drupal\user\Entity\Role;


/**
 * Tests the Flag form actions (add/edit/delete).
 *
 * @group flag
 */
class FlagSimpleTest extends FlagTestBase {

  /**
   * The label of the flag to create for the test.
   *
   * @var string
   */
  protected $label = 'Test label 123';

  /**
   * The ID of the flag to create for the test.
   *
   * @var string
   */
  protected $id = 'test_label_123';

  /**
   * The flag link type.
   *
   * @var string
   */
  protected $flagLinkType;

  /**
   * The node type to use in the test.
   *
   * @var string
   */
  protected $nodeType = 'article';

  /**
   * User object.
   *
   * @var \Drupal\user\Entity\User|false
   */
  protected $adminUser;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array('views', 'flag', 'node', 'field_ui');

  /**
   * Configures test base and executes test cases.
   */
  public function testFlagForm() {
    // Create and log in our user.
    $this->adminUser = $this->drupalCreateUser([
      'administer flags',
      'administer flagging display',
      'administer node display',
    ]);

    $this->drupalLogin($this->adminUser);

    // Create content type.
    $this->drupalCreateContentType(['type' => $this->nodeType]);

    // Create flag.
    // TODO: replace this with createFlag(), and change rest of test class to
    // use generated flag ID and labels.
    $edit = [
      'label' => $this->label,
      'id' => $this->id,
      'bundles[' . $this->nodeType . ']' => $this->nodeType,
      'flag_short' => 'Flag this item',
      'flag_long' => 'Unflag this item',
    ];
    $this->createFlagWithForm('node', $edit);

    $this->doFlagLinksTest();
    $this->doGlobalFlagLinksTest();
    $this->doTestFlagCounts();
    $this->doUserDeletionTest();
  }

  /**
   * Test the flag link in different states, for different users.
   */
  public function doFlagLinksTest() {
    $node = $this->drupalCreateNode(['type' => $this->nodeType]);
    $node_id = $node->id();

    // Grant the flag permissions to the authenticated role, so that both
    // users have the same roles and share the render cache.
    $role = Role::load(RoleInterface::AUTHENTICATED_ID);
    $role->grantPermission('flag ' . $this->id);
    $role->grantPermission('unflag ' . $this->id);
    $role->save();

    // Create and login a new user.
    $user_1 = $this->drupalCreateUser();
    $this->drupalLogin($user_1);

    $this->drupalGet('node/' . $node_id);
    $this->clickLink('Flag this item');
    $this->assertResponse(200);
    $this->assertLink('Unflag this item');

    // Switch user to check flagging link.
    $user_2 = $this->drupalCreateUser();
    $this->drupalLogin($user_2);
    $this->drupalGet('node/' . $node_id);
    $this->assertResponse(200);
    $this->assertLink('Flag this item');

    // Switch back to first user and unflag.
    $this->drupalLogin($user_1);
    $this->drupalGet('node/' . $node_id);

    $this->clickLink('Unflag this item');
    $this->assertResponse(200);
    $this->assertLink('Flag this item');

    // Check that the anonymous user, who does not have the necessary
    // permissions, does not see the flag link.
    $this->drupalLogout();
    $this->drupalGet('node/' . $node_id);
    $this->assertNoLink('Flag this item');
  }

  /**
   * Test a global flag link appears correctly in different states.
   */
  public function doGlobalFlagLinksTest() {
    $node = $this->drupalCreateNode(['type' => $this->nodeType]);
    $node_id = $node->id();

    // Grant the flag permissions to the authenticated role, so that both
    // users have the same roles and share the render cache.
    $role = Role::load(RoleInterface::AUTHENTICATED_ID);
    $role->grantPermission('flag ' . $this->id);
    $role->grantPermission('unflag ' . $this->id);
    $role->save();

    // Create and login a new user.
    $user_1 = $this->drupalCreateUser();
    $user_2 = $this->drupalCreateUser();
    $this->drupalLogin($user_1);

    // Flag the node with user 1.
    $this->drupalGet('node/' . $node_id);
    $this->clickLink('Flag this item');
    $this->assertResponse(200);
    $this->assertLink('Unflag this item');

    $this->drupalLogin($user_2);
    $this->drupalGet('node/' . $node_id);
    $this->assertLink('Flag this item');

    $this->drupalLogin($this->adminUser);
    $edit = [
      'global' => true,
    ];
    $this->drupalPostForm('admin/structure/flags/manage/' . $this->id, $edit, t('Save Flag'));
    $this->drupalGet('admin/structure/flags/manage/' . $this->id);
    $this->assertFieldChecked('edit-global');

    $this->drupalLogin($user_2);
    $this->drupalGet('node/' . $node_id);
    $this->assertLink('Unflag this item');

    $this->drupalLogin($this->adminUser);
    $edit = [
      'global' => false,
    ];
    $this->drupalPostForm('admin/structure/flags/manage/' . $this->id, $edit, t('Save Flag'));
    $this->drupalGet('admin/structure/flags/manage/' . $this->id);
    $this->assertNoFieldChecked('edit-global');

    $this->drupalLogin($user_2);
    $this->drupalGet('node/' . $node_id);
    $this->assertLink('Flag this item');
  }

  /**
   * Creates user, sets flags and deletes user.
   */
  public function doUserDeletionTest() {
    $node = $this->drupalCreateNode(['type' => $this->nodeType]);
    $node_id = $node->id();

    // Create and login a new user.
    $user_1 = $this->drupalCreateUser();
    $this->drupalLogin($user_1);

    $this->drupalGet('node/' . $node_id);
    $this->clickLink('Flag this item');
    $this->assertResponse(200);
    $this->assertLink('Unflag this item');

    $count_flags_before = \Drupal::entityQuery('flagging')
      ->condition('uid', $user_1->id())
      ->condition('flag_id', $this->id)
      ->condition('entity_type', $node->getEntityTypeId())
      ->condition('entity_id', $node_id)
      ->count()
      ->execute();

    $this->assertTrue(1, $count_flags_before);

    $user_1->delete();

    $count_flags_after = \Drupal::entityQuery('flagging')
      ->condition('uid', $user_1->id())
      ->condition('flag_id', $this->id)
      ->condition('entity_type', $node->getEntityTypeId())
      ->condition('entity_id', $node_id)
      ->count()
      ->execute();

    $this->assertEqual(0, $count_flags_after);
  }

  /**
   * Flags a node using different user accounts and checks flag counts.
   */
  public function doTestFlagCounts() {
    /** \Drupal\Core\Database\Connection $connection */
    $connection = \Drupal::database();

    $node = $this->drupalCreateNode(['type' => $this->nodeType]);
    $node_id = $node->id();

    // Grant the flag permissions to the authenticated role, so that both
    // users have the same roles and share the render cache.
    $role = Role::load(DRUPAL_AUTHENTICATED_RID);
    $role->grantPermission('flag ' . $this->id);
    $role->grantPermission('unflag ' . $this->id);
    $role->save();

    // Create and login user 1.
    $user_1 = $this->drupalCreateUser();
    $this->drupalLogin($user_1);

    // Flag node (first count).
    $this->drupalGet('node/' . $node_id);
    $this->clickLink('Flag this item');
    $this->assertResponse(200);
    $this->assertLink('Unflag this item');

    // Check for 1 flag count.
    $count_flags_before = $connection->select('flag_counts')
      ->condition('flag_id', $this->id)
      ->condition('entity_type', $node->getEntityTypeId())
      ->condition('entity_id', $node_id)
      ->countQuery()
      ->execute()
      ->fetchField();
    $this->assertTrue(1, $count_flags_before);

    // Logout user 1, create and login user 2.
    $user_2 = $this->drupalCreateUser();
    $this->drupalLogin($user_2);

    // Flag node (second count).
    $this->drupalGet('node/' . $node_id);
    $this->clickLink('Flag this item');
    $this->assertResponse(200);
    $this->assertLink('Unflag this item');

    // Check for 2 flag counts.
    $count_flags_after = $connection->select('flag_counts')
      ->condition('flag_id', $this->id)
      ->condition('entity_type', $node->getEntityTypeId())
      ->condition('entity_id', $node_id)
      ->countQuery()
      ->execute()
      ->fetchField();
    $this->assertTrue(2, $count_flags_after);

    // Unflag the node again.
    $this->drupalGet('node/' . $node_id);
    $this->clickLink('Unflag this item');
    $this->assertResponse(200);
    $this->assertLink('Flag this item');

    // Check for 1 flag count.
    $count_flags_before = $connection->select('flag_counts')
      ->condition('flag_id', $this->id)
      ->condition('entity_type', $node->getEntityTypeId())
      ->condition('entity_id', $node_id)
      ->countQuery()
      ->execute()
      ->fetchField();
    $this->assertEqual(1, $count_flags_before);

    // Delete  user 1.
    $user_1->delete();

    // Check for 0 flag counts, user deletion should lead to count decrement
    // or row deletion.
    $count_flags_before = $connection->select('flag_counts')
      ->condition('flag_id', $this->id)
      ->condition('entity_type', $node->getEntityTypeId())
      ->condition('entity_id', $node_id)
      ->countQuery()
      ->execute()
      ->fetchField();

    $this->assertEqual(0, $count_flags_before);
  }
}
