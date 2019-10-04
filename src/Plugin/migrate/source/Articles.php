<?php
namespace Drupal\migrate_joomla\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Source plugin for the content.
 *
 * @MigrateSource(
 *   id = "articles"
 * )
 */
class Articles extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('{content}', 'd')
      ->fields('d', ['id', 'title', 'catid', 'alias', 'introtext', 'fulltext', 'created', 'created_by']);
     
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'id' => [
        'type' => 'integer',
        'alias' => 'd',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'id' => $this->t('Page ID'),
      'title' => $this->t('Page Title'),
      'catid' => $this->t('Category'),
      'alias' => $this->t('alias'),
      'introtext' => $this->t('introtext'),
      'fulltext' => $this->t('Page Body'),
      // 'city' => $this->t('City'),
      // 'state' => $this->t('State'),
      // 'site_url' => $this->t('Site URL'),
      // 'site_name' => $this->t('Site Name'),
      'created' => $this->t('created_date'),
      //'keywords' => $this->t('keywords'),
      'created_by' => $this->t('Author'),
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
   
    $query = $this->select('{tags}', 't');
    $query->join('{contentitem_tag_map}', 'map', 't.id = map.tag_id');
    $joomla_tags =  $query->fields('t', array('title'))
      ->condition('map.content_item_id', $row->getSourceProperty('id'))
      ->execute()
      ->fetchCol();

    if(count($joomla_tags) > 0){
      $row->setSourceProperty('joomla_tags', implode(',', $joomla_tags));
    }else{
      $row->setSourceProperty('joomla_tags', 'stub');
    }

    return parent::prepareRow($row);
    
  }
}

