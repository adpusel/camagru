<?php
// je ne prends pas en get pour eviter les injections sql
namespace App;

use App;
use Core\HTML\BootstrapForm;

$catTable = App::getTable('Category');
if (!empty($_POST))
{
  var_dump($_POST);
  $res = $catTable->create(
	[
	  'titre' => $_POST['titre'],
	]);
  if ($res)
  {
	header('Location: admin.php?p=category.edit&id=' .
	  App::getDb()->lastInsertId());
  }
  else
	var_dump($res);
};


// hydrate le form avec le data de $post
$form = new BootstrapForm($_POST);

?>


<form method="post">

  <?= $form->input(
	'titre',
	'Titre de l\' article') ?>

    <button class="btn btn-primary">save</button>

</form>

