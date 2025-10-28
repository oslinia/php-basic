<?php

namespace Framework;

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
