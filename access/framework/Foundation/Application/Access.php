<?php

namespace Framework\Foundation\Application;

use Framework\Frame;

class Access extends Frame
{
    public bool $bool = true;
    public string $token;

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

    private function post(array $users): void
    {
        if (isset($users[$_POST['name']])) {
            $user = $users[$_POST['name']];

            if (
                $_POST['token'] === $this->decrypt($_COOKIE['login'])
                and
                password_verify(
                    $_POST['password'],
                    require parent::root('resource', 'user', $user, 'password.php'),
                )
            ) {
                setcookie('login', '', 0, '/');

                $this->token(
                    $_POST['name'],
                    $user,
                    parent::root('resource', 'user', $user, 'token'),
                );
            }
        }
    }

    private function auth(array $users): void
    {
        if (isset($_COOKIE['token'])) {
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
        } else {
            $this->token = md5(microtime() . $GLOBALS['_FW']->env['salt']);

            setcookie('login', $this->encrypt($this->token), path: '/');
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
            $this->post($users);
        else
            $this->auth($users);
    }
}
