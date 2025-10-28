<?php

namespace Framework\Foundation\Feature;

use Framework\Frame;

class Media extends Frame
{
    public static bool $bool = false;
    public string $filename;

    public static function is_file(string $name): bool
    {
        return is_file(parent::root('resource', 'media', $name));
    }

    public function __construct(string $name)
    {
        $this->filename = parent::root('resource', 'media', $name);
    }

    public function file_exists(): bool
    {
        self::$bool = is_file($this->filename);

        return self::$bool;
    }
}
