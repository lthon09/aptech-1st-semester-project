<?php
    const CREDENTIALS = array(
        "characters" => array(
            "username" => "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ ",
            "password" => "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789`~!?@#$%^&_+-*=/\\|,.;:'\"<>()[]{}",
        ),
        "length" => array(
            "username" => array(2, 20),
            "password" => array(8, 40),
        ),
    );

    function generate_id() {
        return bin2hex(random_bytes(16));
    }

    function _hash($string) {
        return password_hash($string, PASSWORD_BCRYPT);
    }

    function validate_credentials($username, $password) {
        if (
            (strlen($username) < CREDENTIALS["length"]["username"][0]) ||
            (strlen($username) > CREDENTIALS["length"]["username"][1]) ||

            (strlen($password) < CREDENTIALS["length"]["password"][0]) ||
            (strlen($password) > CREDENTIALS["length"]["password"][1])
        ) {
            return false;
        }

        foreach (mb_str_split($username) as $character) {
            if (!str_contains(CREDENTIALS["characters"]["username"], $character)) {
                return false;
            }
        }
        foreach (mb_str_split($password) as $character) {
            if (!str_contains(CREDENTIALS["characters"]["password"], $character)) {
                return false;
            }
        }

        return true;
    }

    function redirect($url) {
        header("Location: $url");

        die();
    }

    function connect() {
        return new PDO("mysql:host=localhost;port=3306;dbname=PleasantTours", "root", "");
    }
?>