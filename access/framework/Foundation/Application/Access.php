<?php

namespace Framework\Foundation\Application;

use Framework\Frame;

use function Framework\salt_decrypt;
use function Framework\salt_encrypt;

class Access extends Frame
{
    private bool $bool = true;

    private function token(string $dirname, string $username, string $filename): void
    {
        [$offset, $salt] = require parent::root('resource', 'user', $dirname, 'token.php');

        $token = md5((microtime(true) - $offset) . $salt);

        setcookie('token', salt_encrypt($username . '.' . $token), path: '/');

        file_put_contents($filename, $token);

        $this->bool = false;
    }

    private function post(string $username, array $users): void
    {
        $verify = function (string $dirname, string $username): void {
            if (
                $_POST['csrf'] === salt_decrypt($_COOKIE['csrf'])
                and
                password_verify(
                    $_POST['password'],
                    require parent::root('resource', 'user', $dirname, 'password.php'),
                )
            ) {
                setcookie('csrf', '', 0, '/');

                $this->token(
                    $dirname,
                    $username,
                    parent::root('resource', 'user', $dirname, 'token'),
                );
            }
        };

        if (str_contains($username, '@')) {
            $users = array_reverse($users);

            !isset($users[$username]) || $verify($username, $users[$username]);
        } else {
            !isset($users[$username]) || $verify($users[$username], $username);
        }
    }

    private function cookie(array $users): void
    {
        [$username, $token] = explode('.', salt_decrypt($_COOKIE['token']));

        if (isset($users[$username])) {
            $dirname = $users[$username];

            if (
                is_file($filename = parent::root('resource', 'user', $dirname, 'token'))
                and
                $GLOBALS['_FW']->env['inaction'] > (time() - filemtime($filename))
                and
                $token === file_get_contents($filename)
            )
                $this->token($dirname, $username, $filename);
        }
    }

    public function __construct()
    {
        $users = require parent::root('resource', 'user', 'users.php');

        (
            'POST' === $_SERVER['REQUEST_METHOD']
            and
            isset($_POST['username'])
            and
            isset($_POST['password'])
        ) ?
            $this->post($_POST['username'], $users)
            :
            !isset($_COOKIE['token']) || $this->cookie($users);
    }

    public function __invoke(): bool
    {
        return $this->bool;
    }
}
