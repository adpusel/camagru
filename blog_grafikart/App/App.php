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
  const DB_NAME = 'blog';
  // faire la meme chose pour les autres element de la conection
  // je les repasse ensuite au consteur de la base

  private static $database;
  private static $title = 'mon suppert site';

  static function getDb()
  {
	if (self::$database === null)
	  self::$database = new Database(self::DB_NAME);
	return self::$database;
  }

  public static function NotFound( )
  {
	header('HTTP/1.0 404 NOT FOUND');
	header('Location: index.php?p=404');
  }

  /**
   * @return mixed
   */
  public static function getTitle()
  {
	return self::$title;
  }

  /**
   * @param mixed $title
   */
  public static function setTitle($title): void
  {
	self::$title = $title;
  }

}