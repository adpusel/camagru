<?php

namespace App;

use App;
use App\Table\Categorie;
?>

<div class="row">
    <div class="col-sm-8">
        <ul>
		  <?php foreach (App::getTable('Post')->last() as $article): ?>

              <h2>
                  <a href="<?= $article->url ?>"><?= $article->titre ?></a>
              </h2>
              <p><?= $article->extrait ?></p>
              <p> <?= $article->categorie ?></p>


		  <?php endforeach; ?>
        </ul>
    </div>

    <div class="col-sm-4">
        <ul>
		  <?php foreach (App::getTable('Category')->all() as $cat): ?>
              <li>
                  <a href="<?= $cat->url ?>">
                      <?= $cat->titre ?>
                  </a>
              </li>

		  <?php endforeach; ?>
        </ul>

    </div>


</div>

