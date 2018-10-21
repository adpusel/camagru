<?php
/**
 * User: adpusel
 * Date: 19/10/2018
 * Time: 13:05
 */

namespace App;
 // cette class sert a tous faire
class App
{
  private static $_instance;

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

}