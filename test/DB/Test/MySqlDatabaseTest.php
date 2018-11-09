<?php
/**
 * User: adpusel
 * Date: 11/3/18
 * Time: 15:22
 */

namespace DatabaseTesting\Test;


use function array_merge;
use function array_pop;
use Core\Database\MySqlDatabase;
use Core\User\ManagePass;
use DatabaseTesting\Generic_Tests_DatabaseTestCase;
use DatabaseTesting\ConvertArrayToDBUnitTable;

class MySqlDatabaseTest extends Generic_Tests_DatabaseTestCase
{
  use ManagePass;

  private $tableUser = [
	'Users' => [
	  [
		'id'    => 1,
		'email' => 'joe',
		'hash'  => 'pass_1',
		'login' => 'roco99'
	  ],
	  [
		'id'    => 2,
		'email' => 'patrick',
		'hash'  => 'pass_2',
		'login' => 'peteteFleur'
	  ]
	]
  ];


  public function getDataSet()
  {
	MySqlDatabase::getInstance(
	  __DIR__ . "/../../resources/database.ini");
	return new ConvertArrayToDBUnitTable($this->tableUser);
  }

  public function testCreate()
  {
	$newUser = [
	  'id'    => 3,
	  'email' => 'sab',
	  'hash'  => 'pass_3',
	  'login' => 'superMonster'
	];

	$this->tableUser['Users'][] = $newUser;

	MySqlDatabase::query(
	  'INSERT INTO `Users` SET
		email = :email,
		hash = :hash,
		login = :login',
	  [
		'email' => $newUser['email'],
		'hash'  => $newUser['hash'],
		'login' => $newUser['login']
	  ]);

	// check si j'ai bien ajoute
	$this->assertEquals(
	  3,
	  $this->getConnection()->getRowCount('Users'),
	  'inserte fail');

	// permet de get les info de la tables
	$queryTable = $this
	  ->getConnection()
	  ->createQueryTable(
		'Users', 'SELECT id, email, hash, login FROM Users'
	  );

	$expectedTable =
	  $this->createArrayDataSet($this->tableUser)->getTable('Users');
	$this->assertTablesEqual($expectedTable, $queryTable);

	array_pop($this->tableUser['Users']);

  }

  public function testModify()
  {
	$user = [
	  'id'    => 2,
	  'email' => 'sab',
	  'hash'  => 'toto',
	  'login' => 'petete'
	];

	$newArrayWanted = [
	  'Users' =>
		[
		  $this->tableUser['Users'][0],
		  $user
		]
	];

	MySqlDatabase::query(
	  'UPDATE Users SET
		email = :email,
		hash = :hash,
		login = :login
		WHERE id = :id',
	  $user
	);

	// permet de get les info de la tables
	$queryTable = $this
	  ->getConnection()
	  ->createQueryTable(
		'Users', 'SELECT id, email, hash, login FROM Users'
	  );

	$expectedTable =
	  $this->createArrayDataSet($newArrayWanted)->getTable('Users');
	$this->assertTablesEqual($expectedTable, $queryTable);
  }

  public function testDelete()
  {
	$newArrayWanted = [
	  'Users' => [
		$this->tableUser['Users'][0]
	  ]
	];

	MySqlDatabase::query(
	  'DELETE FROM Users
		WHERE id = 2',
	  []
	);

	// permet de get les info de la tables
	$queryTable = $this
	  ->getConnection()
	  ->createQueryTable(
		'Users', 'SELECT id, email, hash, login FROM Users'
	  );

	// compare la table avec le reste
	$expectedTable =
	  $this->createArrayDataSet($newArrayWanted)->getTable('Users');
	$this->assertTablesEqual($expectedTable, $queryTable);
  }

  public function testFetch()
  {
	$query = MySqlDatabase::query(
	  'SELECT * FROM Users
		WHERE id = ?',
	  [1],
	  '',
	  true
	);

	$this->assertEquals('1', $query->id);
	$this->assertEquals('joe', $query->email);
	$this->assertEquals('pass_1', $query->hash);
	$this->assertEquals('roco99', $query->login);
  }

  public function testFetchAll()
  {
	$query = MySqlDatabase::query(
	  'SELECT * FROM Users',
	  [],
	  ''
	);

	// premier el
	$this->assertEquals('1', $query[0]->id);
	$this->assertEquals('joe', $query[0]->email);
	$this->assertEquals('pass_1', $query[0]->hash);
	$this->assertEquals('roco99', $query[0]->login);

	// deuxieme el
	$this->assertEquals('2', $query[1]->id);
	$this->assertEquals('patrick', $query[1]->email);
	$this->assertEquals('pass_2', $query[1]->hash);
	$this->assertEquals('peteteFleur', $query[1]->login);
  }
}