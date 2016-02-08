<?php

/**
 * @file
 * Contains \Drupal\flag\Form\UnflagConfirmForm.
 */

namespace Drupal\flag\Form;

use Drupal\flag\Form\FlagConfirmBaseForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides the confirm form page for unflagging an entity.
 *
 * @see \Drupal\flag\Plugin\ActionLink\ConfirmForm
 */
class UnflagConfirmForm extends FlagConfirmBaseForm {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'unflag_confirm_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->flag->getLinkTypePlugin()->getUnflagQuestion();
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->flag->getUnflagLongText();
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Unflag');
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    \Drupal::service('flag')->unflag($this->flag, $this->entity);
  }

}
