<?php

use Framework\Facade\Path;

use function Framework\url_for;

/**
 * @var Path $path
 */

ob_start()

?>
<p>Path: <?= $path->name ?></p>
<ul class="nav-list">
    <li><a href="<?= url_for('main') ?>">Home</a></li>
</ul>
<?php

$content = ob_get_clean();

require __DIR__ . '/../template/index.php';
