<?php
/**
 * User: adpusel
 * Date: 19/10/2018
 * Time: 13:05
 */

// cette class sert a tous faire

namespace Event;

class App
{
  private static $_instance = null;

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

	require 'Autoloader.php';
	Autoloader::register();

  }

}