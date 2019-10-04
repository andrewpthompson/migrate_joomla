<?php
namespace Drupal\migrate_joomla\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Source plugin for the content.
 *
 * @MigrateSource(
 *   id = "category"
 * )
 */
class Category extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('{categories}', 'c')
      ->fields('c', ['id', 'title', 'parent_id', 'alias', 'description']);     
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'id' => [
        'type' => 'integer',
        'alias' => 'c',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'id' => $this->t('Category ID'),
      'title' => $this->t('Category Title'),
      'parent_id' => $this->t('Category parent'),
      'alias' => $this->t('Category alias'),
      'description' => $this->t('Description'),      
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
   // print_r($row);die;
    // Perform extra pre-processing for keywords terms, if needed.
    return parent::prepareRow($row);
  }
}
