<?php

namespace DatabaseTesting;

use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;


/**
 * Class Generic_Tests_DatabaseTestCase permet de creer la connection a chaque
 * test avec la function get connection
 * les variables de PDO se trouvent dans le fichier de config des tests
 *
 * @package DatabaseTesting
 */
abstract class Generic_Tests_DatabaseTestCase extends TestCase
{
  use TestCaseTrait;

  // only instantiate pdo once for test clean-up/fixture load
  static private $pdo = null;

  // only instantiate PHPUnit\DbUnit\Database\Connection once per test
  private $conn = null;

  final public function getConnection()
  {
	if ($this->conn === null)
	{
	  if (self::$pdo == null)
	  {
		self::$pdo = new \PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'],
		  $GLOBALS['DB_PASSWD']);
	  }
	  $this->conn =
		$this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
	}

	return $this->conn;
  }
}