<?php
/**
 * User: adpusel
 * Date: 25/10/2018
 * Time: 10:14
 */

namespace App;

use App;
use Core\HTML\BootstrapForm;

$catTable = App::getTable('Category');
if (!empty($_POST))
{
  var_dump($_POST);
  $res = $catTable->delete($_POST['id']);
  if ($res)
	header('Location: admin.php?p=category.home');
  else
	var_dump($res);
};
