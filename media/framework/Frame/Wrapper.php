<?php

namespace Framework\Frame;

use Framework\Foundation\Feature\Media;

class Wrapper
{
    public function media_exists(string $name): bool
    {
        return Media::is_file($name);
    }

    public function render_media(string $name, null|string $encoding = null): array
    {
        $media = new Media($name);

        if ($media->file_exists())
            return [$media->filename, $encoding];

        return ['File not found', 404, null, 'ASCII'];
    }

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
