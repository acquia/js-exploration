<?php
/**
 * @file
 * Contains the \Drupal\flag\Entity\Flagging content entity.
 */

namespace Drupal\flag\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\flag\FlaggingInterface;

/**
 * Provides the flagging content entity.
 *
 * @ContentEntityType(
 *  id = "flagging",
 *  label = @Translation("Flagging"),
 *  bundle_label = @Translation("Flagging"),
 *  handlers = {
 *    "form" = {
 *      "add" = "Drupal\flag\Form\FlaggingForm",
 *      "edit" = "Drupal\flag\Form\FlaggingForm",
 *      "delete" = "Drupal\flag\Form\UnflagConfirmForm"
 *    },
 *    "views_data" = "Drupal\flag\FlaggingViewsData",
 *  },
 *  base_table = "flagging",
 *  entity_keys = {
 *    "id" = "id",
 *    "bundle" = "flag_id",
 *    "uuid" = "uuid"
 *  },
 *  bundle_entity_type = "flag",
 *  field_ui_base_route = "entity.flag.edit_form",
 * )
 */
class Flagging extends ContentEntityBase implements FlaggingInterface {
  // @todo should there be a data_table annotation?
  // @todo should the bundle entity_key annotation be "flag" not "type"?
  /**
   * Gets the flag ID for the parent flag.
   *
   * @return string
   *   The flag ID.
   */
  public function getFlagId() {
    return $this->get('flag_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getFlag() {
    return $this->entityManager()->getStorage('flag')->load($this->getFlagId());
  }

  /**
   * Gets the entity type of the flaggable.
   *
   * @return string
   *   A string containing the flaggable type ID.
   */
  public function getFlaggableType() {
    return $this->get('entity_type')->value;
  }

  /**
   * Gets the entity ID of the flaggable.
   *
   * @return string
   *   A string containing the flaggable ID.
   */
  public function getFlaggableId() {
    return $this->get('entity_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getFlaggable() {
    $flaggable_type = $this->getFlaggableType();
    $flaggable_id = $this->getFlaggableId();
    return $this->entityManager()->getStorage($flaggable_type)->load($flaggable_id);
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Flagging ID'))
      ->setDescription(t('The flagging ID.'))
      ->setReadOnly(TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The flagging UUID.'))
      ->setReadOnly(TRUE);

    $fields['flag_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Flag ID'))
      ->setDescription(t('The Flag ID.'))
      ->setRequired(TRUE)
      ->setReadOnly(TRUE);

    // This field is on flaggings even though it duplicates the entity type
    // field on the flag so that flagging queries can use it.
    $fields['entity_type'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Entity Type'))
      ->setDescription(t('The Entity Type.'));

    $fields['entity_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Entity ID'))
      ->setRequired(TRUE)
      ->setDescription(t('The Entity ID.'));

    // Also duplicates data on flag entity for querying purposes.
    $fields['global'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Global'))
      ->setDescription(t('A boolean indicating whether the flagging is global.'));

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('User ID'))
      ->setDescription(t('The user ID of the flagging user.'))
      ->setSettings([
        'target_type' => 'user',
        'default_value' => 0,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the flagging was created.'));

    return $fields;
  }

}
