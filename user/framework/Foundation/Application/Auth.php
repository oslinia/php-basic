<?php

namespace Framework\Foundation\Application;

class Auth extends View\Template
{
    public bool $auth;

    public function __construct()
    {
        $this->auth = new Access()();
    }

    public function login(): array
    {
        return parent::render_template('auth/login.php');
    }

    public function logout(): array
    {
        if ($this->auth)
            return $this->login();

        setcookie('token', '', 0, '/');

        return parent::redirect_response(parent::url_for('main'));
    }

    public function superuser(): array
    {
        $user = new User(true);

        if ($user->superuser)
            return $user->installed ?
                parent::redirect_response($user->redirect())
                :
                parent::render_template('auth/superuser.php', $user->form());

        return ['Not Found', 404, null, 'ASCII'];
    }
}
