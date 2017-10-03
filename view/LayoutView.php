<?php

namespace view;

class LayoutView {

    public function render($isLoggedIn, LoginView $v, DateTimeView $dtv, RegisterView $rv) {
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
                ' . $v->response() . ' 
                
                ' . $dtv->show() . '
            </div>
            </body>
        </html>
        ';
    }
}