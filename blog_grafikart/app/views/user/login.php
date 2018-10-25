<?php if ($error): ?>
    <div class="alert alert-danger"> err log</div>
<?php endif; ?>


<form method="post">

  <?= $form->input(
	'username',
	'Pseudo') ?>

  <?= $form->input(
	'password',
	'Mot de passe',
	['type' => 'password']) ?>

    <button class="btn btn-primary">ok</button>

</form>
