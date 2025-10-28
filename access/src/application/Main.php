<?php

namespace Application;

use Framework\Frame\View;

class Main extends View
{
    public function __invoke(): array
    {
        return parent::render_template('main.php');
    }
}
