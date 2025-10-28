<?php

namespace Framework\Foundation\Application;

class Auth extends View\Template
{
    private Access $access;

    public function auth(): bool
    {
        $this->access = new Access;

        return $this->access->bool;
    }

    public function login(): array
    {
        return $this->render_template('auth/login.php', ['token' => $this->access->token]);
    }

    public function logout(): array
    {
        if ($this->auth())
            return $this->login();

        setcookie('token', '', 0, '/');

        return parent::redirect_response(parent::url_for('main'));
    }
}
