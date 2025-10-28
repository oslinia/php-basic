<?php

namespace Framework\Procedure;

use Framework\Routing\Route;

class Http
{
    private function status(int $code): void
    {
        if (ob_get_level())
            ob_end_clean();

        header($_SERVER['SERVER_PROTOCOL'] . [
            200 => ' 200 OK',
            301 => ' 301 Moved Permanently',
            302 => ' 302 Moved Temporarily',
            307 => ' 307 Temporary Redirect',
            308 => ' 308 Permanent Redirect',
            404 => ' 404 Not Found',
            500 => ' 500 Internal Server Error',
        ][$code]);
    }

    private function content(int $length, string $mimetype, null|string $encoding): void
    {
        if (str_starts_with($mimetype, 'text/'))
            $mimetype .= '; charset=' . ($encoding ?? 'UTF-8');

        header('content-length: ' . $length);
        header('content-type: ' . $mimetype);
    }

    private function extract(
        string      $body,
        int|null    $code = null,
        null|string $mimetype = null,
        null|string $encoding = null,
    ): void {
        $this->status($code ?? 200);
        $this->content(strlen($body), $mimetype ?? 'text/plain', $encoding);

        echo $body;
    }

    public function __construct(string $dirname)
    {
        $route = new Route($dirname);

        $callback = $route(explode('?', $_SERVER['REQUEST_URI'])[0]);

        is_array($callback) ? $this->extract(...$callback) : $this->extract($callback);
    }
}
