<?php

/**
 * @var array $form
 */

use function Framework\csrf_token;

$title = 'Login form';

ob_start();

?>
<div class="form">
    <form method="post">
        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
        <div class="mb-3">
            <label for="login" class="form-label">Superuser login</label>
            <input type="text" class="form-control" id="login"
                name="login" value="<?= $_POST['login'] ?? '' ?>"><?= $form['login'] ?? '' ?>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="text" class="form-control" id="email"
                name="email" value="<?= $_POST['email'] ?? '' ?>"><?= $form['email'] ?? '' ?>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password"
                name="password" value="<?= $_POST['password'] ?? '' ?>"><?= $form['password'] ?? '' ?>
        </div>
        <div class="mb-3">
            <label for="confirm" class="form-label">Confirm password</label>
            <input type="password" class="form-control" id="confirm"
                name="confirm" value="<?= $_POST['confirm'] ?? '' ?>"><?= $form['confirm'] ?? '' ?>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<?php

$body = ob_get_clean();

require __DIR__ . '/..' . '/template/form.php';
