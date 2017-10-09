<?php

namespace model;

class DatabaseModel {

    public function __construct() {
        
    }
    
    //Login
    public function findAndVerifyUser($username, $pw) {
        $data = file('../database/database.txt');
        for ($i = 0; $i < count($data); $i++) {
            if (strpos(json_encode($data[$i]),$username)) {
                //Type Removes "" marks
                //Type2 Removes n  
                $PasswordType = trim(json_encode($data[$i + 1]), '"');                  
                $PasswordType2 = substr(trim(json_encode($data[$i + 1]), '"'), 0, -1);  
                
                //Remove backslashes because of strange bug which keeps adding random backslashes to hash for some reason. 
                if ($this->verifyHash($pw, str_replace('\\', '', $PasswordType))) {
                    return true;
                }
                if ($this->verifyHash($pw, str_replace('\\', '', $PasswordType2))) {
                    return true;
                }
            }
        }
        return false;
    }
    public function register($username, $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $myfile = fopen("../database/database.txt", "a+");
        fwrite($myfile, "\n" . $username . "\n");
        fwrite($myfile, $hashed_password);
        fclose($myfile);
    }
    
    public function verifyHash($inputPassword, $databasePassword) {
        if (password_verify($inputPassword, $databasePassword)) {
            return true;
        }
    }

    public function alreadyExist($username) {
        $lines = file('../database/database.txt');
        foreach ($lines as $n => $line) {
            if ($line == $username . "\n") {                    
                return true;
            } 
        }
        return false;    
    }

    public function editUsername($username, $originalName) {
        $file = '../database/database.txt';
        $content = file($file);
        foreach ($content as $n => $line) {
            if ($line == $originalName . "\n") {   
                echo $line;
                echo "hehe";                 
                $line = $username;
                $allContent = implode("", $content); //Put the array back into one string
                file_put_contents('../database/database.txt', $allContent);
            } 
        }


        //file_put_contents( $filename , implode( "\n", $lines ) );
    }

}