<?php
/**
 * User: adpusel
 * Date: 10/31/18
 * Time: 08:34
 */


namespace Core\App;


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
   * @param $user
   * @param $router
   * @param $httpRequest
   * @param $httpResponse
   * @param $config
   * @param $name
   */
  public function __construct($user, $router, $httpRequest, $httpResponse,
							  $config, $name)
  {
	$this->user = $user;
	$this->router = $router;
	$this->httpRequest = $httpRequest;
	$this->httpResponse = $httpResponse;
	$this->config = $config;
	$this->name = $name;
  }

  // TODO : faire ici le routeur
  //	il envoit au bon controleur la suite controleur
  // mon routeur dois get les route depuis un fichier separer


  // TODO : le run de l'app
  // donne les


}