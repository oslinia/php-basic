<?php

namespace Framework\Foundation\Application\View;

use Framework\Foundation\Buffer;
use Framework\Frame\Wrapper;

use function Framework\Foundation\buffer;

class Template extends Wrapper
{
    public function render_template(
        string      $name,
        array|null  $context = null,
        int|null    $code = null,
    ): array {
        Buffer::$file = __DIR__ . DIRECTORY_SEPARATOR . $name;
        Buffer::$context = $context ?? [];

        return [buffer(), $code, 'text/html'];
    }
}
