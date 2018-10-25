<h1> Administrer les articles</h1>

<p>
    <a
            href="?p=admin.post.add"
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
	<?php foreach ($posts as $item): ?>
        <tr>
            <td><?= $item->id ?></td>
            <td><?= $item->titre ?></td>
            <td>
                <a
                        class="btn btn-primary"
                        href="?p=admin.post.edit&id=<?= $item->id ?>">edit
                </a>

                <form action="?p=admin.post.delete" method="post" style="display: inline">
                    <button
                            class="btn btn-danger "
                            href="?p=admin.post.&id=<?= $item->id ?>">Supprimer
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

