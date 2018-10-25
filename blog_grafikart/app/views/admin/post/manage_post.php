<form method="post">

  <?= $form->input(
	'titre',
	'Titre de l\' article') ?>

  <?= $form->input(
	'contenu',
	'Contenu',
	['type' => 'textarea']) ?>

  <?= $form->select(
	'category_id',
	'Category',
	$category) ?>

    <button class="btn btn-primary">save</button>

</form>

