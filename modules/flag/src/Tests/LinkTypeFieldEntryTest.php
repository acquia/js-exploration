<?php
/**
 * @file
 * Contains \Drupal\flag\Tests\LinkTypeFieldEntryTest.
 */

namespace Drupal\flag\Tests;

use Drupal\field_ui\Tests\FieldUiTestTrait;
use Drupal\simpletest\WebTestBase;
use Drupal\user\RoleInterface;
use Drupal\user\Entity\Role;

/**
 * Test the Field Entry link type.
 *
 * @group flag
 */
class LinkTypeFieldEntryTest extends WebTestBase {

  use FieldUiTestTrait;

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

  protected $nodeId;

  protected $flagConfirmMessage = 'Flag test label 123?';
  protected $flagDetailsMessage = 'Enter flag test label 123 details';
  protected $unflagConfirmMessage = 'Unflag test label 123?';

  protected $flagFieldId = 'flag_text_field';
  protected $flagFieldLabel = 'Flag Text Field';
  protected $flagFieldValue;

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
  public static $modules = array('views', 'flag', 'node', 'field_ui', 'text', 'block');

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    // The breadcrumb block is needed for FieldUiTestTrait's tests.
    $this->drupalPlaceBlock('system_breadcrumb_block');
    $this->drupalPlaceBlock('local_tasks_block');
    $this->drupalPlaceBlock('page_title_block');
  }

  /**
   * Create a new flag with the Field Entry type, and add fields.
   */
  public function testCreateFieldEntryFlag() {
    $this->adminUser = $this->drupalCreateUser([
      'administer flags',
      'administer flagging display',
      'administer flagging fields',
      'administer node display',
    ]);

    $this->drupalLogin($this->adminUser);
    $this->doCreateFlag();
    $this->doAddFields();
    $this->doFlagNode();
    $this->doEditFlagField();
    $this->doBadEditFlagField();
  }

  /**
   * Create a node type and flag.
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
      'link_type' => 'field_entry',
    ];
    $this->drupalPostAjaxForm(NULL, $edit, 'link_type');

    // Check confirm form field entry.
    $this->assertText(t('Flag confirmation message'));
    $this->assertText(t('Enter flagging details message'));
    $this->assertText(t('Unflag confirmation message'));

    $edit = [
      'label' => $this->label,
      'id' => $this->id,
      'bundles[' . $this->nodeType . ']' => $this->nodeType,
      'flag_confirmation' => $this->flagConfirmMessage,
      'flagging_edit_title' => $this->flagDetailsMessage,
      'unflag_confirmation' => $this->unflagConfirmMessage,
    ];
    $this->drupalPostForm(NULL, $edit, t('Create Flag'));

    // Check to see if the flag was created.
    $this->assertText(t('Flag @this_label has been added.', ['@this_label' => $this->label]));
  }

  /**
   * Add fields to flag.
   */
  public function doAddFields() {
    // Check the Field UI tabs appear on the flag edit page.
    $this->drupalGet('admin/structure/flags/manage/' . $this->id);
    $this->assertText(t("Manage fields"), "The Field UI tabs appear on the flag edit form page.");

    $this->fieldUIAddNewField('admin/structure/flags/manage/' . $this->id, $this->flagFieldId, $this->flagFieldLabel, 'text');
  }

  /**
   * Create a node and flag it.
   */
  public function doFlagNode() {
    $node = $this->drupalCreateNode(['type' => $this->nodeType]);
    $this->nodeId = $node->id();

    // Grant the flag permissions to the authenticated role, so that both
    // users have the same roles and share the render cache.
    $role = Role::load(RoleInterface::AUTHENTICATED_ID);
    $role->grantPermission('flag ' . $this->id);
    $role->grantPermission('unflag ' . $this->id);
    $role->save();

    // Create and login a new user.
    $user_1 = $this->drupalCreateUser();
    $this->drupalLogin($user_1);

    // Click the flag link.
    $this->drupalGet('node/' . $this->nodeId);
    $this->clickLink(t('Flag this item'));

    // Check if we have the confirm form message displayed.
    $this->assertText($this->flagConfirmMessage);

    // Enter the field value and submit it.
    $this->flagFieldValue = $this->randomString();
    $edit = [
      'field_' . $this->flagFieldId . '[0][value]' => $this->flagFieldValue,
    ];
    $this->drupalPostForm(NULL, $edit, t('Create Flagging'));

    // Check that the node is flagged.
    $this->assertLink(t('Unflag this item'));
  }

  /**
   * Edit the field value of the existing flagging.
   */
  public function doEditFlagField() {
    $this->drupalGet('node/' . $this->nodeId);

    // Get the details form.
    $this->clickLink(t('Unflag this item'));
    $this->assertUrl('flag/details/edit/' . $this->id . '/' . $this->nodeId, [
      'query' => [
        'destination' => 'node/' . $this->nodeId,
      ],
    ]);

    // See if the details message is displayed.
    $this->assertText($this->flagDetailsMessage);

    // See if the field value was preserved.
    $this->assertFieldByName('field_' . $this->flagFieldId . '[0][value]', $this->flagFieldValue);

    // Update the field value.
    $this->flagFieldValue = $this->randomString();
    $edit = [
      'field_' . $this->flagFieldId . '[0][value]' => $this->flagFieldValue,
    ];
    $this->drupalPostForm(NULL, $edit, t('Update Flagging'));

    // Get the details form.
    $this->drupalGet('flag/details/edit/' . $this->id . '/' . $this->nodeId);

    // See if the field value was preserved.
    $this->assertFieldByName('field_' . $this->flagFieldId . '[0][value]', $this->flagFieldValue);
  }

  /**
   * Assert editing an invalid flagging throws an exception.
   */
  public function doBadEditFlagField() {
    // Test a good flag ID param, but a bad flaggable ID param.
    $this->drupalGet('flag/details/edit/' . $this->id . '/-9999');
    $this->assertResponse('404', 'Editing an invalid flagging path: good flag, bad entity.');

    // Test a bad flag ID param, but a good flaggable ID param.
    $this->drupalGet('flag/details/edit/jibberish/' . $this->nodeId);
    $this->assertResponse('404', 'Editing an invalid flagging path: bad flag, good entity');

    // Test editing a unflagged entity.
    $unlinked_node = $this->drupalCreateNode(['type' => $this->nodeType]);
    $this->drupalGet('flag/details/edit/' . $this->id . '/' . $unlinked_node->id());
    $this->assertResponse('404', 'Editing an invalid flagging path: good flag, good entity, but not flagged');
  }

}
