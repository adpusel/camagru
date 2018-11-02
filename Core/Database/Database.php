<?php
/**
 * User: adpusel
 * Date: 10/31/18
 * Time: 16:23
 */


namespace Core\Database;


/**
 * Class Database est la class parente de toute les db de l'app
 * elle sera instancier par la factory corespondante
 *
 * @package Core\Database
 */
abstract class Database
{
  protected $DB_DSN = null;
  protected $DB_USER = null;
  protected $DB_PASSWORD = null;
  protected static $_instance = null;

}