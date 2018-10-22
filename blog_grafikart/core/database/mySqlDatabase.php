<?php
/**
 * User: adpusel
 * Date: 18/10/2018
 * Time: 08:39
 */


// permet de me proteger si j'inclus d4
namespace Core\Database;

use \PDO;

/**0
 * Class Database
 *
 * @package App
 */
class mySqlDatabase extends Database
{
  protected $_name;
  protected $_user;
  protected $_host;
  protected $_pass;
  private static $pdo;


  /**
   * Database constructor.
   *
   * @param $name
   * @param $user
   * @param $host
   * @param $pass
   */
  public function __construct($name = "mysql", $user = "tuto",
							  $host = "127.0.0.1", $pass = "pass")
  {
	$this->_name = $name;
	$this->_user = $user;
	$this->_host = $host;
	$this->_pass = $pass;
  }

  public function getPdo(): PDO
  {
	if (self::$pdo === null)
	{
	  self::$pdo = new PDO(
		'mysql:dbname=Blog_grafikart;host=127.0.0.1',
		'tuto',
		'pass');
	  // set les err a visible
	  self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}

	// TODO : rajouter un controle en cas d'err
	return self::$pdo;
  }

  public static function
  query(string $query,
		string $class_name = '',
		bool $one)
  {
	$pdo = self::getPdo()->query($query);

	$pdo->setFetchMode(PDO::FETCH_CLASS, $class_name);

	if ($one !== false)
	  return $pdo->fetch();
	else
	  return $pdo->fetchAll();
  }

  public static function
  prepare(string $statement,
		  array $attributes,
		  string $class_name,
		  bool $one = false
  )
  {
	// je protege ma request sql des injections
	PDO :
	$req = self::getPdo()->prepare($statement);

	// je lance la request
	$req->execute($attributes);

	// je precise ici le fetch dont je vais avoir besoin
	$req->setFetchMode(PDO::FETCH_CLASS, $class_name);

	// je fech que un
	if ($one !== false)
	  return $req->fetch();
	else
	  return $req->fetchAll();
  }


}