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
        $vecount = substr_count($newbody, 'data-embed-button="media"');
        $processv = 0;
        for ($k = 0; $k < $vecount; $k++) {
          # pull out the media entity embed.
          # Find the last < before data-embed-button="media"
          $mstart = strrpos($newbody, "<", -strlen($newbody) + strpos($newbody, 'data-embed-button="media"', $mend));
          # Get the > after that point
          $mend = strpos($newbody, ">", $mstart + 3);
          # get the next one. This is the end of the media embed section.
          $mend = strpos($newbody, ">", $mend + 3) + 1;
          # Get the section to work on
          $mediasec = substr($newbody, $mstart, $mend - $mstart);
          $newmediasec = $mediasec;

          # Need to jump if already fixed!
          if (strpos($mediasec, "drupal-media") == false) {
            $processv = 1;
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
            'format' => $oldformat,]);
          $node->save();
        }
      }


    }
    print_r($tobefixed);
  }

  /**
   * Command oc_oc2mediaimage: This will transfer content from oc_image to media:image
   *
   * @command oc_tools:oc_oc2mediaimage
   * @aliases oc2media
   */
  public function oc_oc2mediaimage()
  {
    # This will transfer content from oc_image to media:image

    # Get all oc_docs
    $nids = \Drupal::entityQuery('node')->condition('type', 'oc_image')->execute();
    $tobefixed = array();
//    $fix = new OcToolsCommands();
//    $euuids = $fix->oc_mlist("remote_video"); # get the array of corresponding values.
    foreach ($nids as $nid) {
      $node = \Drupal\node\Entity\Node::load($nid);
      $newbody = $node->get('body')->getValue()[0]['value'];
      $oldsum = $node->get('body')->summary;
      $oldformat = $node->get('body')->format;
      $authors = $node->get('field_oc_author_s_')->getValue();
      $level = $node->get('field_oc_level')->getValue();
      $licence = $node->get('field_oc_licence')->getValue();
      $link = $node->get('field_oc_link')->getValue();
      $meta = $node->get('field_meta_tags')->getValue();
      $back = $node->get('field_oc_back_color')->getValue();
      $publisher = $node->get('field_oc_publisher')->getValue();
      $seo = $node->get('field_yoast_seo')->getValue();
      $topic = $node->get('field_oc_topic')->getValue();
      $visibility = $node->get('field_content_visibility')->getValue();
      $year = $node->get('field_oc_year')->getValue();
      $image = $node->get('field_oc_image')->getValue();
      $title = $node->get('title')->getValue();

      $account = \Drupal\user\Entity\User::load('4');
      $oldmedia = \Drupal::entityTypeManager()->getStorage('media');
      $media = \Drupal\media\entity\Media::create([
        'bundle' => 'image',
        'uid' => $account->id(),
        'langcode' => \Drupal::languageManager()->getDefaultLanguage()->getId(),
        'status' => 1,
        'field_media_in_library' => TRUE,
////        'body' => $newbody,
        'field_oc_author_s_' => $authors,
        'field_oc_level' => $level,
        'field_oc_licence' => $licence,
        'field_oc_link' => $link,
        'field_meta_tags' => $meta,
        'field_oc_back_color' => $back,
        'field_oc_publisher' => $publisher,
        'field_yoast_seo' => $seo,
        'field_oc_topic' => $topic,
        'field_content_visibility' => $visibility,
        'field_oc_year' => $year,
        'title' => $title,
        'field_media_image' => [
          'target_id' => $image[0]['target_id'],
          'alt' => $image[0]['alt'],
          'title' => $image[0]['alt'],
          'width' => $image[0]['width'],
          'height' => $image[0]['height']
        ],
//        'field_oc_old' => $node->id(),
      ]);
      $media->set('body', [
        'summary' => $oldsum,
        'value' => $newbody,
        'format' => $oldformat,]);
      $media->setName($node->get('title')->getValue())->setPublished(TRUE)->save();

// Now modify the oc_doc link to the image and change to media:image

      $nids = \Drupal::entityQuery('node')->condition('type', 'oc_doc')->execute();

      foreach ($nids as $nidd) {
        $node = \Drupal\node\Entity\Node::load($nidd);
        if (isset($node->get('field_oc_linked_image')->getValue()[0])) {
          $imageid = $node->get('field_oc_linked_image')->getValue()[0]['target_id'];
          if ($imageid == $nid) {
            //now add the correct new media id to field_oc_linked_media
            $node->set('field_media', $media->id());
            $node->set('field_page_header_style', 'media_header');
            $node->save();
          }
        }
      }
      $nids = \Drupal::entityQuery('node')->condition('type', 'oc_sequence')->execute();

      foreach ($nids as $nidd) {
        $node = \Drupal\node\Entity\Node::load($nidd);
        if (isset($node->get('field_oc_linked_image')->getValue()[0])) {
          $imageid = $node->get('field_oc_linked_image')->getValue()[0]['target_id'];
          if ($imageid == $nid) {
            //now add the correct new media id to field_oc_linked_media
            $node->set('field_media', $media->id());
            $node->set('field_page_header_style', 'media_header');
            $node->save();
          }
        }
      }
    }
  }


  /**
   * Command oc_addmediafield: This will add the field_media to a content type.
   *
   * @command oc_tools:oc_addmediafield
   * @aliases oc_addmf
   */
  public function oc_addmediafield()
  {
//    $field = \Drupal\field\Entity\FieldConfig::loadByName('node', 'oc_doc', 'field_media');
//    $uuid = 'd12da227-890b-4eb7-86b1-93937e7d6d91';
//    $field->set('uuid',$uuid);
//    $field->save();
    $label = "Main Media";
    $type = "oc_link";
    $field_storage = \Drupal\field\Entity\FieldStorageConfig::loadByName('node', 'field_media');
    $field = \Drupal\field\Entity\FieldConfig::loadByName('node', $type, 'field_media');
    if (empty($field)) {
      $field = \Drupal\field\Entity\FieldConfig::create([
        'field_storage' => $field_storage,
        'bundle' => $type,
        'label' => $label,
        'settings' =>
          array(
            'handler' => 'default:media',
            'handler_settings' =>
              array(
                'target_bundles' =>
                  array(
                    'image' => 'image',
                    'remote_video' => 'remote_video',
                    'video' => 'video',
                  ),
                'sort' =>
                  array(
                    'field' => '_none',
                  ),
                'auto_create' => false,
                'auto_create_bundle' => 'remote_video',
              ),
          ),
      ]);
      $field->save();
    }

    // could use: https://drupal.stackexchange.com/questions/220229/how-can-i-add-color-to-drush-output


    $instance = \Drupal\field\Entity\FieldStorageConfig::loadByName('node', 'field_media');
    $field_info = \Drupal\field\Entity\FieldConfig::loadByName('node', 'oc_link', 'field_media');
    drush_print("Instance");
    //drush_print($instance);

    //drush_print($field_info);
//    Drupal\field\Entity\FieldConfig::__set_state(array(
//      'deleted' => false,
//      'fieldStorage' => NULL,
//      'id' => 'node.oc_doc.field_media',
//      'field_name' => 'field_media',
//      'field_type' => 'entity_reference',
//      'entity_type' => 'node',
//      'bundle' => 'oc_link',
//      'label' => 'Main media',
//      'description' => '',
//      'settings' =>
//        array (
//          'handler' => 'default:media',
//          'handler_settings' =>
//            array (
//              'target_bundles' =>
//                array (
//                  'image' => 'image',
//                  'remote_video' => 'remote_video',
//                  'video' => 'video',
//                ),
//              'sort' =>
//                array (
//                  'field' => '_none',
//                ),
//              'auto_create' => false,
//              'auto_create_bundle' => 'remote_video',
//            ),
//        ),
//      'required' => false,
//      'translatable' => true,
//      'default_value' =>
//        array (
//        ),
//      'default_value_callback' => '',
//      'itemDefinition' => NULL,
//      'constraints' =>
//        array (
//        ),
//      'propertyConstraints' =>
//        array (
//        ),
//      'originalId' => 'node.oc_doc.field_media',
//      'status' => true,
//      'uuid' => NULL,
//      'isUninstalling' => false,
//      'langcode' => 'en',
//      'third_party_settings' =>
//        array (
//        ),
//      '_core' =>
//        array (
//        ),
//      'trustedData' => false,
//      'entityTypeId' => 'field_config',
//      'enforceIsNew' => NULL,
//      'typedData' => NULL,
//      'cacheContexts' =>
//        array (
//          0 => 'languages:language_interface',
//        ),
//      'cacheTags' =>
//        array (
//        ),
//      'cacheMaxAge' => -1,
//      '_serviceIds' =>
//        array (
//        ),
//      '_entityStorages' =>
//        array (
//        ),
//      'dependencies' =>
//        array (
//          'config' =>
//            array (
//              0 => 'field.storage.node.field_media',
//              1 => 'media.type.image',
//              2 => 'media.type.remote_video',
//              3 => 'media.type.video',
//              4 => 'node.type.oc_link',
//            ),
//        ),
//      'isSyncing' => false,
//    ));
    drush_print("done");
  }


  /**
   * Command oc_updatectwfm: This will update content types using varbase_media_header by copying field_media content to
   * a new field_nmedia then removing the field and reapplying the field
   *
   * @command oc_tools:oc_updatectwfm1
   * @aliases ocuctwfm1
   */
  public function oc_updatectwfm1()
  {
    # This will transfer content from oc_image to media:image

    # Get all oc_docs
    $nids = \Drupal::entityQuery('node')->condition('type', 'oc_sequence')->execute();
    $tobefixed = array();
//    $fix = new OcToolsCommands();
//    $euuids = $fix->oc_mlist("remote_video"); # get the array of corresponding values.


    //transfer content from media_field to temp field
    foreach ($nids as $nid) {
      $node = \Drupal\node\Entity\Node::load($nid);
      $mediaf = $node->get('field_media')->getValue();
      $phs = $node->get('field_page_header_style')->getValue();
      $node->set('field_oc_lmedia', $mediaf);
      $node->save();
    }
  }



  /**
   * Command oc_updatectwfm: This will update content types using varbase_media_header by copying field_media content to
   * a new field_nmedia then removing the field and reapplying the field
   *
   * @command oc_tools:oc_updatectwfm2
   * @aliases ocuctwfm2
   */
  public function oc_updatectwfm2()
  {
    # This will transfer content from oc_image to media:image

    # Get all oc_docs
    $nids = \Drupal::entityQuery('node')->condition('type', 'oc_sequence')->execute();
    $tobefixed = array();
//    $fix = new OcToolsCommands();
//    $euuids = $fix->oc_mlist("remote_video"); # get the array of corresponding values.


    //transfer content from media_field to temp field
    foreach ($nids as $nid) {
      $node = \Drupal\node\Entity\Node::load($nid);
      $mediaf = $node->get('field_oc_lmedia')->getValue();
      $phs = $node->get('field_page_header_style')->getValue();
      $node->set('field_media', $mediaf);
      if ($mediaf) {
        $node->set('field_page_header_style', 'media_header');
      } else {
        $node->set('field_page_header_style', 'standard');
      }
      $node->save();
    }
  }


}
