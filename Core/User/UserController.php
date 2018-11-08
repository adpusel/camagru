<?php
/**
 * User: adpusel
 * Date: 11/5/18
 * Time: 09:12
 */

namespace Core\User;

use Core\App\App;
use Core\Controller\Controller;
use Core\Http\HTTPRequest;
use Core\Mail\PhpMail;
use Core\User\HTML\UserFormBuilder;
use const ROOT;

class UserController extends Controller
{

  /**
   * @return UserModel
   */
  public function getModel(): UserModel
  {
	return $this->model;
  }


  public function __construct(App $app)
  {
	parent::__construct($app);
	$this->model = new UserModel();
  }

  private function _initEntityWithForm(HTTPRequest $request)
  {
	$userEntety = new UserEntity();

	// j'hydrante mon entity si post
	if ($request->method() === 'POST')
	  $userEntety->hydrate($request->getAllPost());
	return $userEntety;
  }

  private function _generateLink(UserEntity $userEntity, $action)
  {
	$href =
	  ROOT .
	  '/User' . '.' . $action . '?' .
	  'id=' . $userEntity->getId() .
	  '&token=' . $userEntity->getEmailCheck();
	return "<a href='$href'> CLICK </a>";
  }

  private function _sendInscriptionEmail(UserEntity $userEntity)
  {
	$mailer = new PhpMail(
	  $userEntity->getEmail(),
	  'Camagru',
	  $this->_generateLink($userEntity, 'inscription_check'),
	  'Confirm inscription'
	);
	return $mailer->sendEmail();
  }

  public function inscription_check(HTTPRequest $request)
  {
	$user = $this->model->fetchOne($request->getData('id'));

	// TODO : redirect si pas de user
	if ($user->sameCheck($request->getData('token')))
	{
	  $this->model->modify([
		'id'       => $user->id,
		'is_check' => true
	  ]);
	  return true;
	}
	return false;
  }

  public function inscription(HTTPRequest $request)
  {
	// init user et l'hydrate si post
	$userEntity = $this->_initEntityWithForm($request);

	// build le form
	$formBuilder = new UserFormBuilder($userEntity);
	$form = $formBuilder->build();

	// si get je stop et retourn un form tout beau
    if ($request->method() === 'GET')
	{
	  $view = $form->createView();
	  return $view;
	}

	// check si l'user n'existe pas deja je fais un message flash
	// TODO : le set dans mon err
	$isNew = $this->model->userExist($userEntity->email);
	if ($isNew !== false)
	  return false;

	// TODO : return le form
	if ($form->isValid() === false)
	  return $form->createView();

	$userEntity
	  ->generateEmailCheck()
	  ->generateHash();

	$this->model->create(
	  [
		'email'       => $userEntity->getEmail(),
		'hash'        => $userEntity->getHash(),
		'email_check' => $userEntity->getEmailCheck()
	  ]
	);

	$userEntity->setId($this->model->lastInsertId());
	// send le mail de verification
	if ($this->_sendInscriptionEmail($userEntity) === false)
	  return new \Exception('le mailer ne marche pas');

	// ici je dois redirider
	return "{$userEntity->getId()}.{$userEntity->getEmailCheck()}";
  }

  public function delete(HTTPRequest $request)
  {
	$this->model->delete($request->postData(['id']));
  }

}

