<?php
/**
 * User: adpusel
 * Date: 11/6/18
 * Time: 08:52
 */

use Core\Database\MySqlDatabase;
use Core\Entity\UserEntity;
use Core\User\UserModel;
use DatabaseTesting\Generic_Tests_DatabaseTestCase;
use DatabaseTesting\ConvertArrayToDBUnitTable;

/**
 * Class ModelTest
 * les test sont fait sur le model UserModel car j'ai besoin des entity
 */
class ModelTest extends Generic_Tests_DatabaseTestCase
{
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

  private $model;


  public function getDataSet()
  {
	MySqlDatabase::getInstance(
	  __DIR__ . "/../../resources/database.ini");

	$this->model = new UserModel();
	return new ConvertArrayToDBUnitTable($this->tableUser
	);
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

	$this->model->create(
	  [

	    'email' => $newUser['email'],
		'hash'  => $newUser['hash'],
		'login' => $newUser['login']
	  ]
	);

	// check si j'ai bien ajoute
	$this->assertEquals(
	  3,
	  $this->getConnection()->getRowCount('Users'),
	  'insert fail');

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

	$modele = new UserModel();
	$modele->modify($user);

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

	$modele = new UserModel();
	$modele->delete(2);

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
	$modele = new UserModel();

	$query = $modele->fetchOne(1);

	$this->assertEquals('1', $query->id);
	$this->assertEquals('joe', $query->email);
	$this->assertEquals('pass_1', $query->hash);
	$this->assertEquals('roco99', $query->login);
  }

  public function testFetchAll()
  {
	$modele = new UserModel();

	$query = $modele->fetchAll();

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