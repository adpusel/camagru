<?php
/**
 * User: adpusel
 * Date: 20/10/2018
 * Time: 22:50
 */

namespace Core;

class Config
{

  private $settings = array();
  private static $_instance;

  /**
   * @return mixed
   */
  public static function getInstance($file)
  {
	if (self::$_instance === null)
	  self::$_instance = new Config($file);
	return self::$_instance;
  }

  /**
   * Config constructor.
   *
   * @param array $settings
   */
  public function __construct($file)
  {
	$this->settings = require ($file);
  }

  public function get(string $key)
  {
	if (!isset($this->settings[$key]))
	    return null;
	else
	  return $this->settings[$key];
  }


}