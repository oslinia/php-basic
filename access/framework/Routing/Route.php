<?php

namespace Framework\Routing;

use Framework\Foundation\Environment;
use Framework\Frame;
use Framework\Frame\Path;

class Route extends Frame
{
    private string $dirname;

    public function __construct(string $dirname)
    {
        parent::__construct($dirname);

        is_dir($dirname = parent::root('resource', 'routing')) || new Structure(
            $dirname,
            parent::root('src', 'rules.php'),
            __DIR__ . DIRECTORY_SEPARATOR . 'rules.php',
        );

        $this->dirname = $dirname . DIRECTORY_SEPARATOR;
    }

    public function __invoke(string $path_info): array|string
    {
        new Environment($path_info, $this->dirname . 'map.php');

        foreach (require $this->dirname . 'patterns.php' as $pattern => $items)
            if (preg_match($pattern, $path_info, $matches)) {
                [$name, $class] = $items;

                $method = explode('.', $name, 2)[1] ?? '__invoke';

                $value = array_slice($matches, 1);

                $masks = (require $this->dirname . 'masks.php')[$name];

                if (0 < $size = count($value)) {
                    if (isset($masks[$size])) {
                        $tokens = array();

                        foreach ($value as $i => $pattern)
                            $tokens[$masks[$size][$i]] = $pattern;

                        return new $class()->$method(new Path($tokens));
                    }
                } else {
                    return new $class()->$method();
                }
            }

        return ['Not Found', 404, null, 'ASCII'];
    }
}
