<?php
# Thanks to: https://www.previousnext.com.au/blog/migrating-drupal-7-file-entities-drupal-8-media-entities
# modules/custom/custom_migration/src/Plugin/migrate/process/SkipYoutubeVideos.php
namespace Drupal\custom_migration\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\MigrateSkipRowException;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Skip youtube videos.
 *
 * @MigrateProcessPlugin(
 *   id = "skip_youtube_files"
 * )
 */
class SkipYoutubeVideos extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (parse_url(end($value), PHP_URL_SCHEME) == 'youtube') {
      throw new MigrateSkipRowException();
    }
    return $value;
  }

}