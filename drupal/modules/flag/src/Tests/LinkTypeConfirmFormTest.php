<?php
/**
 * @file
 * Contains \Drupal\flag\Tests\LinkTypeConfirmFormTest.
 */

namespace Drupal\flag\Tests;

use Drupal\simpletest\WebTestBase;
use Drupal\user\RoleInterface;
use Drupal\user\Entity\Role;

/**
 * Tests the confirm form link type.
 *
 * @group flag
 */
class LinkTypeConfirmFormTest extends WebTestBase {

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

  protected $flagConfirmMessage = 'Flag test label 123?';
  protected $unflagConfirmMessage = 'Unflag test label 123?';

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
   * Test the confirm form link type.
   */
  public function testCreateConfirmFlag() {
    // Create and log in our user.
    $this->adminUser = $this->drupalCreateUser([
      'administer flags',
      'administer flagging display',
      'administer node display',
    ]);

    $this->drupalLogin($this->adminUser);
    $this->doCreateFlag();
    $this->doFlagUnflagNode();
  }

  /**
   * Create a node type and a flag.
   */
  public function doCreateFlag() {
    // Create content type.
    $this->drupalCreateContentType(['type' => $this->nodeType]);

    // Test with minimal value requirement.
    $edit = [
      'flag_entity_type' => 'entity:node',
    ];
    $this->drupalPostForm('admin/structure/flags/add', $edit, t('Continue'));

    // Update the flag.
    $edit = [
      'link_type' => 'confirm',
    ];
    $this->drupalPostAjaxForm(NULL, $edit, 'link_type');

    // Check confirm form field entry.
    $this->assertText(t('Flag confirmation message'));
    $this->assertText(t('Unflag confirmation message'));

    $edit = [
      'label' => $this->label,
      'id' => $this->id,
      'bundles[' . $this->nodeType . ']' => $this->nodeType,
      'flag_confirmation' => $this->flagConfirmMessage,
      'unflag_confirmation' => $this->unflagConfirmMessage,
    ];
    $this->drupalPostForm(NULL, $edit, t('Create Flag'));

    // Check to see if the flag was created.
    $this->assertText(t('Flag @this_label has been added.', ['@this_label' => $this->label]));
  }

  /**
   * Create a node, flag it and unflag it.
   */
  public function doFlagUnflagNode() {
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

    // Get the flag count before the flagging, querying the database directly.
    $flag_count_pre = db_query('SELECT count FROM {flag_counts}
      WHERE flag_id = :flag_id AND entity_type = :entity_type AND entity_id = :entity_id', [
        ':flag_id' => $this->id,
        ':entity_type' => 'node',
        ':entity_id' => $node_id,
      ])->fetchField();

    // Click the flag link.
    $this->drupalGet('node/' . $node_id);
    $this->clickLink(t('Flag this item'));

    // Check if we have the confirm form message displayed.
    $this->assertText($this->flagConfirmMessage);

    // Submit the confirm form.
    $this->drupalPostForm('flag/confirm/flag/' . $this->id . '/' . $node_id, [], t('Flag'));
    $this->assertResponse(200);

    // Check that the node is flagged.
    $this->drupalGet('node/' . $node_id);
    $this->assertLink(t('Unflag this item'));

    // Check the flag count was incremented.
    $flag_count_flagged = db_query('SELECT count FROM {flag_counts}
      WHERE flag_id = :flag_id AND entity_type = :entity_type AND entity_id = :entity_id', [
        ':flag_id' => $this->id,
        ':entity_type' => 'node',
        ':entity_id' => $node_id,
      ])->fetchField();
    $this->assertEqual($flag_count_flagged, $flag_count_pre + 1, "The flag count was incremented.");

    // Unflag the node.
    $this->clickLink(t('Unflag this item'));

    // Check if we have the confirm form message displayed.
    $this->assertText($this->unflagConfirmMessage);

    // Submit the confirm form.
    $this->drupalPostForm(NULL, [], t('Unflag'));
    $this->assertResponse(200);

    // Check that the node is no longer flagged.
    $this->drupalGet('node/' . $node_id);
    $this->assertLink(t('Flag this item'));

    // Check the flag count was decremented.
    $flag_count_unflagged = db_query('SELECT count FROM {flag_counts}
      WHERE flag_id = :flag_id AND entity_type = :entity_type AND entity_id = :entity_id', [
        ':flag_id' => $this->id,
        ':entity_type' => 'node',
        ':entity_id' => $node_id,
      ])->fetchField();
    $this->assertEqual($flag_count_unflagged, $flag_count_flagged - 1, "The flag count was decremented.");
  }

}
