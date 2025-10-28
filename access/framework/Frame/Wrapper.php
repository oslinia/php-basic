<?php

namespace Framework\Frame;

class Wrapper
{
    public function url_path(string $name): string
    {
        return $GLOBALS['_FW']->env['public'] . $name;
    }

    public function path_info(): string
    {
        return $GLOBALS['_FW']->env['path_info'];
    }

    public function url_for(string ...$args): null|string
    {
        $name = array_shift($args);

        return $GLOBALS['_FW']->collect($name, $args);
    }

    public function redirect_response(string $url, int $code = 307): array
    {
        header('Location: ' . $url);

        return ['', $code];
    }

    public function base_response(
        string      $body,
        int|null    $code = null,
        null|string $mimetype = null,
        null|string $encoding = null,
    ): array {
        return [$body, $code, $mimetype, $encoding];
    }
}
