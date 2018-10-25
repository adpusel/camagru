<?php
// je ne prends pas en get pour eviter les injections sql
namespace App;

use App;
use Core\HTML\BootstrapForm;

$categoryTable = App::getTable('Category');
if (!empty($_POST))
{
  var_dump($_POST);
    $res = $categoryTable->update($_GET['id'],
	[
	  'titre'   => $_POST['titre'],
	]);
  if ($res)
	var_dump('save ok');
  else
      var_dump($res);
};


// get les category
// get la table
$post = $categoryTable->one($_GET['id']);

// hydrate le form avec le data de $post
$form = new BootstrapForm($post);

?>


<form method="post">

  <?= $form->input(
	'titre',
	'Titre de l\' article') ?>

    <button class="btn btn-primary">save</button>

</form>

