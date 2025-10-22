<?php

namespace Application;

use Framework\Frame\{Path, View};

class Page extends View
{
    public function __invoke(Path $path): array
    {
        return parent::render_template('navbar/page.php', ['path' => $path]);
    }

    public function archive(Path $path): array
    {
        return parent::render_template('navbar/archive.php', ['path' => $path]);
    }
}
