<?php
/**
 * User: adpusel
 * Date: 10/31/18
 * Time: 17:01
 */


namespace Core\Database;

use PDO;

require 'Database.php';


/**
 * Class MySqlDatabase
 *
 * @package Core\Database
 */
class MySqlDatabase extends Database
{
  private static $Pdo;

  /**
   * create the singleton
   *
   * @param string $path_file_config
   *
   * @return MySqlDatabase
   * @throws \Exception si file de config manquant ou incomplet
   */
  public static function getInstance(string $path_file_config): MySqlDatabase
  {
	if (self::$_instance === null)
	{
	  self::$_instance = new MySqlDatabase($path_file_config);
	}
	return self::$_instance;
  }

  /**
   * MySqlDatabase constructor.
   *
   * @param string $path_file_config
   *
   * @throws \Exception
   */
  public function __construct(string $path_file_config)
  {
	try
	{
	  /** @noinspection PhpIncludeInspection */
	  $parsed_file = parse_ini_file($path_file_config);

	  self::$Pdo = new PDO(
		$parsed_file['DB_DSN'],
		$parsed_file['DB_USER'],
		$parsed_file['DB_PASSWORD']
	  );

	  self::$Pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	} catch (\Exception $e)
	{
	  throw new \InvalidArgumentException("file de config DB corumpu");
	  exit(-1);
	}
  }

  public static function prepare(
	string $statement,
	array $attributes,
	$class_name,
	bool $one = false
  )
  {
	// je protege ma request sql des injections
	PDO :
	$req = self::getPdo()->prepare($statement);

	// je lance la request
	$res = $req->execute($attributes);

	// je ne peux pas fetch ces request
	if (
	  strpos($statement, 'UPDATE') ||
	  strpos($statement, 'INSERT') ||
	  strpos($statement, 'DELETE')
	)
	{
	  return $res;
	}

	// TODO : ne devrais jamais arriver a delete si besoin
	if ($class_name !== null)
	  $req->setFetchMode(PDO::FETCH_CLASS, $class_name);
	else
	  $req->setFetchMode(PDO::FETCH_OBJ);

	// je fech que un
	if ($one !== false)
	  return $req->fetch();
	else
	  return $req->fetchAll();
  }

  // get l'id de la derniere intance mis en db
  public static function  lastInsertId()
  {
	return self::lastInsertId();
  }

}