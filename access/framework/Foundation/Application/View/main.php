<?php

use function Framework\url_for;

ob_start();

?>
<div class="d-flex justify-content-between">
    <div class="py-3"><a href="<?= url_for('panel') ?>">Main</a></div>
    <div class="align-self-center"><a href="<?= url_for('panel.logout') ?>">Logout</a></div>
</div>
<?php

$content = ob_get_clean();

require __DIR__ . '/template/index.php';
