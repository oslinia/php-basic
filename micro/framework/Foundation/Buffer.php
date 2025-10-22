<?php

namespace Framework\Foundation;

function buffer(): string
{
    ob_start();

    extract(Buffer::$context);

    require Buffer::$file;

    return ob_get_clean();
}

class Buffer
{
    public static array $context;
    public static string $file;
}
