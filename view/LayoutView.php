<?php

namespace view;

class LayoutView {

    public function render($isLoggedIn, LoginView $v, RegisterView $rv, $message, $decideViewToRender) {
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
                ' . $this->renderLoginOrRegister($v, $rv, $message, $decideViewToRender) . '
                ' . $this->displayTime() . '
            </div>
            </body>
        </html>
        ';
    }

    private function renderIsLoggedIn($isLoggedIn, $decideViewToRender) {
        if ($isLoggedIn) {
            return '<a href="?">Change username</a><h2>Logged in</h2>';
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
    

    private function renderLoginOrRegister($v, $rv, $message, $decideViewToRender) {
        if ($decideViewToRender == 'login') {
            return $v->response($message);
        } else {
            return $rv->response($message);
        }
    }
}
