<?php

namespace Framework\Frame;

class Path
{
    private array $data;

    public function __construct(array $tokens)
    {
        $this->data = $tokens;
    }

    public function __get(string $name): string
    {
        return $this->data[$name];
    }

    public function __isset(string $name): bool
    {
        return array_key_exists($name, $this->data);
    }
}
