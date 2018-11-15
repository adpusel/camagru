<?php
/**
 * User: adpusel
 * Date: 11/4/18
 * Time: 23:14
 */

use Core\Database\MySqlDatabase;

// permet de charger les config de testage de la db
use Core\User\UserController;
use Core\User\UserEntity;

use Core\User\UserModel;
use DatabaseTesting\Generic_Tests_DatabaseTestCase;
use DatabaseTesting\ConvertArrayToDBUnitTable;

define('ROOT', 'Applications/mappstack-7.1.22-1/apache2/htdocs/42/camagru');
session_start();

class UserInscriptionTest extends Generic_Tests_DatabaseTestCase
{
  protected $userController;
  protected $request;
  protected $link_inscription;
  protected $form;
  protected $page;
  protected $userEntity;
  protected $app;

  const USERS_1 = [
	'email'    => 'adrien@gmail.com',
	'password' => 'DDeuauaou888',
	'login'    => 'toto'
  ];

  const USERS_2 = [
	'email'   => 'test@gmail.com',
	'login'   => 'test',
	'checked' => 1,
  ];


  public function getDataSet()
  {
	MySqlDatabase::getInstance(
	  __DIR__ . "/../../../resources/database.ini");


	$_GET = [];
	$_POST = [];
	$_SESSION = [];
	// init mes objets
	$this->app = new Core\App\App("a", "a");

	$this->userController = new UserController($this->app, "a", "a");
	$this->request = $this->app->getHttpRequest();
	$this->page = $this->userController->getPage();

	$this->userEntity = new UserEntity(
	  self::USERS_1
	);

	$this->userEntity
	  ->generateEmailCheck()
	  ->generateHash();
	$this->link_inscription = [
	  'id'    => 1,
	  'token' => $this->userEntity->getEmailCheck(),
	];

	return new ConvertArrayToDBUnitTable(
	  [
		'Users' => [
		  [
			'email'       => $this->userEntity->email,
			'hash'        => $this->userEntity->hash,
			'email_check' => $this->userEntity->getEmailCheck(),
			'login'       => $this->userEntity->getLogin()
		  ],
		  [
			'email'   => self::USERS_2['email'],
			'hash'    => $this->userEntity->hash,
			'login'   => self::USERS_2['login'],
			'checked' => 1
		  ]
		],
	  ]
	);
  }


  // TODO : dois pouvoir set post et get ...
  private function setDataRequest($method, $dataPost = false,
								  $dataSession = false,
								  $dataGet = false)
  {
	if ($dataPost)
	  $_POST = $dataPost;
	if ($dataGet)
	  $_GET = $dataGet;
	if ($dataSession)
	  $_SESSION = $dataSession;

	$_SERVER['REQUEST_METHOD'] = $method;
  }


  private function _get_form_fill_render(array $d)
  {
	$builderName = 'Core\User\HTML\\' . $d['name'] . 'FormBuilder';

	if ($d['type'] === 'fresh')
	  $form = (new $builderName(new UserEntity()))->build();
	elseif ($d['type'] === 'post')
	  $form = (new $builderName(new UserEntity($_POST)))->build();
	else
	  $form = (new $builderName(new UserEntity($d['type'])))->build();

	if (isset($d['check']))
	  $form->isValid($d['check']);

	return $form->createView();
  }


  public function launch($func, array $d, string $sql = '')
  {
	// set les var de GET POST ...
	$this->setDataRequest($d['method'],
	  isset($d['POST']) ? $d['POST'] : false,
	  isset($d['session']) ? $d['session'] : false,
	  isset($d['GET']) ? $d['GET'] : false);

	// res Controller function
	$this->assertSame($d['expect'],
	  $this->userController->$func($this->request));

	// compare page
	if (isset($d['form']))
	{
	  $this->assertSame(
		$this->_get_form_fill_render($d['form']),
		$this->page->getVars('form'));
	}

	if (isset($d['flash']))
	  $this->assertSame($d['flash'], $this->app->getUser()->getFlash());

	if (isset($d['sessionCheck']))
	  $this->assertSame($d['sessionCheck'], $_SESSION);

	// compare table MySql
	if (isset($d['sql']))
	{
	  $queryTable = $this
		->getConnection()
		->createQueryTable(
		  'Users', $sql
		);

	  $expectedTable =
		$this->createArrayDataSet($d['sql'])->getTable('Users');
	  $this->assertTablesEqual($expectedTable, $queryTable);
	}
  }


  /*------------------------------------*\
	  test de incription
  \*------------------------------------*/

  public function InscriptionProvider()
  {
	return [
	  // get the page
	  [['method' => 'GET', 'expect' => true,
		'form'   => ['name' => 'InfoUser',
					 'type' => 'fresh'],
	   ]],

	  // all good
	  [['method' => 'POST', 'expect' => true,
		'POST'   => [
		  'email'    => 'adrien@prairial.com',
		  'password' => 'DDeuauaou888',
		  'login'    => 'tata'
		],
	   ]],

	  // same email
	  [['method' => 'POST', 'expect' => false,
		'POST'   => [
		  'email'    => self::USERS_1['email'],
		  'password' => 'DDeuauaou888',
		  'login'    => 'aoua'],
		'flash'  => UserModel::EXISTING_EMAIL
	   ]],

	  // same email
	  [['method' => 'POST', 'expect' => false,
		'POST'   => [
		  'email'    => 'aeu@aheu.fu',
		  'password' => 'oeauhaoe999RR',
		  'login'    => self::USERS_1['login']],
		'flash'  => UserModel::EXISTING_LOGIN
	   ]],

	  // bad password
	  [['method' => 'POST', 'expect' => false,
		'POST'   => [
		  'email'    => 'aeu@aheu.fu',
		  'password' => 'oe',
		  'login'    => 'eauaoeu'],
		'form'   => [
		  'name' => 'InfoUser', 'type' => 'post', 'check' => []],
	   ]],

	  // bad email
	  [['method' => 'POST', 'expect' => false,
		'POST'   => [
		  'email'    => 'aeu@ah',
		  'password' => 'oe',
		  'login'    => 'eauaoeu222PPP'],
		'form'   => [
		  'name' => 'InfoUser', 'type' => 'post', 'check' => []],
	   ]],
	];
  }


  /**
   * @dataProvider InscriptionProvider
   */
  public function testInscription(array $d)
  {
	$this->launch('inscription', $d);
  }


  /*------------------------------------*\
	  test link inscription
  \*------------------------------------*/
  public function test_good_check_link()
  {
	// simule le click sur le lien du email
	$_GET = $this->link_inscription;

	// je le donne a la fonction check qui retourn true si link work
	$this->assertSame(true,
	  $this->userController->inscription_check($this->request));

	// je get l'user
	$user = $this->userController->getModel()
	  ->fetchOne($this->request->getData('id'));

	// est il bien valide
	$this->assertSame(true, $user->getChecked());
	$this->assertSame(null, $user->getEmailCheck());
  }


  public function test_bad_check_link()
  {

	// simule le click sur le lien du email
	$_GET = $this->link_inscription;

	// je met un token pouri
	$_GET['token'] = 'auaouaou a uaoeuaohu aoeu ';

	// je le donne a la fonction check qui retourn true si link work
	$this->assertSame(false,
	  $this->userController->inscription_check($this->request));

	// je get l'user
	$user = $this->userController->getModel()
	  ->fetchOne($this->request->getData('id'));

	// check si check est toujours false
	$this->assertSame(false, $user->getChecked());
  }


  public function deleteUserProvadier()
  {
	return [
	  ['GET', ['id' => 1], [], false, 2],
	  ['GET', [], ['id' => 1], false, 2],
	  ['POST', [], ['id' => 1], false, 2],
	  ['POST', ['id' => 1], ['id' => 1], false, 2],
	  ['POST', ['id' => 1], ['id' => 1, 'auth' => true], true, 1],
	];
  }


  /**
   * @dataProvider deleteUserProvadier
   */
  public function test_delete_user($method, $dataSend, $dataSession,
								   $expect, $entry)
  {
	$this->setDataRequest($method, $dataSend, $dataSession);

	$this->assertSame($expect, $this->userController->delete($this->request));
	$this->assertSame($entry,
	  $this->getConnection()->getRowCount('Users'));

  }


  public function LoginUserProvider()
  {
	return
	  [
		//	   get method
		[['method' => 'GET', 'expect' => true,
		  'form'   => ['name' => 'LoginUser',
					   'type' => 'fresh']]],

		// next bad creditial
		[['method' => 'POST', 'expect' => false,
		  'form'   => ['name' => 'LoginUser',
					   'type' => 'fresh'],
		  'flash'  => UserController::BAD_CREDITIAL]],

		[['method' => 'POST', 'expect' => false,
		  'data'   => ['password' => 'aoeuau'],
		  'form'   => ['name' => 'LoginUser', 'check' => false,
					   'type' => 'fresh'],
		  'flash'  => UserController::BAD_CREDITIAL]],

		[['method' => 'POST', 'expect' => false,
		  'data'   => ['password' => 'aoeuau', 'login' => 'auaoeuoeu'],
		  'form'   => ['name' => 'LoginUser', 'check' => false,
					   'type' => 'fresh'],
		  'flash'  => UserController::BAD_CREDITIAL]],

		// not checked
		[['method' => 'POST', 'expect' => false,
		  'data'   => ['password' => self::USERS_1['password'],
					   'login'    => self::USERS_1['login']],
		  'flash'  => UserController::EMAIL_NOT_CONFIRM]],

		// checked
		[['method'  => 'POST', 'expect' => true,
		  'checked' => true,
		  'data'    => ['password' => self::USERS_1['password'],
						'login'    => 'test'],
		  'flash'   => UserController::CONNECTION]],

	  ];
  }


  /**
   * @dataProvider LoginUserProvider
   */
  public function testLoginUser($d)
  {
	$this->setDataRequest($d['method'],
	  isset($d['data']) ? $d['data'] : [],
	  isset($d['session']) ? $d['session'] : []);

	if (isset($d['checked']))
	  MySqlDatabase::query("
			UPDATE Users SET checked = TRUE 
			WHERE id = 2",
		[]);

	// res Controller function
	$this->assertSame($d['expect'],
	  $this->userController->login($this->request));

	// compare table MySql
	if (isset($d['tableSql']))
	  $this->assertSame($d['tableSql'],
		$this->getConnection()->getRowCount('Users'));

	// compare page
	if (isset($d['form']))
	{
	  $this->assertSame(
		$this->_get_form_fill_render($d['form']),
		$this->page->getVars('form'));
	}

	if (isset($d['flash']))
	  $this->assertSame($d['flash'], $this->app->getUser()->getFlash());

	if (isset($d['sessionCheck']))
	  $this->assertSame($d['sessionCheck'], $_SESSION);
  }


  public function ModifyDataUserProvideInfo()
  {
	$mail = 'toto@gmail.com';
	$log = 'naeu';

	$user_test_1 = self::USERS_1;
	unset($user_test_1['password']);

	$badEmail = [
	  'email' => 'lolahaha.com',
	  'login' => self::USERS_1['login']
	];

	$sameEmail = [
	  'email' => self::USERS_2['email'],
	  'login' => self::USERS_1['login']
	];

	$sameLogin = [
	  'email' => self::USERS_1['email'],
	  'login' => self::USERS_2['login']
	];

	$goodNewLogAndEmail = [

	  'email' => $mail,
	  'login' => $log,
	];

	$bdNewGoodUser = ['Users' => [
	  array_merge(['id' => 1], $goodNewLogAndEmail)]];

	return [
	  //	  	  	   get not connected --> true redirection on login
	  [['method' => 'GET', 'expect' => true,
		'form'   => ['name' => 'LoginUser',
					 'type' => 'fresh'],
	   ]],

	  // get connected // fill le frorm avec la bonne entity.
	  [['method'  => 'GET', 'expect' => true,
		'session' => ['auth' => true, 'id' => 1],
		'form'    => ['name' => 'ModifyUser',
					  'type' => $user_test_1],
		'POST'    => $user_test_1


	   ]],

	  // bad mail
	  [['method'  => 'POST', 'expect' => false,
		'session' => ['auth' => true, 'id' => 1],
		'POST'    => $badEmail,
		'form'    => ['name'  => 'ModifyUser',
					  'check' => $badEmail,
					  'type'  => $badEmail
		],
	   ]],

	  // same mail
	  [['method'  => 'POST', 'expect' => false,
		'session' => ['auth' => true, 'id' => 1],
		'POST'    => $sameEmail,
		'form'    => ['name'  => 'ModifyUser',
					  'check' => $sameEmail,
					  'type'  => $sameEmail],
		'flash'   => UserModel::EXISTING_EMAIL
	   ]],

	  //	  // TODO : i didn't test the form, i did up to but not perfect
	  // same login
	  [['method'  => 'POST', 'expect' => false,
		'session' => ['auth' => true, 'id' => 1],
		'POST'    => $sameLogin,
		//		'form'    => ['name'  => 'ModifyUser',
		//					  'check' => $badLogin,
		//					  'type'  => $badLogin],
		'flash'   => UserModel::EXISTING_LOGIN
	   ]],

	  // t5 all good
	  [['method'  => 'POST', 'expect' => true,
		'session' => ['auth' => true, 'id' => 1],
		'POST'    => $goodNewLogAndEmail,
		//		'form'    => ['name'  => 'ModifyUser',
		//					  'check' => $badEmail,
		//					  'type'  => $badEmail],
		'sql'     => $bdNewGoodUser,
	   ]],

	];
  }


  /**
   * @dataProvider ModifyDataUserProvideInfo
   */
  public function testModifyDataUserInfo(array $d)
  {
	$this->launch('modify', $d,
	  'SELECT id, email, login FROM Users WHERE id = 1');
  }


  public function ModifyDataUserProviderPass()
  {

	return [
	  //	   good new mail and pass
	  // TODO : for all the next i need to test the html form founded

	  // bad new pass
	  [['method'  => 'POST', 'expect' => false,
		'session' => ['auth' => true, 'id' => 1],
		'data'    => ['password' => 'aeuaoeu'],
		//		'form'    => [ 'name' => 'ModifyUser',
		//					   'type' => $user_test_1],
	   ]],

	  // bad old pass
	  [['method'  => 'POST', 'expect' => false,
		'session' => ['auth' => true, 'id' => 1],
		'data'    => ['password' => 'aoeu778RRCHT', 'oldpassword' => 'auaou'],
		//		'form'    => [ 'name' => 'ModifyUser',
		//					   'type' => $user_test_1],
	   ]],

	  // good new and old pass
	  [['method'     => 'POST', 'expect' => true,
		'session'    => ['auth' => true, 'id' => 1],
		'POST'       => ['password'    => 'aoeu778RRCHT',
						 'oldpassword' => 'DDeuauaou888'],
		'check_hash' => true
		//		'form'    => [ 'name' => 'ModifyUser',
		//					   'type' => $user_test_1],
	   ]],
	];
  }


  /**
   * @dataProvider ModifyDataUserProviderPass
   */
  public function testModifyDataUserPass(array $d)
  {
	$this->launch('modify', $d,
	  'SELECT id, email, login FROM Users WHERE id = 1');

	if (isset($d['check_hash']))
	{
	  $hash = MySqlDatabase::query('SELECT hash FROM Users WHERE id = 1',
		[])[0]->hash;
	  $this->assertSame(true,
		password_verify($d['POST']['password'], $hash));
	}
  }


  public function resetPasswordProvider()
  {
	return [
	  // get page
	  [['method' => 'GET', 'expect' => true,
		'form'   => ['name' => 'ResetPassUser',
					 'type' => 'fresh'],
	   ]],

	  // wrong email
	  [['method' => 'POST', 'expect' => false,
		'POST'   => ['email' => 'aoeuaeo@eu.cum'],
		'form'   => ['name' => 'ResetPassUser',
					 'type' => ['email' => 'aoeuaeo@eu.cum']],
		'flash'  => UserController::BAD_CREDITIAL
	   ]],

	  // TODO : check ici good redirection
	  // good email
	  [['method' => 'POST', 'expect' => true,
		'POST'   => ['email' => self::USERS_1['email']],
		'email'  => 'ok'
		//		'form'   => ['name' => 'ModifyUser',
		//					 'type' => ['email' => self::USERS_1['email']]],
	   ]],
	];
  }


  /**
   * @dataProvider resetPasswordProvider
   */
  public function testResetPassword(array $d)
  {
	if (isset($d['email']))
	{
	  $beforecheck =
		MySqlDatabase::query('SELECT email_check FROM Users WHERE id = 1',
		  [])[0]->email_check;
	}

	$this->launch('reset_password', $d);

	if (isset($d['email']))
	{
	  $afterCheck =
		MySqlDatabase::query('SELECT email_check FROM Users WHERE id = 1',
		  [])[0]->email_check;
	  $this->assertNotEquals($afterCheck, $beforecheck);
	}
  }


  public function changePasswordProvider()
  {
	return [
	  // TODO : put 404 here
	  // get page no log
	  [['method' => 'GET', 'expect' => false,
	   ]],

	  // get page bad log
	  [['method' => 'GET', 'expect' => false,
		'data'   => [
		  'id'    => 1,
		  'token' => 'auhaonseuhaoiyhb kbuanotukaoeutnoidtni dntidantiu'
		]
	   ]],

	  // get page good log
	  [['method'     => 'GET', 'expect' => true,
		'GET'        => ['id' => 1],
		'good_token' => true,
		'form'       => ['name' => 'ResetPassUser',
						 'type' => 'fresh'],
	   ]],

	  // bad password
	  [['method'     => 'POST', 'expect' => false,
		'GET'        => ['id' => 1],
		'good_token' => true,
		'POST'       => ['password' => 'aoeuaou'],
		'form'       => ['name'  => 'ResetPassUser',
						 'type'  => ['password' => 'aoeuaou'],
						 'check' => []]
	   ]],

	  // TODO : le forme n'est pas check
	  [['method'       => 'POST', 'expect' => true,
		'GET'          => ['id' => 1],
		'good_token'   => true,
		'POST'         => ['password' => 'aoeuaou999>>EE'],
		'checkNewPass' => true
	   ]],

	];
  }


  /**
   * @dataProvider changePasswordProvider
   */
  public function testChangePassword(array $d)
  {
	if (isset($d['good_token'])) // get le link de la database
	{
	  $d['GET']['token'] =
		MySqlDatabase::query('SELECT email_check FROM Users WHERE id = 1',
		  [])[0]->email_check;
	}

	$this->launch('change_password', $d);

	if (isset($d['checkNewPass']))
	{
	  $hash = MySqlDatabase::query('SELECT hash FROM Users WHERE id = 1',
		[])[0]->hash;
	  $this->assertSame(true,
		password_verify($d['POST']['password'], $hash));
	}

  }

}




















