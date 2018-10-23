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
$p = isset($_GET['p']) ? $_GET['p'] : 'home';


//// permet de stocker tout les output pour les reutiliser ensuite
ob_start();


if ($p === 'home')
  require ROOT . "/pages/admin/index.php";

if ($p === 'post.single')
  require ROOT . "/pages/post/single.php";

if ($p === 'post.category')
  require ROOT . "/pages/post/category.php";

if ($p === '404')
  include "../pages/home.php";


//if ($p === 'home')
//  require ROOT . "/pages/post/home.php";
//
//if ($p === 'post.single')
//  require ROOT . "/pages/post/single.php";
//
//if ($p === 'post.category')
//  require ROOT . "/pages/post/category.php";
//
//if ($p === '404')
//  include "../pages/home.php";

$content = ob_get_clean();
require ROOT . "/pages/templates/default.php";
