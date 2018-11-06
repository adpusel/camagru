<?php
///**
// * User: adpusel
// * Date: 11/4/18
// * Time: 23:14
// */
//
//use Core\Auth\AuthController;
//use Core\Database\MySqlDatabase;
//use Core\User\UserEntity;
//
//// permet de charger les config de testage de la db
//use DatabaseTesting\Generic_Tests_DatabaseTestCase;
//use DatabaseTesting\ConvertArrayToDBUnitTable;
//
//define('ROOT', 'Applications/mappstack-7.1.22-1/apache2/htdocs/42/camagru');
//
//class DbAuthTest extends Generic_Tests_DatabaseTestCase
//{
//  public function getDataSet()
//  {
//	MySqlDatabase::getInstance(
//	  __DIR__ . "/../../resources/database.ini");
//	return new ConvertArrayToDBUnitTable(
//	  [
//		'Users' => [],
//	  ]
//	);
//  }
//
//  public function testInscriptionNewUser()
//  {
//	MySqlDatabase::getInstance(
//	  __DIR__ . "/../resources/database.ini");
//
//	$authManager = new AuthController();
//
//	$user = new UserEntity([
//	  'email'    => 'adrien@prairial.com',
//	  'password' => 'toto'
//	]);
//
//	// premier test
//	$this->assertSame(true, $authManager->inscription($user));
//
//	// test avec le meme user
//	$this->assertSame(false, $authManager->inscription($user));
//
//	// test bad mdp
//
//	// test email
//
//
//  }
//
//
//
////  // test canger de mdp si besoin
////  public function testChangePassword()
////  {
////	// test change pass
////
////
////  }
//
//
//}