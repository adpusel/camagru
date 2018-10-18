<?php
/**
 * User: adpusel
 * Date: 15/10/2018
 * Time: 13:31
 */

namespace App;

// va set l'autoloading
require "../App/Autoloader.php";
Autoloader::register();


/*------------------------------------*\
    init les objet
\*------------------------------------*/
$db = new Database();

// permet de faire le rooting
$p = isset($_GET['p']) ? $_GET['p'] : 'home';


// permet de stocker tout les output pour les reutiliser ensuite
ob_start();
if ($p === 'home')
  include "../pages/home.php";

if ($p === 'single')
  include "../pages/single.php";

$content = ob_get_clean();
include "../pages/templates/default.php";
