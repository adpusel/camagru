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
  public function getDataSet()
  {
	MySqlDatabase::getInstance(
	  __DIR__ . "/../../resources/database.ini");
	return new ConvertArrayToDBUnitTable(
	  [
		'Users' => [
		  [
			'id'    => 1,
			'email' => 'joe',
			'hash'  => 'pass_1'
		  ],
		  [
			'id'    => 2,
			'email' => 'patrick',
			'hash'  => 'pass_2'
		  ],
		],
	  ]
	);
  }

  public function testFetch()
  {
	$modele = new UserModel();

	$query = $modele->fetchOne(1);

	$this->assertEquals('1', $query->id);
	$this->assertEquals('joe', $query->email);
	$this->assertEquals('pass_1', $query->hash);
  }

  public function testFetchAll()
  {
	$modele = new UserModel();

	$query = $modele->fetchAll();

	// premier el
	$this->assertEquals('1', $query[0]->id);
	$this->assertEquals('joe', $query[0]->email);
	$this->assertEquals('pass_1', $query[0]->hash);

	// deuxieme el
	$this->assertEquals('2', $query[1]->id);
	$this->assertEquals('patrick', $query[1]->email);
	$this->assertEquals('pass_2', $query[1]->hash);
  }

  public function testCreate()
  {
	$newArrayWanted = [
	  'Users' => [
		[
		  'id'    => 1,
		  'email' => 'joe',
		  'hash'  => 'pass_1'
		],
		[
		  'id'    => 2,
		  'email' => 'patrick',
		  'hash'  => 'pass_2'
		],
		[
		  'id'    => 3,
		  'email' => 'sab',
		  'hash'  => 'pass_3'
		],
	  ]
	];

	$modele = new UserModel();
	$modele->create([
	  'email' => 'sab',
	  'hash'  => 'pass_3'
	]);

	// check si j'ai bien ajoute
	$this->assertEquals(
	  3,
	  $this->getConnection()->getRowCount('Users'),
	  'insert fail');

	// permet de get les info de la tables
	$queryTable = $this
	  ->getConnection()
	  ->createQueryTable(
		'Users', 'SELECT id, email, hash FROM Users'
	  );

	$expectedTable =
	  $this->createArrayDataSet($newArrayWanted)->getTable('Users');
	$this->assertTablesEqual($expectedTable, $queryTable);
  }

  public function testModify()
  {
	$newArrayWanted = [
	  'Users' => [
		[
		  'id'    => 1,
		  'email' => 'joe',
		  'hash'  => 'pass_1'
		],
		[
		  'id'    => 2,
		  'email' => 'sab',
		  'hash'  => 'toto'
		],
	  ]
	];

	$modele = new UserModel();
	$modele->modify([
		'id'    => 2,
		'email' => 'sab',
		'hash'  => 'toto']
	);

	// permet de get les info de la tables
	$queryTable = $this
	  ->getConnection()
	  ->createQueryTable(
		'Users', 'SELECT id, email, hash FROM Users'
	  );

	$expectedTable =
	  $this->createArrayDataSet($newArrayWanted)->getTable('Users');
	$this->assertTablesEqual($expectedTable, $queryTable);
  }

  public function testDelete()
  {
	$newArrayWanted = [
	  'Users' => [
		[
		  'id'    => 1,
		  'email' => 'joe',
		  'hash'  => 'pass_1'
		],
	  ]
	];


	$modele = new UserModel();
	$modele->delete(2);

	// permet de get les info de la tables
	$queryTable = $this
	  ->getConnection()
	  ->createQueryTable(
		'Users', 'SELECT id, email, hash FROM Users'
	  );

	// compare la table avec le reste
	$expectedTable =
	  $this->createArrayDataSet($newArrayWanted)->getTable('Users');
	$this->assertTablesEqual($expectedTable, $queryTable);
  }


}