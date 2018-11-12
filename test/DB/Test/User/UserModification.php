<?php
///**
// * User: adpusel
// * Date: 11/12/18
// * Time: 09:57
// */
//
//
//define('ROOT', 'Applications/mappstack-7.1.22-1/apache2/htdocs/42/camagru');
//session_start();
//
//use Core\User\HTML\InfoUserFormBuilder;
//use Core\User\UserController;
//use Core\User\UserEntity;
//
//use Core\User\UserModel;
//use DatabaseTesting\Generic_Tests_DatabaseTestCase;
//use DatabaseTesting\ConvertArrayToDBUnitTable;
//
//
//class UserModification extends Generic_Tests_DatabaseTestCase
//{
//  protected $userController;
//  protected $request;
//  protected $link_inscription;
//  protected $form;
//  protected $page;
//  protected $userEntity;
//  protected $app;
//
//  public function getDataSet()
//  {
//
//
//	MySqlDatabase::getInstance(
//	  __DIR__ . "/../../resources/database.ini");
//
//	// init mes objets
//	$this->app = new Core\App\App("a", "a");
//
//	$this->userController = new UserController($this->app, "a", "a");
//	$this->request = $this->app->getHttpRequest();
//	$this->page = $this->userController->getPage();
//
//	$this->userEntity = new UserEntity([
//	  'email'    => 'adrien@gmail.com',
//	  'password' => 'DDeuauaou888',
//	  'login'    => 'toto'
//	]);
//
//	$this->userEntity
//	  ->generateEmailCheck()
//	  ->generateHash();
//	$this->link_inscription = [
//	  'id'    => 1,
//	  'token' => $this->userEntity->getEmailCheck(),
//	];
//
//	return new ConvertArrayToDBUnitTable(
//	  [
//		'Users' => [
//		  [
//			'email'       => $this->userEntity->email,
//			'hash'        => $this->userEntity->hash,
//			'email_check' => $this->userEntity->getEmailCheck(),
//			'login'       => $this->userEntity->getLogin()
//		  ]
//		],
//	  ]
//	);
//  }
//
//
//
//
//}