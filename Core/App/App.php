<?php
/**
 * User: adpusel
 * Date: 10/31/18
 * Time: 08:34
 */


namespace Core\App;


use Core\Http\HTTPRequest;
use Core\Http\HTTPResponse;
use Core\Router\Router;

class App
{
  protected $user;
  protected $router;
  protected $httpRequest;
  protected $httpResponse;
  protected $config;
  protected $name;

  /**
   * App constructor.
   *
   * @param $router
   * @param $httpRequest
   * @param $httpResponse
   * @param $config
   * @param $name
   */
  public function __construct($config, $name)
  {
	$this->user = new User($this);
//	$this->router = new Router();
	$this->httpRequest = new HTTPRequest($this);
	$this->httpResponse = new HTTPResponse($this);
	$this->config = $config;
	$this->name = $name;
  }

  /**
   * @return User
   */
  public function getUser(): User
  {
	return $this->user;
  }

  /**
   * @return HTTPRequest
   */
  public function getHttpRequest(): HTTPRequest
  {
	return $this->httpRequest;
  }

  /**
   * @return HTTPResponse
   */
  public function getHttpResponse(): HTTPResponse
  {
	return $this->httpResponse;
  }

  // TODO : faire ici le routeur
  //	il envoit au bon controleur la suite controleur
  // mon routeur dois get les route depuis un fichier separer


  // TODO : le run de l'app
  // donne les


}