<?php

namespace Framework;

function salt_encrypt(string $string): string
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

function salt_decrypt(string $string): string
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

function csrf_token(): string
{
    $token = md5(microtime() . $GLOBALS['_FW']->env['salt']);

    setcookie('csrf', salt_encrypt($token), path: '/');

    return $token;
}

function url_path(string $name): string
{
    return $GLOBALS['_FW']->env['public'] . $name;
}

function path_info(): string
{
    return $GLOBALS['_FW']->env['path_info'];
}

function url_for(string ...$args): null|string
{
    $name = array_shift($args);

    return $GLOBALS['_FW']->collect($name, $args);
}

class Frame
{
    private static string $dirname;

    public function __construct(string $dirname)
    {
        self::$dirname = $dirname . DIRECTORY_SEPARATOR;
    }

    protected function root(string ...$args): string
    {
        return self::$dirname . implode(DIRECTORY_SEPARATOR, $args);
    }
}
