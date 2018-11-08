<?php
/**
 * User: adpusel
 * Date: 11/5/18
 * Time: 09:12
 */

namespace Core\User;

use Core\Http\HTTPRequest;
use Core\Mail\PhpMail;
use Core\User\HTML\UserFormBuilder;
use function link;
use const ROOT;

class UserController
{
  private $model;

  /**
   * @return UserModel
   */
  public function getModel(): UserModel
  {
	return $this->model;
  }


  public function __construct()
  {
	// TODO : implementer un validator
	$this->model = new UserModel();
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

  private function sendInscriptionEmail(UserEntity $userEntity)
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

  private function initEntityWithForm(HTTPRequest $request)
  {
	$userEntety = new UserEntity();

	// j'hydrante mon entity si post
	if ($request->method() === 'POST')
	  $userEntety->hydrate($request->getAllPost());
	return $userEntety;
  }

  // get en post les data
  //
  // si je suis en post je test les data
  // si les data sont ok je save
  // je retourne false
  public function inscription(HTTPRequest $request)
  {
	// si pas post
	$userEntity = $this->initEntityWithForm($request);

	// check si l'user n'existe pas deja
	$isNew = $this->model->userExist($userEntity->email);
	if ($isNew !== false)
	  return false;

	$formBuilder = new UserFormBuilder($userEntity);
	$form = $formBuilder->build();
	if ($form->isValid() === false)
	  return false;

	$userEntity
	  ->generateEmailCheck()
	  ->generateHash();

	$res = $this->model->create(
	  [
		'email'       => $userEntity->getEmail(),
		'hash'        => $userEntity->getHash(),
		'email_check' => $userEntity->getEmailCheck()
	  ]
	);

	if ($res === false)
	  return false;
	$userEntity->setId($this->model->lastInsertId());

	// send le mail de verification
	if ($this->sendInscriptionEmail($userEntity) === false)
	  return new \Exception('le mailer ne marche pas');

	return "{$userEntity->getId()}.{$userEntity->getEmailCheck()}";
  }


}

