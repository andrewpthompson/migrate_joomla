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
      ->fields('d', ['id', 'title', 'catid', 'alias', 'introtext', 'fulltext', 'created', 'created_by', 'state', 'publish_down', 'attribs']);
     
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
      //'fulltext' => $this->t('Page Body'),
      'body' => $this->t('Article Body'),
      'summary' => $this->t('Article Summary'),
      // 'city' => $this->t('City'),
      // 'state' => $this->t('State'),
      // 'site_url' => $this->t('Site URL'),
      // 'site_name' => $this->t('Site Name'),
      'created' => $this->t('created_date'),
      //'keywords' => $this->t('keywords'),
      'created_by' => $this->t('Author'),
      'state' => $this->t('Published state'),
      //'publish_down'
      //'attribs'
      //
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
    }
    
    // Get the article's 'show_intro' publishing option, which is JSON-encoded
    // in the 'attribs' column. Assuming the global default setting is to show
    // article intro, then if show_intro is "0" ("Show Intro Text" is set to
    // "Hide" for the article) then the body shall consist of only the fulltext
    // field. Otherwise concatenate introtext and fulltext.
    $attribs = json_decode($row->getSourceProperty('attribs'));
    if ($attribs->show_intro == '0') {
      $row->setSourceProperty('body', $row->getSourceProperty('fulltext'));
    } else {
      $row->setSourceProperty('body', $row->getSourceProperty('introtext') . $row->getSourceProperty('fulltext'));      
    }
    
    // Get the summary, which will be in introtext but only if fulltext is not
    // empty. (If "Read More" is not used then all text will be in introtext and
    // the summary can be left empty.)
    if (!empty($row->getSourceProperty('fulltext'))) {
      $row->setSourceProperty('summary', $row->getSourceProperty('introtext'));
    }

    return parent::prepareRow($row);
    
  }
}

