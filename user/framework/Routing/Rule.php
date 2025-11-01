<?php

namespace Framework\Routing;

class Rule
{
    protected static array $rules = array();

    private string $current;

    public static function route(string $path, string $name, string $class): Rule
    {
        $names = $tokens = array();

        if (preg_match_all('/{([A-Za-z0-9_-]+)}/', $path, $matches)) {
            foreach ($matches[0] as $mask)
                $tokens[$mask] = '([A-Za-z0-9_-]+)';

            $names = $matches[1];
        }

        self::$rules[$path] = 0 === ($size = count($names)) ?
            array(array($name, $class), $size)
            :
            array(array($name, $class), $size, $names, $tokens);

        return new self($path);
    }

    public function __construct(string $path)
    {
        $this->current = $path;
    }

    public function where(string ...$args): void
    {
        foreach ($args as $name => $pattern)
            self::$rules[$this->current][3]['{' . $name . '}'] = '(' . $pattern . ')';
    }
}
