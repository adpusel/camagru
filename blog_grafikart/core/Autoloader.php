<?php
/**
 * User: adpusel
 * Date: 15/10/2018
 * Time: 11:15
 */

namespace Core;

class Autoloader
{


  // static pour la call n'importe ou,
  // __CLASS__ ==> le nom de la class
  // spl ... permet de set la function static qui va include les files
  // je peux comme ca definir plusieur fonction pour load de differente maniere
  // permet de gere les conflit car tout est enfermer dans la class
  public static function register()
  {
	spl_autoload_register(array(__CLASS__, "autoload"));
  }

  static function autoload($class)
  {
	if (strpos($class, __NAMESPACE__ . '\\') === 0)
	{
	  $class = str_replace(__NAMESPACE__ . '\\', '', $class);
	  $class = str_replace('\\', '/', $class);
	  $class = __DIR__ . "/$class" . ".php";


	  require $class;
	}
  }
}