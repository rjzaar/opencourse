<?php

namespace Drupal\oc_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

//use Drupal\Core\Url;

/**
 * Provides a 'OCBlock' block.
 *
 * @Block(
 *  id = "ocblock",
 *  admin_label = @Translation("OCblock"),
 * )
 */
class OCBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
        'content_type' => $this->t(''),
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['content_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Select element'),
      '#options' => [
        'oc_sequences' => $this->t('Sequences'),
        'oc_docs' => $this->t('Docs'),
        'oc_books' => $this->t('Books'),
        'oc_links' => $this->t('Links'),
        'oc_images' => $this->t('Images'),
      ],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['content_type'] = $form_state->getValue('content_type');

  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];

    $node = \Drupal::request()->attributes->get('node');
    if ($node) {
      foreach (\Drupal\group\Entity\GroupContent::loadByEntity($node) as $group_content) {
        $oc_group = $group_content->getGroup()->id();
        $build['oc_group'] = $oc_group;
      }
    }
//    $entities = $group->getContentEntities('group_node:travel_story_update');

      $build['oc_node'] = $node;
//    $build['oc_ct'] = $this->configuration['content_type'];


//      $results = \Drupal::database()->select("SELECT node_field_data.nid AS nid, group_content_field_data_node_field_data.id AS group_content_field_data_node_field_data_id
//FROM
//{node_field_data} node_field_data
//INNER JOIN {group_content_field_data} group_content_field_data_node_field_data ON node_field_data.nid = group_content_field_data_node_field_data.entity_id AND group_content_field_data_node_field_data.type = 'open-group_node-oc_doc'
//WHERE ((group_content_field_data_node_field_data.gid = '1' )) AND (node_field_data.type IN ('oc_doc'))
//ORDER BY node_field_data.changed DESC
//LIMIT 50 OFFSET 0");
//      $build['oc_results']=$results;
    $oc_viewid = $this->configuration['content_type'];
    $build['oc_view']=$oc_viewid;
    if($oc_viewid) {
      $view_B = \Drupal\views\Views::getView($oc_viewid);
      // now load view B
//    $view_B = views_get_view_result('oc_docs');
// set arguments for view
     if ($view_B) {
       $view_B->setArguments(array($oc_group));
// execute view
       $view_B->execute();
//    foreach ($view_B->result as $result) {
//      // capture the result of your field in an array.
//      $values[] = $result->field_machine_name;
//    }
       $view_B->preview();
       $values = $view_B->result;
       $build['oc_values'] = $values;
       foreach ($values as $result_node) {
         $id = $result_node->_entity->id();
         $node = \Drupal\node\Entity\Node::load($id);
         if (isset($node)) {
           // Add head image if it has one.
           if ($node->hasField('field_oc_image')) {
             if (isset($node->get('field_oc_image')[0])) {
               $fnodeid = $node->get('field_oc_image')->getValue()[0]['target_id'];
//    $variables['oc_head_image'] =$fnodeid ;
               $fnode = \Drupal\file\Entity\File::load($fnodeid);
               $variables['oc_image_uri'] = $fnode->getFileUri();
               if (isset($fnode)) {
                 $fnodeuri = $fnode->getFileUri();
                 $oc_head_image = $fnodeuri;
               }

             }
           }

           if ($node->hasField('field_oc_book_image')) {
             if (isset($node->get('field_oc_book_image')[0])) {
               $fnodeid = $node->get('field_oc_book_image')->getValue()[0]['target_id'];
//    $variables['oc_head_image'] =$fnodeid ;
               $fnode = \Drupal\file\Entity\File::load($fnodeid);
               $variables['oc_image_uri'] = $fnode->getFileUri();
               if (isset($fnode)) {
                 $fnodeuri = $fnode->getFileUri();
                 $oc_head_image = $fnodeuri;
               }

             }
           }

           //get OC doc title image if it has one.
           if ($node->hasField('field_oc_title_image')) {
             if (isset($node->get('field_oc_title_image')[0])) {
//          $lnodeid = $node->get('field_oc_linked_image')->getValue()[0];
               $lnodeid = $node->get('field_oc_title_image')->getValue()[0]['target_id'];
               if (is_numeric($lnodeid)) {
                 $imnode = \Drupal\node\Entity\Node::load($lnodeid);
                 $fnodeid = $imnode->get('field_oc_image')->getValue()[0]['target_id'];
                 $fnode = \Drupal\file\Entity\File::load($fnodeid);
                 $variables['oc_image_uri'] = $fnode->getFileUri();
                 if (isset($fnode)) {
                   $fnodeuri = $fnode->getFileUri();
                   $oc_head_image = $fnodeuri;
                 }
               }
             } else {
               //get OC doc linked image if it has one but only if there is no title image
               if ($node->hasField('field_oc_linked_image')) {
                 if (isset($node->get('field_oc_linked_image')[0])) {
//          $lnodeid = $node->get('field_oc_linked_image')->getValue()[0];
                   $lnodeid = $node->get('field_oc_linked_image')->getValue()[0]['target_id'];
                   if (is_numeric($lnodeid)) {
                     $imnode = \Drupal\node\Entity\Node::load($lnodeid);
                     $fnodeid = $imnode->get('field_oc_image')->getValue()[0]['target_id'];
                     $fnode = \Drupal\file\Entity\File::load($fnodeid);
                     $variables['oc_image_uri'] = $fnode->getFileUri();
                     if (isset($fnode)) {
                       $fnodeuri = $fnode->getFileUri();
                       $oc_head_image = $fnodeuri;
                     }
                   }
                 }
               }
             }
           }
         }
         if (!isset($oc_head_image)){
           $oc_head_image="";
         }
         $oc_nodes[] = array('id' => $id, 'type' => $node->getType(), 'oc_head_image' => $oc_head_image, 'title' => $node->getTitle(), 'url' => $node->url());
       }
     }
    }
    if(isset($oc_nodes)) {
      $build['oc_items'] = $oc_nodes;
    }
    //    $nids = [];
//    foreach ($oc_group->getContent('group_node:oc_doc') as $group_content) {
//      $nids[] = $group_content->entity_id->target_id;
//    }
//    $build['oc_nids']=$nids;

//    $ids = \Drupal::entityQuery('group_content')
//      ->condition('entity_id', $node->id())
//      ->execute();
//
//    $relations = \Drupal\group\Entity\GroupContent::loadMultiple($ids);
//
//    foreach ($relations as $rel) {
//
//      if ($rel->getEntity()->getEntityTypeId() == 'node') {
//
//        $gids[] = $rel->getGroup()->id();
//      }
//    }
//    $build['oc_gids']=$gids;
//    $build['oc_relations']=$relations;


//    }
//    return array(
//      '#title' => $group,
//      '#link' => 'http://ws.agency/'
//    );
    return $build;
  }

}
