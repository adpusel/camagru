<?php

$category = App::getTable('Category')->all();

?>

<h1> Administrer les cats</h1>

<p>
    <a
            href="?p=category.add"
            class="btn btn-success">Ajouter
    </a>
</p>


<table class="table">
    <thead>
    <tr>
        <td>ID</td>
        <td>Titre</td>
        <td>Action</td>
    </tr>
    </thead>
    <tbody>
	<?php foreach ($category as $item): ?>
        <tr>
            <td><?= $item->id ?></td>
            <td><?= $item->titre ?></td>
            <td>
                <a
                        class="btn btn-primary"
                        href="?p=category.edit&id=<?= $item->id ?>">edit
                </a>

                <form action="?p=category.delete" method="post" style="display: inline">
                    <button
                            class="btn btn-danger ">Supprimer
                    </button>
                    <input
                            type="hidden"
                            name="id"
                            value="<?= $item->id ?>">
                </form>
            </td>
        </tr>
	<?php endforeach; ?>

    </tbody>

</table>

