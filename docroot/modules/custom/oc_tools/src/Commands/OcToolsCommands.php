<?php

namespace Drupal\oc_tools\Commands;

use Consolidation\OutputFormatters\StructuredData\RowsOfFields;
use Drush\Commands\DrushCommands;

/**
 * A Drush commandfile.
 *
 * In addition to this file, you need a drush.services.yml
 * in root of your module, and a composer.json file that provides the name
 * of the services file to use.
 *
 * See these files for an example of injecting Drupal services:
 *   - http://cgit.drupalcode.org/devel/tree/src/Commands/DevelCommands.php
 *   - http://cgit.drupalcode.org/devel/tree/drush.services.yml
 */
class OcToolsCommands extends DrushCommands
{

  /**
   * Command oc_mfcc: media field content copy:  copies the embed_media media to new remote_media.
   *
   * @param string $arg1
   *   Argument description.
   * @command oc_tools:oc_mfcc
   * @aliases ocmfcc
   * @options arr An option that takes multiple values.
   * @options msg Whether or not an extra message should be displayed to the user.
   * @usage oc_tools:oc_mfcc akanksha --msg
   *   Display 'Hello Akanksha!' and a message.
   */
  public function oc_mfcc($arg1, $options = ['msg' => FALSE])
  {

//    if ($options['msg']) {
//      $this->output()->writeln('Hello ' . $arg1 . '! This is your first Drush 9 command.');
//    }
//    else {
//      $this->output()->writeln('Hello ' . $arg1 . '!');
//    }
//    $this->logger()->success(dt('Achievement unlocked.'));
//  }
    $query = \Drupal::entityQuery('media')
      ->condition('bundle', 'video_embed');
    $vids = $query->execute();
//$nodes = $node_storage->loadMultiple($nids);
//  $variables['oc_vids'] = $vids;
//    print_r($vids);
//    foreach ($vids as $vid) {
//      $oldmedia = \Drupal::entityTypeManager()->getStorage('media')->load($vid);
//      print_r($oldmedia->id() . " ");
//      print_r($oldmedia->get('mid')->getValue()[0]['value']. " ");
//      print_r($oldmedia->get('vid')->getValue()[0]['value']. PHP_EOL);
//    }


    foreach ($vids as $vid) {
      $oldmedia = \Drupal::entityTypeManager()->getStorage('media')->load($vid);
      $media = \Drupal\media\entity\Media::create([
        'bundle' => 'remote_video',
        'uid' => \Drupal::currentUser()->id(),
        'field_media_in_library' => TRUE,
        'field_oc_provider' => $vid,
        'field_media_oembed_video' => $oldmedia->get('field_media_video_embed_field')->getValue(),
      ]);
      $media->setName($oldmedia->get('name')->getValue())->setPublished(TRUE)->save();

      $ocnew_value = \Drupal::entityTypeManager()->getStorage('media')->load($vid)->get('field_media_video_embed_field')->getValue();

      \Drupal::entityTypeManager()->getStorage('media')->load($vid)->set('field_media_oembed_video', $ocnew_value);
    }

// $variables['ocvid'] = \Drupal::entityTypeManager()->getStorage('media')->load(2239);
  }


  /**
   * Command oc_mlist: list the content type
   *
   * @param string $arg1
   *   the media type to list
   * @command oc_tools:oc_mlist
   * @aliases ocmlist
   * @options arr An option that takes multiple values.
   * @options msg Whether or not an extra message should be displayed to the user.
   * @usage oc_tools:oc_mlist remote_video --msg
   *   Display 'Hello Akanksha!' and a message.
   */
  public function oc_mlist($arg1, $options = ['msg' => FALSE])
  {

//    if ($options['msg']) {
//      $this->output()->writeln('Hello ' . $arg1 . '! This is your first Drush 9 command.');
//    }
//    else {
//      $this->output()->writeln('Hello ' . $arg1 . '!');
//    }
//    $this->logger()->success(dt('Achievement unlocked.'));
//  }
    $query = \Drupal::entityQuery('media')
      ->condition('bundle', 'remote_video');
    $rvids = $query->execute();

    $query = \Drupal::entityQuery('media')
      ->condition('bundle', 'video_embed');
    $evids = $query->execute();

    $ruuids = array();
    $euuids = array();

    // Build the remote video array
    foreach ($rvids as $vid) {
      $oldmedia = \Drupal::entityTypeManager()->getStorage('media')->load($vid);
      // print_r($oldmedia->id() . " ");
      //   print_r($oldmedia->get('uuid')->getValue()[0]['value'] . " ");

//      print_r($oldmedia->get('mid')->getValue()[0]['value']. " ");
//      print_r($oldmedia->get('vid')->getValue()[0]['value']. " ");
//      print_r($oldmedia->get('name')->getValue()[0]['value']. PHP_EOL);
      print_r($oldmedia->get('field_oc_provider')->getValue()[0]['value'] . PHP_EOL);
      // create the array based on evid id.
      $ruuids[$oldmedia->get('field_oc_provider')->getValue()[0]['value']] = $oldmedia->get('uuid')->getValue()[0]['value'];
    }

// Build the remote video array
    foreach ($evids as $vid) {
      $oldmedia = \Drupal::entityTypeManager()->getStorage('media')->load($vid);
      print_r($oldmedia->id() . " ");
      print_r($oldmedia->get('uuid')->getValue()[0]['value'] . " ");
//      print_r($oldmedia->get('mid')->getValue()[0]['value']. " ");
//      print_r($oldmedia->get('vid')->getValue()[0]['value']. " ");
//      print_r($oldmedia->get('name')->getValue()[0]['value']. PHP_EOL);
      print_r($oldmedia->get('field_oc_provider')->getValue()[0]['value'] . PHP_EOL);
      // create the array based on evid id.
      $euuids[$oldmedia->get('uuid')->getValue()[0]['value']] = $ruuids[$oldmedia->id()];
    }
    //  print_r($euuids);
    return $euuids;
// Now go through and modify the media

  }

  /**
   * Command oc_c2rvibf: change embed_video uuids to their corresponding remote_video uuids in the embed body fields
   *
   * @command oc_tools:oc_c2rvibf
   * @aliases occ2rvibf
   * @usage oc_tools:oc_c2rvibf
   *   Fix all the body tags.
   */
  public function oc_c2rvibf()
  {
    # This will change embed_video uuids to their corresponding remote_video uuids in the embed body fields.

    # Get all oc_docs
    $nids = \Drupal::entityQuery('node')->condition('type', 'oc_doc')->execute();
    $tobefixed = array();
    $fix = new OcToolsCommands();
    $euuids = $fix->oc_mlist("remote_video"); # get the array of corresponding values.
    foreach ($nids as $nid) {
      $node = \Drupal\node\Entity\Node::load($nid);
      $newbody = $node->get('body')->getValue()[0]['value'];
      $oldsum = $node->get('body')->summary;
      $oldformat = $node->get('body')->format;

      if (strpos($node->get('body')->getValue()[0]['value'], 'data-embed-button="media"') !== false) {
        # OK this node has an embeded entity

        $mend = 0;
        $vecount=substr_count($newbody, 'data-embed-button="media"');
        $processv=0;
        for ($k = 0; $k < $vecount; $k++) {
          # pull out the media entity embed.
          # Find the last < before data-embed-button="media"
          $mstart = strrpos($newbody, "<", -strlen($newbody) + strpos($newbody, 'data-embed-button="media"', $mend));
          # Get the > after that point
          $mend = strpos($newbody, ">", $mstart + 3);
          # get the next one. This is the end of the media embed section.
          $mend = strpos($newbody, ">", $mend + 3)+1;
          # Get the section to work on
          $mediasec = substr($newbody, $mstart, $mend - $mstart);
          $newmediasec = $mediasec;

          # Need to jump if already fixed!
          if ( strpos($mediasec,"drupal-media") == false) {
            $processv=1;
            $newmediasec = str_replace("drupal-entity", "drupal-media", $newmediasec, $j);

            $newmediasec = str_replace("data-entity-embed-display=\"view_mode:media.small\"", "data-view-mode=\"small\"", $newmediasec, $i);

            if ($i < 1) {
              # does not have node view small so need to manually check
              $tobefixed[$nid] = $newbody;

            }
            # now change the uuid for as many embeded videos there are
            $vuuid = substr($newmediasec, strpos($newmediasec, "data-entity-uuid") + 18, 36);
            $newmediasec = str_replace($vuuid, $euuids[$vuuid], $newmediasec);
            $newbody = str_replace($mediasec, $newmediasec, $newbody);
            print_r("Fixed $nid: $node->getTitle()->getValue() $vuuid changed to $euuids[$vuuid]" . PHP_EOL);
          }
        }
        if ($processv) {
//          $node->set('body', $newbody);
          $node->set('body', [
            'summary' => $oldsum,
            'value' => $newbody,
            'format' => $oldformat, ]);
          $node->save();
        }
      }


    }
    print_r ($tobefixed);
  }

}
