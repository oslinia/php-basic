<?php

namespace Framework\Foundation;

use Framework\Frame;

class Env extends Frame
{
    public array $env;

    private function default(string $filename): void
    {
        $f = fopen($filename, 'w');

        fwrite($f, '<?php return ' . var_export([
            'dirname' => dirname($filename),
            'public' => '/static/',
            'inaction' => 3600,
            'salt' => uniqid('', true),
        ], true) . ';');

        fclose($f);
    }

    private function require(string $filename): void
    {
        is_file($filename) || $this->default($filename);

        $this->env = require_once $filename;
    }

    public function __construct(string $path_info)
    {
        $this->require(parent::root('resource', 'config.php'));

        $this->env['path_info'] = $path_info;
    }
}
