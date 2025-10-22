<?php

namespace Application;

use Framework\Frame\{Path, View};

class Main extends View
{
    public function __invoke(): array
    {
        return parent::render_template('main.php');
    }

    public function redirect(Path $path): array
    {
        return parent::redirect_response(parent::url_for('page', name: $path->name));
    }
}
