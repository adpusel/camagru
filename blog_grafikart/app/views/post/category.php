<?php
/**
 * User: adpusel
 * Date: 20/10/2018
 * Time: 15:36
 */

namespace App;

use App;

?>

<div class="row">
    <div class="col-sm-8">
        <ul>
		  <?php foreach ($articles as $article): ?>
                <?php var_dump($article); ?>
              <h2>
                  <a href="<?= $article->url ?>"><?= $article->titre ?></a>
              </h2>

              <p><?= $article->extrait ?></p>

		  <?php endforeach; ?>
        </ul>
    </div>

    <div class="col-sm-4">
        <ul>
		  <?php foreach ($cats as $cat): ?>
              <li>
                  <a href="<?= $cat->url ?>">
					<?= $cat->titre ?>
                  </a>
              </li>

		  <?php endforeach; ?>
        </ul>

    </div>

</div>



