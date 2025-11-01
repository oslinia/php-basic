<?php

namespace Framework\Foundation\Application;

use Framework\Frame;

use function Framework\salt_encrypt;
use function Framework\url_for;

class User extends Frame
{
    public bool $superuser;
    public bool $installed;

    private array $form = array();

    private function not_empty(): bool
    {
        foreach (['login', 'email', 'password', 'confirm'] as $name)
            if ('' === $_POST[$name])
                return false;

        return true;
    }

    private function warning(string $name, string $text): void
    {
        $this->form[$name] = '<p class="text-danger">' . $text . '</p>';
    }

    private function clear_post(string $name, string $text): void
    {
        $_POST[$name] = '';

        $this->warning($name, $text);
    }

    private function valid_login(string $login, array $users): void
    {
        if (false == preg_match('/^[A-Za-z0-9_]+$/', $login))
            $this->clear_post('login', 'Valid characters are "A-Za-z0-9" and the "_" symbol');

        elseif (isset($users[$login]))
            $this->clear_post('login', 'Such login already exists');
    }

    private function valid_email(string $email, string $dirname): void
    {
        if (false == filter_var($email, FILTER_VALIDATE_EMAIL))
            $this->clear_post('email', 'The email address "' . $email . '" is incorrect');

        elseif (is_dir($dirname . $email))
            $this->clear_post('email', 'Such email address already exists');
    }

    private function valid_password(): void
    {
        $clear_post = function (string $name, string $text): void {
            [$_POST['password'], $_POST['confirm']] = ['', ''];

            $this->warning($name, $text);
        };

        $length = strlen($_POST['password']);

        if (4 > $length or $length > 32)
            $clear_post('password', 'The password length must be between 4 and 32 characters');

        elseif ($_POST['password'] !== $_POST['confirm'])
            $clear_post('confirm', 'The password and confirmation do not match');
    }

    private function mkdir(string $dirname): string
    {
        mkdir($dirname);

        return $dirname . DIRECTORY_SEPARATOR;
    }

    private function write(string $dirname, string $login, string $email): array
    {
        file_put_contents(
            $dirname . 'users.php',
            '<?php return ' . var_export([0 => $login, $login => $email], true) . ';',
        );

        $dirname = $this->mkdir($dirname . $email);

        file_put_contents(
            $dirname . 'password.php',
            '<?php return \'' . password_hash($_POST['password'], PASSWORD_DEFAULT) . '\';',
        );

        [$offset, $salt] = [100 + mt_rand() / mt_getrandmax() * (1000000 - 100), uniqid('', true)];

        file_put_contents(
            $dirname . 'token.php',
            '<?php return ' . var_export([$offset, $salt], true) . ';',
        );

        return [$offset, $salt, $dirname . 'token'];
    }

    private function install(string $dirname, string $login): bool
    {
        if (array() === $this->form) {
            [$offset, $salt, $filename] = $this->write($dirname, $login, $_POST['email']);

            setcookie('csrf', '', 0, '/');

            $token = md5((microtime(true) - $offset) . $salt);

            setcookie('token', salt_encrypt($login . '.' . $token), path: '/');

            file_put_contents($filename, $token);

            return true;
        }

        return false;
    }

    public function superuser(): bool
    {
        $users = require parent::root('resource', 'user', 'users.php');

        if (isset($users[0]))
            return false;

        if (
            'POST' === $_SERVER['REQUEST_METHOD']
            and
            $this->not_empty()
        ) {
            $dirname = parent::root('resource', 'user', '');

            $this->valid_login($_POST['login'], $users);
            $this->valid_email($_POST['email'], $dirname);
            $this->valid_password();

            $this->installed = $this->install($dirname, $_POST['login']);
        } else {
            $this->installed = false;
        }

        return true;
    }

    public function __construct(bool $superuser = false)
    {
        if ($superuser)
            $this->superuser = $this->superuser();
    }

    public function redirect(): string
    {
        return url_for('panel');
    }

    public function form(): array
    {
        return ['form' => $this->form];
    }
}
