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

  const USERS = [
	'email'    => 'adrien@gmail.com',
	'password' => 'DDeuauaou888',
	'login'    => 'toto'
  ];


  public function getDataSet()
  {
	MySqlDatabase::getInstance(
	  __DIR__ . "/../../../resources/database.ini");

	// init mes objets
	$this->app = new Core\App\App("a", "a");

	$this->userController = new UserController($this->app, "a", "a");
	$this->request = $this->app->getHttpRequest();
	$this->page = $this->userController->getPage();

	$this->userEntity = new UserEntity(
	  self::USERS
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
			'email'   => 'test@gmail.com',
			'hash'    => $this->userEntity->hash,
			'login'   => 'test',
			'checked' => 1
		  ]
		],
	  ]
	);
  }


  private function setDataRequest($method, $data = [], $dataSession = [])
  {
	if ($method === 'POST')
	  $_POST = $data;
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
	  $form->isValid();
	return $form->createView();
  }


  public function testGetIncriptionPage()
  {
	$this->setDataRequest('GET');

	// new form str
	$formStr = $this->_get_form_fill_render([
	  'name' => 'InfoUser', 'type' => 'fresh']);

	$this->assertSame(
	  true,
	  $this
		->userController
		->inscription($this->request)
	);

	$this->assertSame(
	  $formStr,
	  $this->page->getVars('form')
	);
  }


  public function testInscriptionNewUser()
  {
	$this->setDataRequest('POST', [
	  'email'    => 'adrien@prairial.com',
	  'password' => 'DDeuauaou888',
	  'login'    => 'tata'
	]);

	// il garde le lien de confimation est dans $link_inscription
	$this->assertInternalType('string',
	  $this->userController->inscription($this->request));

  }


  // TODO : need testing if the form is filled with last tapping
  public function testInscriptionExisting()
  {
	// test same mail
	$this->setDataRequest('POST', [
	  'email'    => $this->userEntity->email,
	  'password' => 'DDeuauaou888',
	  'login'    => 'auau'
	]);

	$this->assertSame(false,
	  $this->userController->inscription($this->request));

	$this->assertSame(UserModel::EXISTING_EMAIL,
	  $this->app->getUser()->getFlash());

	// test same login
	$this->setDataRequest('POST', [
	  'email'    => 'bob@ema.com',
	  'password' => 'DDeuauaou888',
	  'login'    => $this->userEntity->login
	]);

	$this->assertSame(false,
	  $this->userController->inscription($this->request));

	$this->assertSame(UserModel::EXISTING_LOGIN,
	  $this->app->getUser()->getFlash());

  }


  public function test_inscription_new_user_bad_password()
  {
	$this->setDataRequest('POST', [
	  'email'    => 'naa@aeu.com',
	  'password' => 'aoeua'
	]);

	$this->assertSame(
	  false,
	  $this->userController->inscription($this->request)
	);
	$this->assertSame(
	  $this->_get_form_fill_render([
		'name' => 'InfoUser', 'type' => 'post', 'check' => true]),
	  $this->page->getVars('form')
	);
  }


  public function test_inscription_new_user_bad_email()
  {
	$this->setDataRequest('POST', [
	  'email'    => 'naa@aeucom',
	  'password' => 'aoeuauGGGHH009a',
	  'login'    => 'al'
	]);

	$this->assertSame(
	  false,
	  $this->userController->inscription($this->request)
	);

	$this->assertSame(
	  $this->_get_form_fill_render([
		'name' => 'InfoUser', 'type' => 'post', 'check' => true]),
	  $this->page->getVars('form')
	);
  }


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
		  'form'   => ['name' => 'LoginUser', 'check' => false,
					   'type' => 'fresh']]],

		// next bad creditial
		[['method' => 'POST', 'expect' => false,
		  'form'   => ['name' => 'LoginUser', 'check' => false,
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
		  'data'   => ['password' => self::USERS['password'],
					   'login'    => self::USERS['login']],
		  'flash'  => UserController::EMAIL_NOT_CONFIRM]],

		// checked
		[['method'  => 'POST', 'expect' => true,
		  'checked' => true,
		  'data'    => ['password' => self::USERS['password'],
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


  public function ModifyDataUserProvider()
  {
	$user_test_1 = self::USERS;
	unset($user_test_1['password']);

	$dataEmail = [
	  'email'    => 'lola@haha.com',
	];

	return [
	  //	   get not connected --> true redirection on login
	  [['method' => 'GET', 'expect' => true,
		'form'   => ['check' => false, 'name' => 'LoginUser',
					 'type'  => 'fresh'],
	   ]],

	  // get connected // fill le frorm avec la bonne entity.
	  [['method'  => 'GET', 'expect' => true,
		'session' => ['auth' => true, 'id' => 1],
		'form'    => ['name' => 'InfoUser',
					  'type' => $user_test_1]
	   ]],

	  // test modification avec test de la db
	  [['method'  => 'POST', 'expect' => true,
		'session' => ['auth' => true, 'id' => 1],
		'data'    => $dataEmail,
		'form'    => ['name'  => 'InfoUser',
					  'check' => false, 'type' => 'fresh'],
//		'sql'     => $data_t2
	   ]],
	];
  }


  /**
   * @dataProvider ModifyDataUserProvider
   */
  public function testModifyDataUser($d)
  {
	$this->setDataRequest($d['method'],
	  isset($d['data']) ? $d['data'] : [],
	  isset($d['session']) ? $d['session'] : []);

	// res Controller function
	$this->assertSame($d['expect'],
	  $this->userController->modify($this->request));

	// compare table MySql
	if (isset($d['sql']))
	  $this->assertSame($d['sql'],
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


}




















