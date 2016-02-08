<?php
/**
 * @file
 * Contains \Drupal\flag\Form\FlagConfirmBaseForm.
 */

namespace Drupal\flag\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Form\FormStateInterface;
use Drupal\flag\FlagInterface;

/**
 * Provides methods common to the flag and unflag confirm forms.
 *
 * @see \Drupal\flag\Plugin\ActionLink\ConfirmForm
 */
abstract class FlagConfirmBaseForm extends ConfirmFormBase {

  /**
   * The flaggable entity.
   *
   * @var \Drupal\Core\Entity\EntityInterface
   */
  protected $entity;

  /**
   * The flag entity.
   *
   * @var \Drupal\flag\FlagInterface
   */
  protected $flag;

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state,
                            FlagInterface $flag = NULL, $entity_id = NULL) {

    $this->flag = $flag;
    $flag_service = \Drupal::service('flag');
    $this->entity = $flag_service->getFlaggableById($this->flag, $entity_id);
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    $destination = \Drupal::request()->get('destination');
    if (!empty($destination)) {
      return Url::createFromPath($destination);
    }

    return $this->entity->urlInfo();
  }

}
