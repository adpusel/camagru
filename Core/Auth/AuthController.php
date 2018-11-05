<?php
/**
 * User: adpusel
 * Date: 11/5/18
 * Time: 09:12
 */

namespace Core\Auth;

use Core\Database\MySqlDatabase;
use Core\Http\HTTPRequest;
use Core\Mail\PhpMail;
use Core\User;
use Core\User\UserEntity;

class AuthController
{
  /**
   * @var PhpMail
   */
  private $mailer;

  use ManagePass;

  public function __construct()
  {
	// je fais un parceur qui valide et met dans une entite mes datas
	// take http request and put in entity user
	// TODO : implementer un validator
  }

  private function _generateLink(UserEntity $userEntity, $action)
  {
	$href =
	  ROOT .
	  'User' . '.' . $action . '?' .
	  'id=' . $userEntity->getId() .
	  '&token=' . $userEntity->getEmailCheck();
	return "<a href='$href'> CLICK </a>";

  }

  private function sendInscriptionEmail(UserEntity $userEntity)
  {
	$mailer = new PhpMail(
	  $userEntity->getEmail(),
	  'Camagru',
	  $this->_generateLink($userEntity, 'check_inscription'),
	  'Confirm inscription'
	);
	return $mailer->sendEmail();
  }

  // si la method est en post, je creer un entity et je la lance a save ?
  // he hit by checked entity
  public function inscription(UserEntity $userEntity)
  {
	$isNew =
	  MySqlDatabase::query('SELECT email FROM Users WHERE email = ?',
		[
		  $userEntity->getEmail()
		], '', true);

	if ($isNew !== false)
	  return false;
	var_dump($isNew);


	// to this point the user is correct
	$userEntity->setHash(
	  $this->_hashPass($userEntity->getPassword())
	);

	// genere chaine de 100 char ramdom passable par url
	$userEntity->setEmailCheck(
	  urlencode(bin2hex(random_bytes(50))
	  ));

	$res = MySqlDatabase::query(
	  /** @lang MySQL */
	  'INSERT INTO Users SET 
                    email = :email,
                    hash = :hash,
                    email_check = :email_check',
	  [
	    'email' => $userEntity->getEmail(),
		'hash' => $userEntity->getHash(),
		'email_check' => $userEntity->getEmailCheck()
	  ]
	);

	// id de l'user nouvellement creer
	$userEntity->setId(MySqlDatabase::lastInsertId());

	// send le mail de verification
	if ($this->sendInscriptionEmail($userEntity) === false)
	  return new \Exception('le mailer ne marche pas');

	return true;

  }

}

