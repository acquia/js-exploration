<?php
/**
 * @file
 * Contains \Drupal\flag\Controller\FlagListBuilder.
 */

namespace Drupal\flag\Controller;

use Drupal\Core\Config\Entity\DraggableListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\flag\FlagInterface;
use Drupal\Core\Url;

/**
 * Provides a entity list page for Flags.
 */
class FlagListBuilder extends DraggableListBuilder {

  /**
   * {@inheritdoc}
   */
  protected $entitiesKey = 'flags';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'flag_list';
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = t('Flag');
    $header['roles'] = t('Roles');
    $header['global'] = t('Global?');
    $header['status'] = t('Status');

    return $header + parent::buildHeader();
  }

  /**
   * Creates a displayable string of roles that may use the flag.
   *
   * @param \Drupal\flag\FlagInterface $flag
   *   The flag entity.
   *
   * @return string
   *   An HTML string of roles.
   */
  protected function getFlagRoles(FlagInterface $flag) {
    $all_roles = [];

    foreach (array_keys($flag->getPermissions()) as $perm) {
      $roles = user_roles(FALSE, $perm);

      foreach ($roles as $rid => $role) {
        $all_roles[$rid] = $role->label();
      }
    }

    $out = implode(', ', $all_roles);

    if (empty($out)) {
      return [
        '#markup' => '<em>' . $this->t('None') . '</em>',
        '#allowed_tags' => ['em'],
      ];
    }

    return [
      '#markup' => rtrim($out, ', '),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {

    $row['label'] = $entity->label();

    $row['roles'] = $this->getFlagRoles($entity);

    $row['global'] = [
      '#markup' => $entity->isGlobal() ? t('Yes') : t('No'),
    ];

    $row['status'] = [
      '#markup' => $entity->isEnabled() ? t('enabled') : t('disabled'),
    ];

    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  // We override the render() method to add helpful text below the entity list.
  public function render() {
    $build['table'] = parent::render();
    $build['table']['flags']['#empty'] = t('No flags available. <a href=":link">Add flag</a>.', array(':link' => \Drupal::url('flag.add_page')));

    $output = "";

    // @todo Move this too hook_help()?
    if (\Drupal::moduleHandler()->moduleExists('views')) {
      $output .= '<p>';
      $output .= t('Lists of flagged content can be displayed using views. You can configure these in the Views administration section.');
      if (\Drupal::service('flag')->getFlagById('bookmarks')) {
        $output .= ' ' . t('Flag module automatically provides a few default views for the <em>bookmarks</em> flag. You can use these as templates by cloning these views and then customizing as desired.');
      }
      $output .= ' ' . t('The <a href="@flag-handbook-url">Flag module handbook</a> contains extensive <a href="@customize-url">documentation on creating customized views</a> using flags.', ['@flag-handbook-url' => 'http://drupal.org/handbook/modules/flag', '@customize-url' => 'http://drupal.org/node/296954']);
      $output .= '</p>';
    }

    if (!\Drupal::moduleHandler()->moduleExists('rules')) {
      $output .= '<p>' . t('Flagging an item may trigger <em>rules</em>. However, you don\'t have the <a href="@rules-url">Rules</a> module enabled, so you won\'t be able to enjoy this feature. The Rules module is a more extensive solution than Flag actions.', ['@rules-url' => Url::fromUri('http://drupal.org/node/407070')->toString()]) . '</p>';
    }

    /*
     * Commented out because the rules route name has yet to be established.
    else {
      $output .= '<p>' . t('Flagging an item may trigger <a href="@rules-url">rules</a>.', ['@rules-url' => Url::fromUri('base://admin/config/workflow/rules')]) . '</p>';
    }
    */

    $output .= '<p>' . t('To learn about the various ways to use flags, please check out the <a href="@handbook-url">Flag module handbook</a>.', ['@handbook-url' => 'http://drupal.org/handbook/modules/flag']) . '</p>';

    $build['markup'] = [
      '#type' => 'markup',
      '#markup' => $output,
    ];

    return $build;
  }

}
