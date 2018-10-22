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
$p = isset($_GET['page']) ? $_GET['page'] : 'home';


//// permet de stocker tout les output pour les reutiliser ensuite
ob_start();
if ($p === 'home')
  require ROOT . "/pages/articles/home.php";


//if ($p === 'article')
//  include "../pages/single.php";
//
//if ($p === 'categorie')
//  include "../pages/categorie.php";
//
//if ($p === '404')
//  include "../pages/home.php";
//
$content = ob_get_clean();
require ROOT . "/pages/templates/default.php";
