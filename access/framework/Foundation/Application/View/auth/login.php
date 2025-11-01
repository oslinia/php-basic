<?php

use function Framework\csrf_token;
use function Framework\url_for;

$title = 'Login form';

ob_start();

?>
<div class="form">
    <div class="py-3 text-end">
        <a href="<?= url_for('main') ?>">Return</a>
    </div>
    <form method="post">
        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username"
                name="username" value="<?= $_POST['username'] ?? '' ?>">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password"
                name="password" value="<?= $_POST['password'] ?? '' ?>">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<?php

$body = ob_get_clean();

require __DIR__ . '/..' . '/template/form.php';
