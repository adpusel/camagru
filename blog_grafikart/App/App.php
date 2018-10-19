<?php
/**
 * User: adpusel
 * Date: 19/10/2018
 * Time: 13:05
 */

namespace App;

class App
{
  const DB_NAME = 'blog';
  // faire la meme chose pour les autres element de la conection
  // je les repasse ensuite au consteur de la base

  private static $database;

  static function getDb()
  {
	if (self::$database === null)
	  self::$database = new Database(self::DB_NAME);
	return self::$database;
  }
}