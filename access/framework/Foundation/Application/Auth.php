<?php

namespace Framework\Foundation\Application;

class Auth extends View\Template
{
    private Access $access;

    public bool $auth;

    public function __construct()
    {
        $this->access = new Access;

        $this->auth = $this->access->bool();
    }

    public function login(): array
    {
        return $this->render_template('auth/login.php', $this->access->csrf_token());
    }

    public function logout(): array
    {
        if ($this->auth)
            return $this->login();

        $this->access->logout();

        return parent::redirect_response(parent::url_for('main'));
    }
}
