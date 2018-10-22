<?php
/**
 * User: adpusel
 * Date: 19/10/2018
 * Time: 13:05
 */

// cette class sert a tous faire
use Core\Database\mySqlDatabase;
use Core\Config;

class App
{
  private static $_instance = null;
  private static $_db_instance = null;

  /**
   * @return mixed
   */
  public static function getInstance()
  {
	if (self::$_instance === null)
	{
	  self::$_instance = new App();
	}
	return self::$_instance;
  }

  public static function load()
  {
	session_start();

	require ROOT . '/app/Autoloader.php';
	App\Autoloader::register();

	require ROOT . '/core/Autoloader.php';
	Core\Autoloader::register();
  }

  public static function getTable(string $name)
  {
	$class_name = 'App\\Table\\' . ucfirst($name) . 'Table';
	return new $class_name(self::getDb());
  }

  public static function getDb()
  {
	 Config::getInstance(ROOT. '/config/config.php' );

	// la je ne fais pas bien, je devrai passer les data, mais j'ai deja tout
	// fais en default
	if (self::$_db_instance === null)
	  self::$_db_instance = new mySqlDatabase();

	return self::$_db_instance;
  }
}