<?php
# Thanks to: https://www.previousnext.com.au/blog/migrating-drupal-7-file-entities-drupal-8-media-entities
# modules/custom/custom_migration/src/Plugin/migrate/process/Youtube.php
namespace Drupal\custom_migration\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Custom process plugin to convert youtube scheme uri to video url.
 *
 * @MigrateProcessPlugin(
 *   id = "youtube"
 *
 * )
 */
class Youtube extends ProcessPluginBase {

  const SCHEME = 'youtube://';
  const BASE_URL = 'http://youtube.com/watch?';

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    // Convert youtube scheme uri to video url.
    if (strpos($value, static::SCHEME) !== FALSE) {
      $value = static::BASE_URL . implode('=', explode('/', str_replace(static::SCHEME, '', $value), 2));
    }
    else {
      $value = NULL;
    }
    return $value;
  }

}