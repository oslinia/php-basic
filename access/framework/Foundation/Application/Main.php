<?php

namespace Framework\Foundation\Application;

class Main extends Auth
{
    public function __invoke(): array
    {
        if ($this->auth)
            return parent::login();

        return parent::render_template('main.php');
    }
}
