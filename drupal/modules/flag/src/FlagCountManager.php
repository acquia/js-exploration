<?php

/**
 * @file
 * Contains Drupal\flag\FlagCountManager.
 */

namespace Drupal\flag;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\flag\Event\FlagEvents;
use Drupal\flag\Event\FlaggingEvent;
use Drupal\flag\Event\FlagResetEvent;
use Drupal\flag\FlagCountManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\flag\FlagInterface;

/**
 * Class FlagCountManager.
 */
class FlagCountManager implements FlagCountManagerInterface, EventSubscriberInterface {

  /**
   * Database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Constructs a FlagCountManager.
   */
  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public function getEntityFlagCounts(EntityInterface $entity) {
    $counts = &drupal_static(__METHOD__);

    $entity_type = $entity->getEntityTypeId();
    $entity_id = $entity->id();
    if (!isset($counts[$entity_type][$entity_id])) {
      $counts[$entity_type][$entity_id] = [];
      $query = $this->connection->select('flag_counts', 'fc');
      $result = $query
        ->fields('fc', ['flag_id', 'count'])
        ->condition('fc.entity_type', $entity_type)
        ->condition('fc.entity_id', $entity_id)
        ->execute();
      foreach ($result as $row) {
        $counts[$entity_type][$entity_id][$row->flag_id] = $row->count;
      }
    }

    return $counts[$entity_type][$entity_id];
  }

  /**
   * {@inheritdoc}
   */
  public function getFlagFlaggingCount(FlagInterface $flag) {
    $counts = &drupal_static(__METHOD__);

    $flag_id = $flag->id();
    $entity_type = $flag->getFlaggableEntityTypeId();

    // We check to see if the flag count is already in the cache,
    // if it's not, run the query.
    if (!isset($counts[$flag_id][$entity_type])) {
      $counts[$flag_id][$entity_type] = [];
      $result = $this->connection->select('flagging', 'f')
        ->fields('f', ['flag_id'])
        ->condition('flag_id', $flag_id)
        ->condition('entity_type', $entity_type)
        ->countQuery()
        ->execute()
        ->fetchField();
      $counts[$flag_id][$entity_type] = $result;
    }

    return $counts[$flag_id][$entity_type];
  }

  /**
   * {@inheritdoc}
   */
  public function getFlagEntityCount(FlagInterface $flag) {
    $counts = &drupal_static(__METHOD__);
    $flag_name = $flag->id();

    if (!isset($counts[$flag_name])) {
      $counts[$flag_name] = $this->connection->select('flag_counts', 'fc')
        ->fields('fc', array('flag_id'))
        ->condition('flag_id', $flag_name)
        ->countQuery()
        ->execute()
        ->fetchField();
    }

    return $counts[$flag_name];
  }

  /**
   * {@inheritdoc}
   */
  public function getUserFlagFlaggingCount(FlagInterface $flag, AccountInterface $user) {
    $counts = &drupal_static(__METHOD__);

    $flag_id = $flag->id();
    $uid = $user->id();

    // We check to see if the flag count is already in the cache,
    // if it's not, run the query.
    if (!isset($counts[$flag_id][$uid])) {
      $counts[$flag_id][$uid] = [];
      $result = $this->connection->select('flagging', 'f')
        ->fields('f', ['flag_id'])
        ->condition('flag_id', $flag_id)
        ->condition('uid', $uid)
        ->countQuery()
        ->execute()
        ->fetchField();
      $counts[$flag_id][$uid] = $result;
    }

    return $counts[$flag_id][$uid];
  }

  /**
   * Increments count of flagged entities.
   *
   * @param \Drupal\flag\Event\FlaggingEvent $event
   *   The flagging event.
   */
  public function incrementFlagCounts(FlaggingEvent $event) {
    $this->connection->merge('flag_counts')
      ->key([
        'flag_id' => $event->getFlag()->id(),
        'entity_id' => $event->getEntity()->id(),
        'entity_type' => $event->getEntity()->getEntityTypeId(),
      ])
      ->fields([
        'last_updated' => REQUEST_TIME,
        'count' => 1,
      ])
      ->expression('count', 'count + :inc', [':inc' => 1])
      ->execute();
  }

  /**
   * Decrements count of flagged entities.
   *
   * @param \Drupal\flag\Event\FlaggingEvent $event
   *   The flagging Event.
   */
  public function decrementFlagCounts(FlaggingEvent $event) {

    /* @var \Drupal\flag\FlaggingInterface flag */
    $flag = $event->getFlag();
    /* @var Drupal\Core\Entity\EntityInterface $entity */
    $entity = $event->getEntity();

    $count_result = $this->connection->select('flag_counts')
      ->fields(NULL, ['flag_id', 'entity_id', 'entity_type', 'count'])
      ->condition('flag_id', $flag->id())
      ->condition('entity_id', $entity->id())
      ->condition('entity_type', $entity->getEntityTypeId())
      ->execute()
      ->fetchAll();
    if ($count_result[0]->count == '1') {
      $this->connection->delete('flag_counts')
        ->condition('flag_id', $flag->id())
        ->condition('entity_id', $entity->id())
        ->condition('entity_type', $entity->getEntityTypeId())
        ->execute();
    }
    else {
      $this->connection->update('flag_counts')
        ->expression('count', 'count - 1')
        ->condition('flag_id', $flag->id())
        ->condition('entity_id', $entity->id())
        ->condition('entity_type', $entity->getEntityTypeId())
        ->execute();
    }
  }

  /**
   * Deletes all of a flag's count entries.
   *
   * @param \Drupal\flag\event\FlagResetEvent $event
   *  The flag reset event.
   */
  public function resetFlagCounts(FlagResetEvent $event) {
    /* @var \Drupal\flag\FlaggingInterface flag */
    $flag = $event->getFlag();

    $this->connection->delete('flag_counts')
      ->condition('flag_id', $flag->id())
      ->execute();
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events = array();
    $events[FlagEvents::ENTITY_FLAGGED][] = array('incrementFlagCounts', -100);
    $events[FlagEvents::ENTITY_UNFLAGGED][] = array('decrementFlagCounts', -100);
    $events[FlagEvents::FLAG_RESET][] = array('resetFlagCounts', -100);
    return $events;
  }

}
