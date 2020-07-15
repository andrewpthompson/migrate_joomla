<?php
namespace Drupal\migrate_joomla\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Source plugin for the users.
 *
 * @MigrateSource(
 *   id = "users"
 * )
 */
class Users extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('{users}', 'u')
      ->fields('u', ['id', 'username', 'password', 'email', 'registerDate', 'block']);
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'id' => [
        'type' => 'integer',
        'alias' => 'u',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'id' => $this->t('User ID'),
      'username' => $this->t('User Name'),
      'password' => $this->t('Password'),
      'email' => $this->t('Email'),
      'registerDate' => $this->t('Created'),
      'block' => $this->t('Blocked'),
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    //print_r($row);die;
    // Perform extra pre-processing for keywords terms, if needed.
    return parent::prepareRow($row);
  }
}
