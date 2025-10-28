<?php

/**
 * @var string $token
 */

use function Framework\{url_for, url_path};

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
    <div class="container login">
        <div class="py-3 text-end">
            <a href="<?= url_for('main') ?>">Application</a>
        </div>
        <form method="post">
            <input type="hidden" name="token" value="<?= $token ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Username</label>
                <input type="text" class="form-control" id="name"
                    name="name" value="<?= $_POST['name'] ?? '' ?>">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password"
                    name="password" value="<?= $_POST['password'] ?? '' ?>">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>

</html>