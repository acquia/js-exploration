<?php
/**
 * @file
 * Contains \Drupal\flag\Tests\LinkTypeReloadTest.
 */

namespace Drupal\flag\Tests;

use Drupal\simpletest\WebTestBase;
use Drupal\user\Entity\Role;

/**
 * Tests the reload link type.
 *
 * @group flag
 */
class LinkTypeReloadTest extends WebTestBase {

  /**
   * Set to TRUE to strict check all configuration saved.
   *
   * @see \Drupal\Core\Config\Testing\ConfigSchemaChecker
   *
   * @var bool
   */
  protected $strictConfigSchema = FALSE;

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
  public static $modules = array('flag', 'node');

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    // Create content type.
    $this->drupalCreateContentType(['type' => $this->nodeType]);
  }

  /**
   * Test the confirm form link type.
   */
  public function testFlagReloadLink() {
    // Create and log in our user.
    $this->adminUser = $this->drupalCreateUser([
      'administer flags',
    ]);

    $this->drupalLogin($this->adminUser);

    $this->doCreateFlag();
    $this->doFlagNode();
  }

  /**
   * Create a node type and a flag.
   */
  public function doCreateFlag() {
    // Test with minimal value requirement.
    $edit = [
      'flag_entity_type' => 'entity:node',
    ];
    $this->drupalPostForm('admin/structure/flags/add', $edit, t('Continue'));

    // Update the flag.
    $edit = [
      'link_type' => 'reload',
    ];
    $this->drupalPostAjaxForm(NULL, $edit, 'link_type');

    $edit = [
      'label' => $this->label,
      'id' => $this->id,
      'bundles[' . $this->nodeType . ']' => $this->nodeType,
    ];
    $this->drupalPostForm(NULL, $edit, t('Create Flag'));

    // Check to see if the flag was created.
    $this->assertText(t('Flag @this_label has been added.', ['@this_label' => $this->label]));
  }

  /**
   * Flag a node.
   */
  public function doFlagNode() {
    $node = $this->drupalCreateNode(['type' => $this->nodeType]);
    $node_id = $node->id();

    // Grant the flag permissions to the authenticated role, so that both
    // users have the same roles and share the render cache. ???? TODO
    $role = Role::load(DRUPAL_AUTHENTICATED_RID);
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

    // Attempt to load the reload link URL without the token.
    // We (probably) can't obtain the URL from the route rather than hardcoding
    // it, as that would probably give us the token too.
    $this->drupalGet("flag/flag/$this->id/$node_id");
    $this->assertResponse(403, "Access to the flag reload link is denied when no token is supplied.");

    // Click the flag link.
    $this->drupalGet('node/' . $node_id);
    $this->clickLink(t('Flag this item'));

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

    // Attempt to load the reload link URL without the token.
    $this->drupalGet("flag/unflag/$this->id/$node_id");
    $this->assertResponse(403, "Access to the unflag reload link is denied when no token is supplied.");

    // Unflag the node.
    $this->drupalGet('node/' . $node_id);
    $this->clickLink(t('Unflag this item'));

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
