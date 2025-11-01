<?php

use function Framework\url_for;

$title = 'Application';

ob_start();

?>
<div class="container">
    <div class="py-3">
        <a href="<?= url_for('panel') ?>">Control Panel</a>
    </div>
</div>
<?php

$body = ob_get_clean();

require __DIR__ . '/template/index.php';
