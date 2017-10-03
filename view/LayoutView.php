<?php

namespace view;

class LayoutView {

    public function render($isLoggedIn, LoginView $v, RegisterView $rv) {
        echo '<!DOCTYPE html>
        <html>
            <head>
            <meta charset="utf-8">
            <title>Login Example</title>
            </head>
            <body>
            <h1>Assignment 2</h1>
                        
            <div class="container">
                ' . $v->response() . ' 
            </div>
            </body>
        </html>
        ';
    }
}