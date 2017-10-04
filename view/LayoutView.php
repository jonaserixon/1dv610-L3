<?php

namespace view;

class LayoutView {

    public function render($isLoggedIn, LoginView $v, $message) {
        echo '<!DOCTYPE html>
        <html>
            <head>
            <meta charset="utf-8">
            <title>Login Example</title>
            </head>
            <body>
            <h1>Assignment 2</h1>

            ' . $this->renderIsLoggedIn($isLoggedIn) . '

            <div class="container">
                ' . $v->response($message) . ' 

                ' . $this->displayTime() . '
            </div>
            </body>
        </html>
        ';
    }

    private function renderIsLoggedIn($isLoggedIn) {
        if ($isLoggedIn) {
            return '<h2>Logged in</h2>';
        }

        return '<a href="?register">Register a new user</a><h2>Not logged in</h2>';
    }

    private function displayTime() {
		date_default_timezone_set('Europe/Stockholm');
		return '<p>' . date('l') . ', the ' . date('jS \of F Y') . ', The time is ' . date('h:i:s') . '</p>';
	}
}