<?php

namespace Framework\Mapping;

class Structure extends Rule
{
    private function rules(): array
    {
        $patterns = $masks = $map = array();

        foreach (parent::$rules as $path => $items) {
            [[$name, $class], $size] = $items;

            $pattern = '/^' . str_replace('/', '\/', $path) . '$/';

            $masks[$name][$size] = null;

            0 === $size || [$masks[$name][$size], $pattern] = [
                $items[2],
                str_replace(array_keys($items[3]), array_values($items[3]), $pattern),
            ];

            $patterns[$pattern] = [$name, $class];

            $map[$name][$size] = [$path, $pattern];
        }

        return ['patterns' => $patterns, 'masks' => $masks, 'map' => $map];
    }

    private function write(string $dirname): void
    {
        foreach ($this->rules() as $name => $value) {
            $f = fopen($dirname . $name . '.php', 'w');

            fwrite($f, '<?php return ' . var_export($value, true) . ';');

            fclose($f);
        }
    }

    private function directory(string $dirname): void
    {
        mkdir($dirname, recursive: true);

        $dirname = dirname($dirname) . DIRECTORY_SEPARATOR;

        foreach (['media'] as $value)
            is_dir($dir = $dirname . $value) || mkdir($dir);
    }

    private function require(string $src_rules): void
    {
        require_once $src_rules;
    }

    public function __construct(string $dirname, string $src_rules)
    {
        $this->directory($dirname);

        $this->require($src_rules);

        $this->write($dirname . DIRECTORY_SEPARATOR);
    }
}
