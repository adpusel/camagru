<?php
/**
 * User: adpusel
 * Date: 20/10/2018
 * Time: 22:50
 */

namespace App;

class Config
{

  private $settings = array();
  private static $_instance;

  /**
   * @return mixed
   */
  public static function getInstance()
  {
	if (self::$_instance === null)
	  self::$_instance = new Config();
	return self::$_instance;
  }

  /**
   * Config constructor.
   *
   * @param array $settings
   */
  public function __construct()
  {
	$this->settings = require dirname(__DIR__) . '/config/config.php';
  }

  public function get(string $key)
  {
	if (!isset($this->settings[$key]))
	    return null;
	else
	  return $this->settings[$key];
  }


}