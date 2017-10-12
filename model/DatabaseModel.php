<?php

namespace model;

class DatabaseModel {

    private static $databasePath = '../database/database.txt';

    public function __construct() {
       $this->doesDatabaseExist();
    }

    private function doesDatabaseExist() {
        if (!file_exists(self::$databasePath)) {
            if (!file_exists('../database')) {
                mkdir('../database', 0777, true);
            }
            $file = fopen(self::$databasePath,"w");

            fwrite($file, '');
            fclose($file);
        }
    }
    
    
    public function findAndVerifyUser($username, $pw) {
        $dbContent = file(self::$databasePath);
        for ($i = 0; $i < count($dbContent); $i++) {
            if (strpos(json_encode($dbContent[$i]),$username)) {
                //Type Removes "" marks
                //Type2 Removes n
                $PasswordType = trim(json_encode($dbContent[$i + 1]), '"');            
                $PasswordType2 = substr(trim(json_encode($dbContent[$i + 1]), '"'), 0, -1);
                
                //Remove backslashes because of strange bug which keeps adding random backslashes to hash for some reason.
                if ($this->verifyPassword($pw, str_replace('\\', '', $PasswordType))) {
                    return true;
                }
                if ($this->verifyPassword($pw, str_replace('\\', '', $PasswordType2))) {
                    return true;
                }
            }
        }
        return false;
    }

    public function register($username, $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $myfile = fopen(self::$databasePath, "a+");
        fwrite($myfile, "\n" . $username . "\n");
        fwrite($myfile, $hashed_password);
        fclose($myfile);
    }
    
    public function verifyPassword($inputPassword, $databasePassword) {
        if (password_verify($inputPassword, $databasePassword)) {
            return true;
        }
    }

    public function alreadyExist($username) {
        if ($username === '') {
            return false;
        }

        $dbContent = file(self::$databasePath);
        foreach ($dbContent as $n => $line) {
            if ($line == $username . "\n") {    
                return true;
            }
        }
        return false;    
    }

    public function editUsername($username, $originalName) {
        $_SESSION['username'] = $username;
        $result = '';
        $dbContent = file(self::$databasePath);

        foreach ($dbContent as $n => $line) {
            if ($line == $originalName . "\n") {
                $result .= $username . "\n";
            } else {
                $result .= $line;
            }
        }
        file_put_contents(self::$databasePath, $result);
    }

    public function getAmountOfUsers() {
        $userCount = 0;
        $dbContent = file(self::$databasePath);
        foreach ($dbContent as $n => $line) {
            $userCount++;
        }
        //Prevent it from showing a negative value
        if (!$userCount > 0) { return 0; }

        return ($userCount - 1) / 2;
    }
}