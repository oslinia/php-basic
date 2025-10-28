<?php

namespace Framework\Foundation\Feature;

use Framework\Foundation\Buffer;
use Framework\Frame;

class Template extends Frame
{
    public static function is_file(string $name): bool
    {
        return is_file(parent::root('src', 'view', $name));
    }

    public function __construct(string $name)
    {
        Buffer::$file = parent::root('src', 'view', $name);
    }

    public function file_exists(): bool
    {
        return is_file(Buffer::$file);
    }

    public function extract(array|null $context): void
    {
        Buffer::$context = $context ?? [];
    }
}
