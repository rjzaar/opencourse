<?php
/**
 * @file
 * Contains \Drupal\custom_migration\Plugin\migrate\source\OCBook.
 */
namespace Drupal\custom_migration\Plugin\migrate\source;
use Drupal\migrate\Row;
use Drupal\migrate\Plugin\migrate\source\SqlBase;
/**
 * Collect the information we need to migrate book from database (table: menu_links where module="book").
 * We extend the SqlBase source that is used to retrieve values from sql.
 *
 * @MigrateSource(
 *   id = "OCBook",
 *   source_module = "custom_migration"
 * )
 */
class OCBook extends SqlBase {
  /**
   * {@inheritdoc}
   */
  public function query() {
    // We use $this->select() and set the query that will generate our Rows
    $query = $this->select('menu_links', 'm')
      ->fields('m')
      ->condition('module', 'book', '=')
      ->orderBy('mlid');
    return $query;
  }

  public function lookupID(Row $row, $ocfield){
    $field_value = $row->getSourceProperty($ocfield);
    $query = $this->select('menu_links','temp')
      ->fields('temp',['mlid', 'link_path'])
      ->condition('mlid',$field_value)
      ->execute()
      ->fetchAll();
    $field_value_new= intval(substr($query[0]['link_path'],5));
    if (!$field_value_new) {$field_value_new=0;}

    return $field_value_new;
  }
  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) { // Row is a now a class with helpful methods.
    $row->setSourceProperty('has_children',intval($row->getSourceProperty('has_children')));
    $row->setSourceProperty('weight',intval($row->getSourceProperty('weight')));
    $row->setSourceProperty('depth',intval($row->getSourceProperty('depth')));
    $mlid = $row->getSourceProperty('mlid');
    $query = $this->select('menu_links','mlid')
      ->fields('mlid',['mlid', 'link_path'])
      ->condition('mlid',$mlid)
      ->execute()
      ->fetchAll();
    $mlidnew= intval(substr($query[0]['link_path'],5));
    $row->setSourceProperty('mlid',$mlidnew);

    $plid = $row->getSourceProperty('plid');
    $query = $this->select('menu_links','plid')
      ->fields('plid',['mlid', 'link_path'])
      ->condition('mlid',$plid)
      ->execute()
      ->fetchAll();
    $plidnew= intval(substr($query[0]['link_path'],5));
    if (!$plidnew) {$plidnew=0;}
    $row->setSourceProperty('plid',$plidnew);

    $p1 = $row->getSourceProperty('p1');
    $query = $this->select('menu_links','p1')
      ->fields('p1',['mlid', 'link_path'])
      ->condition('mlid',$p1)
      ->execute()
      ->fetchAll();
    $p1new= intval(substr($query[0]['link_path'],5));
    $row->setSourceProperty('p1',$p1new);

    $p2 = $row->getSourceProperty('p2');
    $query = $this->select('menu_links','p2')
      ->fields('p2',['mlid', 'link_path'])
      ->condition('mlid',$p2)
      ->execute()
      ->fetchAll();
    $p2new= intval(substr($query[0]['link_path'],5));
    $row->setSourceProperty('p2',$p2new);

    $p3 = $row->getSourceProperty('p3');
    $query = $this->select('menu_links','p3')
      ->fields('p3',['mlid', 'link_path'])
      ->condition('mlid',$p3)
      ->execute()
      ->fetchAll();
    $p3new= intval(substr($query[0]['link_path'],5));
    $row->setSourceProperty('p3',$p3new);

    $p4 = $row->getSourceProperty('p4');
    $query = $this->select('menu_links','p4')
      ->fields('p4',['mlid', 'link_path'])
      ->condition('mlid',$p4)
      ->execute()
      ->fetchAll();
    $p4new= intval(substr($query[0]['link_path'],5));
    $row->setSourceProperty('p4',$p4new);

    $p5 = $row->getSourceProperty('p5');
    $query = $this->select('menu_links','p5')
      ->fields('p5',['mlid', 'link_path'])
      ->condition('mlid',$p5)
      ->execute()
      ->fetchAll();
    $p5new= intval(substr($query[0]['link_path'],5));
    $row->setSourceProperty('p5',$p5new);

    $p6 = $row->getSourceProperty('p6');
    $query = $this->select('menu_links','p6')
      ->fields('p6',['mlid', 'link_path'])
      ->condition('mlid',$p6)
      ->execute()
      ->fetchAll();
    $p6new= intval(substr($query[0]['link_path'],5));
    $row->setSourceProperty('p6',$p6new);

    $p7 = $row->getSourceProperty('p7');
    $query = $this->select('menu_links','p7')
      ->fields('p7',['mlid', 'link_path'])
      ->condition('mlid',$p7)
      ->execute()
      ->fetchAll();
    $p7new= intval(substr($query[0]['link_path'],5));
    $row->setSourceProperty('p7',$p7new);

    $p8 = $row->getSourceProperty('p8');
    $query = $this->select('menu_links','p8')
      ->fields('p8',['mlid', 'link_path'])
      ->condition('mlid',$p8)
      ->execute()
      ->fetchAll();
    $p8new= intval(substr($query[0]['link_path'],5));
    $row->setSourceProperty('p8',$p8new);

    $p9 = $row->getSourceProperty('p9');
    $query = $this->select('menu_links','p9')
      ->fields('p9',['mlid', 'link_path'])
      ->condition('mlid',$p9)
      ->execute()
      ->fetchAll();
    $p9new= intval(substr($query[0]['link_path'],5));
    $row->setSourceProperty('p9',$p9new);


    return parent::prepareRow($row);
  }
  /**
   * {@inheritdoc}
   */
  public function fields() {
    return array(
      'mlid' => $this->t('Menu Link ID'),
      'plid' => $this->t('The Parent Link ID'),
      'module' => $this->t('The Name of the Module that generated this link'),
      'has_children' => $this->t('Flag indicating whether any links have this link as a parent (1 = children exist, 0 = no children).'),
      'weight' => $this->t('Link weight among links in the same menu at the same depth.'),
      'depth' => $this->t('The depth relative to the top level. A link with plid == 0 will have depth == 1.'),
      'p1' => $this->t('The first mlid in the materialized path. If N = depth, then pN must equal the mlid. If depth > 1 then p(N-1) must equal the plid. All pX where X > depth must equal zero. The columns p1 .. p9 are also called the parents.'),
      'p2' => $this->t('The second mlid in the materialized path. See p1.'),
      'p3' => $this->t('The third mlid in the materialized path. See p1.'),
      'p4' => $this->t('The fourth mlid in the materialized path. See p1.'),
      'p5' => $this->t('The fifth mlid in the materialized path. See p1.'),
      'p6' => $this->t('The sixth mlid in the materialized path. See p1.'),
      'p7' => $this->t('The seventh mlid in the materialized path. See p1.'),
      'p8' => $this->t('The eighth mlid in the materialized path. See p1.'),
      'p9' => $this->t('The ninth mlid in the materialized path. See p1.')
    );
  }
  /**
   * {@inheritdoc}
   */
  public function getIds() {
    // The row property that represent the unique id for the id map.
    return [
      'mlid' => [
        'type' => 'integer',
        'alias' => 'm',
      ],
    ];
  }
}