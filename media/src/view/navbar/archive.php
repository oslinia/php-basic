<?php

use Framework\Facade\Path;

use function Framework\url_for;

/**
 * @var Path $path
 */

[$year, $month, $day] = explode(
    '/',
    (new \DateTime)->setTimezone(new \DateTimeZone('UTC'))->format('Y/m/d'),
);

$date = 'year: ' . $path->year;

if (isset($path->day)) {
    $date .= ' month: ' . $path->month . ' day: ' . $path->day;
    $url = url_for('.archive', year: $year, month: $month, day: $day);
} elseif (isset($path->month)) {
    $date .= ' month: ' . $path->month;
    $url = url_for('.archive', year: $year, month: $month);
} else
    $url = url_for('.archive', year: $year);

ob_start();

?>
<p>Path: <?= $date ?></p>
<p>Url : <?= $url ?></p>
<ul>
    <li><a href="<?= url_for('main') ?>">Home</a></li>
</ul>
<?php

$content = ob_get_clean();

require __DIR__ . '/../template/index.php';
