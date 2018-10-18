<?php
/**
 * User: adpusel
 * Date: 18/10/2018
 * Time: 08:39
 */


// permet de me proteger si j'inclus d4
namespace App;

use \PDO;

class Database
{
  protected $_name;
  protected $_user;
  protected $_host;
  protected $_pass;
  private $_pdo;


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
	if ($this->_pdo === null)
	{
	  $this->_pdo = new PDO(
		'mysql:dbname=Blog_grafikart;host=127.0.0.1',
		$this->_user,
		$this->_pass);// set les err a visible
	  $this->_pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}

	// TODO : rajouter un controle en cas d'err
	return $this->_pdo;
  }

  public function query(string $query): array
  {
	$this->getPdo();
    $pdo_statment = $this->_pdo->query($query);
	return $pdo_statment->fetchAll(\PDO::FETCH_OBJ);
  }
}