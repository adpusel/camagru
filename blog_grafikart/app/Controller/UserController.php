<?php
/**
 * User: adpusel
 * Date: 25/10/2018
 * Time: 16:16
 */


namespace App\Controller;

use App;
use Core\Auth\DBAuth;
use Core\HTML\BootstrapForm;


class UserController extends AppController
{
  public function login()
  {
	$error = false;
	if (!empty($_POST))
	{
	  $auth = new DBAuth(App::getDb());
	  $connect = $auth->login(
		$_POST['username'],
		$_POST['password']
	  );
	  if ($connect)
		header('Location: index.php?p=admin.post.index');
	  else
		$error = true;
	}

	$form = new BootstrapForm($_POST);
	$this->render('user.login', compact('form', 'error'));
  }
}