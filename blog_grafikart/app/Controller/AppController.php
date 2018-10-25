<?php
/**
 * User: adpusel
 * Date: 25/10/2018
 * Time: 11:22
 */


namespace App\Controller;


use App;
use Core\Controller\Controller;

class AppController extends Controller
{
  protected $_template = 'default';

  public function __construct()
  {
	$this->_viewPath = ROOT . '/app/Views/';
  }

  protected function loadModel(string $model_name)
  {
	$this->$model_name =  App::getTable($model_name);
  }

  public function forbidden( )
  {
	header('HTTP/1.0 403 Forbidden');
	die('acces interdit');
  }

  public function notFound( )
  {
	header('HTTP/ 404 Not Found');
	die('page introuvable');
  }

}