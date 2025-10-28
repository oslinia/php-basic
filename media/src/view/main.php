<?php

use function Framework\url_for;

[$year, $month, $day] = explode(
    '/',
    (new \DateTime)->setTimezone(new \DateTimeZone('UTC'))->format('Y/m/d'),
);

ob_start();

?>
<p>Home</p>
<ul class="nav-list">
    <li><a href="<?= url_for('.media', name: 'css/style.css') ?>" target="_blank">Media File</a></li>
    <li><a href="<?= url_for('.redirect', name: 'page') ?>">Redirect</a></li>
    <li><a href="<?= url_for('page', name: 'name') ?>">Page</a></li>
    <li><a href="<?= url_for('.archive', year: $year) ?>">Archive year</a></li>
    <li><a href="<?= url_for('.archive', year: $year, month: $month) ?>">Archive month</a></li>
    <li><a href="<?= url_for('.archive', year: $year, month: $month, day: $day) ?>">Archive day</a></li>
</ul>
<?php

$content = ob_get_clean();

require __DIR__ . '/template/index.php';
