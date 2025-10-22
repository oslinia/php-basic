<?php

spl_autoload_register(function ($class) {
    $path = explode('\\', $class);

    require_once match (array_shift($path)) {
        'Application' => implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'src', 'application', ...$path]),
        'Framework' => implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'framework', ...$path]),
    } . '.php';
});
