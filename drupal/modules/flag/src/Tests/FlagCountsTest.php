<?php
/**
 * @file
 * Contains \Drupal\flag\Tests\FlagCountsTest.
 */

namespace Drupal\flag\Tests;

use Drupal\node\Entity\Node;

/**
 * Tests the Flag counts API.
 *
 * @todo These are currently very basic, and simply test that the various count
 * functions return a sane value and don't crash.
 *
 * @group flag
 */
class FlagCountsTest extends FlagTestBase {

  /**
   * The flag.
   *
   * @var \Drupal\flag\FlagInterface
   */
  protected $flag;

  /**
   * The node.
   *
   * @var \Drupal\node\Entity\Node
   */
  protected $node;

  /**
   * The flag count service.
   *
   * @var \Drupal\flag\FlagCountManagerInterface
   */
  protected $flagCountService;

  /**
   * The flagging deletion service.
   *
   * @var \Drupal\flag\FlaggingServiceInterface
   */
  protected $flaggingDelete;

  /**
   * User object.
   *
   * @var \Drupal\user\Entity\User|false
   */
  protected $adminUser;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->flagCountService = \Drupal::service('flag.count');
    $this->flaggingDelete = \Drupal::service('flagging');

    // Create a flag.
    $this->flag = $this->createFlag('node', ['article'], 'reload');

    // Create a user who may flag.
    $this->adminUser = $this->drupalCreateUser([
      'administer flags',
    ]);

    // Create a node to flag.
    $this->node = Node::create([
      'body' => [
        [
          'value' => $this->randomMachineName(32),
          'format' => filter_default_format(),
        ]
      ],
      'type' => 'article',
      'title' => $this->randomMachineName(8),
    ]);
    $this->node->save();
  }

  /**
   * Tests that counts are kept in sync and can be retrieved.
   */
  public function testFlagCounts() {
    // Flag the node.
    $this->flagService->flag($this->flag, $this->node, $this->adminUser);

    // Check each of the count API functions.
    $flag_get_entity_flag_counts = $this->flagCountService->getFlagFlaggingCount($this->flag);
    $this->assertEqual($flag_get_entity_flag_counts, 1, "getFlagFlaggingCount() returns the expected count.");

    $flag_get_user_flag_counts = $this->flagCountService->getUserFlagFlaggingCount($this->flag, $this->adminUser);
    $this->assertEqual($flag_get_user_flag_counts, 1, "getUserFlagFlaggingCount() returns the expected count.");

    $flag_get_counts = $this->flagCountService->getEntityFlagCounts($this->node);
    $this->assertEqual($flag_get_counts[$this->flag->id()], 1, "getEntityFlagCounts() returns the expected count.");

    $flag_get_flag_counts = $this->flagCountService->getFlagEntityCount($this->flag);
    $this->assertEqual($flag_get_flag_counts, 1, "getFlagEntityCount() returns the expected count.");

    $this->flaggingDelete->reset($this->flag);
    drupal_static_reset();
    $flag_get_flag_counts = $this->flagCountService->getFlagEntityCount($this->flag);
    $this->assertEqual($flag_get_flag_counts, 0, "getFlagEntityCount() on reset flag returns the expected count.");
  }

  /**
   * Tests flaggings are deleted and counts are removed when a flag is deleted.
   */
  public function testFlagDeletion() {
    // Create a flag.
    $flag = $this->createFlag('node', ['article'], 'reload');

    // Create a article to flag.
    $article1 = Node::create([
      'body' => [
        [
          'value' => $this->randomMachineName(32),
          'format' => filter_default_format(),
        ]
      ],
      'type' => 'article',
      'title' => $this->randomMachineName(8),
    ]);
    $article1->save();

    // Create a second article.
    $article2 = Node::create([
      'body' => [
        [
          'value' => $this->randomMachineName(32),
          'format' => filter_default_format(),
        ]
      ],
      'type' => 'article',
      'title' => $this->randomMachineName(8),
    ]);
    $article2->save();

    // Flag both.
    $this->flagService->flag($flag, $article1);
    $this->flagService->flag($flag, $article2);

    // Confirm the counts have been incremented.
    $article1_count_before = $this->flagCountService->getEntityFlagCounts($article1);
    $this->assertEqual($article1_count_before[$flag->id()], 1, 'The article1 has been flagged.');
    $article2_count_before = $this->flagCountService->getEntityFlagCounts($article2);
    $this->assertEqual(count($article2_count_before[$flag->id()]), 1, 'The article2 has been flagged.');

    // Confirm the flagging have been created.
    $flaggings_before = $this->flagService->getFlaggings($flag);
    $this->assertEqual(count($flaggings_before), 2, 'There are two flaggings associated with the flag');

    // Delete the flag.
    $flag->delete();
    // Reset counts stored in FlagCountManager and force inspection of the
    // peristent store.
    drupal_static_reset();

    // The list of all flaggings MUST now be empty.
    $flaggings_after = $this->flagService->getFlaggings($flag);
    $this->assert(empty($flaggings_after), 'The flaggings were removed, when the flag was deleted');

    // The flag id is now stale, so instead of searching for the flag in the
    // count array as before we require the entire array should be empty.
    $article1_counts_after = $this->flagCountService->getEntityFlagCounts($article1);
    $this->assert(empty($article1_counts_after), 'Article1 counts has been removed.');
    $article2_counts_after = $this->flagCountService->getEntityFlagCounts($article2);
    $this->assertEqual(empty($article2_counts_after), 'Article2 counts has been removed.');
  }

}
