<?php

namespace Drupal\custom_migration\Plugin\migrate\destination;

use Drupal\Core\Entity\EntityInterface;
use Drupal\migrate\Plugin\migrate\destination\EntityContentBase;
use Drupal\migrate\Row;

/**
 * @MigrateDestination(
 *   id = "oc_book",
 *   provider = "custom_migration",
 *   source_module = "custom_migration"
 * )
 */
class Book extends EntityContentBase {

  /**
   * {@inheritdoc}
   */
  protected static function getEntityTypeId($plugin_id) {
    return 'node';
  }

  /**
   * {@inheritdoc}
   */
  protected function updateEntity(EntityInterface $entity, Row $row) {
    $entity->book = $row->getDestinationProperty('book');
  }

}
