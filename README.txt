Migrate Joomla content and users along with tags/categories
Please follow the below steps before installing the module:

    Create database setting into the settings.php file. Make sure to add the table prefix of joomla database:

    $databases['migrate']['default'] = array (
      'database'  => 'source-database-name',
      'username'  => 'source-database-username',
      'password'  => 'source-database-password',
      'host'      => 'source-database-server',
      'port'      => '3306',
       'prefix' => 'xyz_',
      'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
      'driver'    => 'mysql',
    );

    joomla articles are mapped to drupal8 article, the default content type created during the drupal installation.
    Create taxonomy named category, the joomla categories will be migrated into this vocabulary.
    Create taxonomy reference field under article content type and map it with category vocabulary with (Create referenced entities if they don't already exist) enabled.
    Joomla tags are mapped with article's default tags field(taxonomy reference)
