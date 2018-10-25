<?php
/**
 * User: adpusel
 * Date: 15/10/2018
 * Time: 13:31
 */

define('ROOT', dirname(__DIR__));

require ROOT . '/app/App.php';
App::load();

/*------------------------------------*\
    init les objet
\*------------------------------------*/
// permet de faire le rooting
$page = isset($_GET['p']) ? $_GET['p'] : 'post.index';

$page = explode('.', $page);

if ($page[0] === 'admin')
{
  $controller = '\App\Controller\Admin\\' . ucfirst($page[1]) . 'Controller';
  $action = $page[2];
}
else
{
  $controller = '\App\Controller\\' . ucfirst($page[0]) . 'Controller';
  $action = $page[1];
}

$controller =
$controller = new $controller();

$controller->$action();

