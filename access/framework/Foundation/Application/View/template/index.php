<?php

/**
 * @var string $content
 */

use function Framework\url_path;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control Panel</title>
    <link rel="stylesheet" href="<?= url_path('bootstrap/5.3.8.css') ?>">
    <link rel="stylesheet" href="<?= url_path('panel/main.css') ?>">
</head>

<body>
    <div class="container"><?= PHP_EOL . $content ?>
    </div>
    <script src="<?= url_path('bootstrap/5.3.8.bundle.js') ?>"></script>
</body>

</html>