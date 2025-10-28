<?php

use function Framework\url_for;

ob_start();

?>
<div class="py-3">
    <a href="<?= url_for('panel') ?>">Control Panel</a>
</div>
<?php

$content = ob_get_clean();

require __DIR__ . '/template/index.php';
