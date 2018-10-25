<?php
/**
 * User: adpusel
 * Date: 25/10/2018
 * Time: 15:39
 */


namespace App\Controller\Admin;

use App;
use Core\Auth\DBAuth;

class AppController extends App\Controller\AppController
{
  public function __construct()
  {
	parent::__construct();

	// Auth
	$auth = new DBAuth(App::getDb());
	if (!$auth->logged())
	  $this->forbidden();
  }


}