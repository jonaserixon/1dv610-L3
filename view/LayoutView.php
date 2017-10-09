<?php

namespace view;

class LayoutView {

    private $message = '';

    public function render($isLoggedIn, LoginView $v, RegisterView $rv, $decideViewToRender) {
        echo '<!DOCTYPE html>
        <html>
            <head>
            <meta charset="utf-8">
            <title>Login Example</title>
            </head>
            <body>
            <h1>Assignment 2</h1>
            ' . $this->renderIsLoggedIn($isLoggedIn, $decideViewToRender) . '
            <div class="container">
                ' . $this->renderLoginOrRegister($v, $rv, $decideViewToRender) . '
                ' . $this->displayTime() . '
            </div>
            </body>
        </html>
        ';
    }

    private function renderIsLoggedIn($isLoggedIn, $decideViewToRender) {
        if ($isLoggedIn) {
            return '<a href="?edit">Change username</a><h2>Logged in</h2>';
        }
        if ($decideViewToRender == 'register') {
            return '<a href="/1dv610-L3/index.php">Back to login</a><h2>Not logged in</h2><h2>Register new user</h2>';
        }
        return '<a href="?register">Register a new user</a><h2>Not logged in</h2>';
    }

    private function displayTime() {
		date_default_timezone_set('Europe/Stockholm');
		return '<p>' . date('l') . ', the ' . date('jS \of F Y') . ', The time is ' . date('h:i:s') . '</p>';
    }
    

    private function renderLoginOrRegister($v, $rv, $decideViewToRender) {
        if ($decideViewToRender == 'login') {
            return $v->response($this->message, false);
        } else if ($decideViewToRender == 'register') {
            return $rv->response($this->message);
        } else if ($decideViewToRender == 'editname') {
            return $v->response($this->message, true);            
        }
    }

    public function setMessage($message) {
        $this->message = $message;
    }
}
