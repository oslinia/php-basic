<?php

namespace Framework\Foundation\Application;

use Framework\Frame;

class Access extends Frame
{
    private bool $bool = true;
    private string $token;

    private function encrypt(string $string): string
    {
        return base64_encode(
            (
                $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-128-CBC'))
            ) . hash_hmac(
                'sha256',
                $raw = openssl_encrypt(
                    $string,
                    'AES-128-CBC',
                    $GLOBALS['_FW']->env['salt'],
                    OPENSSL_RAW_DATA,
                    $iv
                ),
                $GLOBALS['_FW']->env['salt'],
                true
            ) . $raw
        );
    }

    private function decrypt(string $string): string
    {
        return openssl_decrypt(
            substr(
                $decode = base64_decode($string),
                ($ivlen = openssl_cipher_iv_length('AES-128-CBC')) + 32
            ),
            'AES-128-CBC',
            $GLOBALS['_FW']->env['salt'],
            OPENSSL_RAW_DATA,
            substr($decode, 0, $ivlen)
        );
    }

    private function token(string $name, string $user, string $filename): void
    {
        [$offset, $salt] = require parent::root('resource', 'user', $user, 'token.php');

        $token = md5((microtime(true) - $offset) . $salt);

        file_put_contents($filename, $token);
        setcookie('token', $this->encrypt($name . '.' . $token), path: '/');

        $this->bool = false;
    }

    private function csrf(): void
    {
        $this->token = md5(microtime() . $GLOBALS['_FW']->env['salt']);

        setcookie('csrf', $this->encrypt($this->token), path: '/');
    }

    private function login(string $user): void
    {
        if (
            $_POST['csrf'] === $this->decrypt($_COOKIE['csrf'])
            and
            password_verify(
                $_POST['password'],
                require parent::root('resource', 'user', $user, 'password.php'),
            )
        ) {
            setcookie('csrf', '', 0, '/');

            $this->token(
                $_POST['name'],
                $user,
                parent::root('resource', 'user', $user, 'token'),
            );
        } else {
            $this->csrf();
        }
    }

    private function auth(array $users): void
    {
        [$name, $token] = explode('.', $this->decrypt($_COOKIE['token']));

        if (isset($users[$name])) {
            $user = $users[$name];

            if (
                is_file($filename = parent::root('resource', 'user', $user, 'token'))
                and
                $GLOBALS['_FW']->env['inaction'] > (time() - filemtime($filename))
                and
                $token === file_get_contents($filename)
            )
                $this->token($name, $user, $filename);
        }
    }

    public function __construct()
    {
        $users = require parent::root('resource', 'user', 'users.php');

        if (
            'POST' === $_SERVER['REQUEST_METHOD']
            and
            isset($_POST['name'])
            and
            isset($_POST['password'])
        )
            isset($users[$_POST['name']]) ? $this->login($users[$_POST['name']]) : $this->csrf();
        else
            isset($_COOKIE['token']) ? $this->auth($users) : $this->csrf();
    }

    public function csrf_token(): array
    {
        return ['csrf' => $this->token];
    }

    public function bool(): bool
    {
        return $this->bool;
    }

    public function logout(): void
    {
        setcookie('token', '', 0, '/');
    }
}
