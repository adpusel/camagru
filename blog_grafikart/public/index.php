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
// permet de faire le rooting
$p = isset($_GET['p']) ? $_GET['p'] : 'home';


// permet de stocker tout les output pour les reutiliser ensuite
ob_start();
if ($p === 'home')
  include "../pages/home.php";


if ($p === 'article')
  include "../pages/single.php";

if ($p === 'categorie')
  include "../pages/categorie.php";

if ($p === '404')
  include "../pages/home.php";

$content = ob_get_clean();
include "../pages/templates/default.php";
