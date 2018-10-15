<?php
/**
 * User: adpusel
 * Date: 15/10/2018
 * Time: 11:15
 */


class Autoloader
{


  // static pour la call n'importe ou,
  // __CLASS__ ==> le nom de la class
  // spl ... permet de set la function static qui va include les files
  // je peux comme ca definir plusieur fonction pour load de differente maniere
  // permet de gere les conflit car tout est enfermer dans la class
  public static function register(): void
  {
	spl_autoload_register(array(__CLASS__, "autoload"));
  }
  
  static function autoload($class_name)
  {
	require 'class/' . $class_name . ".class.php";
  }

}