<?php

namespace Framework\Foundation;

class Environment extends Env
{
    private array $map;

    public function __construct(string $path_info, string $map)
    {
        parent::__construct($path_info);

        $this->map = require $map;

        $GLOBALS['_FW'] = $this;
    }

    public function collect(string $name, array $args): null|string
    {
        if (isset($this->map[$name])) {
            $route = $this->map[$name];

            $size = count($args);

            if (isset($route[$size])) {
                [$path, $pattern] = $route[$size];

                foreach ($args as $mask => $value)
                    $path = str_replace('{' . $mask . '}', $value, $path);

                if (preg_match($pattern, $path, $matches))
                    return $matches[0];
            }
        }

        return null;
    }
}
