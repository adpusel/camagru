<?php
/**
 * User: adpusel
 * Date: 11/3/18
 * Time: 15:22
 */

namespace DatabaseTesting\Mappers;


use Core\Auth\ManagePass;
use Core\Database\MySqlDatabase;
use DatabaseTesting\Generic_Tests_DatabaseTestCase;
use DatabaseTesting\ConvertArrayToDBUnitTable;

class MySqlMapperTest extends Generic_Tests_DatabaseTestCase
{
  use ManagePass;

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

  public function testInsert()
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

	MySqlDatabase::query(
	  'INSERT INTO `Users` SET
		email = :email,
		hash = :hash',
	  [
		'email' => 'sab',
		'hash'  => 'pass_3'
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

	MySqlDatabase::query(
	  'UPDATE Users SET
		email = :email,
		hash = :hash
		WHERE id = 2',
	  [
		'email' => 'sab',
		'hash'  => 'toto'
	  ]);

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

	MySqlDatabase::query(
	  'DELETE FROM Users
		WHERE id = 2',
	  []
	);

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

	// deuxieme el
	$this->assertEquals('2', $query[1]->id);
	$this->assertEquals('patrick', $query[1]->email);
	$this->assertEquals('pass_2', $query[1]->hash);
  }


  // TODO : faire un test avec fetch mode class
}