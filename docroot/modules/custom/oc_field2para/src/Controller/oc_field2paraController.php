<?php

namespace Drupal\oc_field2para\Controller;

use Drupal\Core\Controller\ControllerBase;

class oc_field2paraController extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   */
  public function content() {
    $build = [];
    $nids = \Drupal::entityQuery('node')
      ->condition('type', 'oc_doc')
      ->execute();

//    foreach ($nids as $nid) {
//      $nidas[]=$nid;
//    }
//
//    $build['nidtable'] = [
//      '#rows' => $nid,
//      '#header' => [t('NIDS')],
//      '#type' => 'table',
//      '#empty' => t('No content available.'),
//    ];

    $nid = 890;
//    foreach ($nids as $nid) {
      $node      = \Drupal\node\Entity\NODE::load($nid);
      $videos = $node->field_oc_video->getValue();
      foreach ($videos as $video) {
        $videosarr[]  = $video;

//        $paragraph = Paragraph::create([
//          'title'          => $q,
//          'type'           => 'bp_simple',
//          'bp_text' => $q,
//        ]);
//        $paragraph->save();
//        $node->field_lp_paragraphs[] = $paragraph->id();
//        $node->paragraph_field_referenced->appendItem($paragraph);
      }

//    $node->field_qs_and_as[] = [
//      'target_id' => $paragraph->id(),
//      'target_revision_id' => $paragraph->getRevisionId(),
//    ];
//      $node->save();
//    }
//    $build['nids'] =$nids;
//    $build['video'] = $videosarr;
//    $build = array(
//      '#type' => 'markup',
//      '#markup' => $video,
//    );

    $build['pager_example'] = [
      '#rows' => $videosarr,
      '#header' => [t(convparas())],
      '#type' => 'table',
      '#empty' => t('No content available.'),
    ];

    return $build;
  }

}

function convparas(){
  return "Hello";
}