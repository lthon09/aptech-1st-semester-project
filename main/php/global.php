<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\OAuth;

    use League\OAuth2\Client\Provider\Google;

    require_once __DIR__ . "/vendor/autoload.php";

    Dotenv\Dotenv::createImmutable(__DIR__) -> load();

    const CREDENTIALS = [
        "characters" => [
            "username" => "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ ",
            "password" => "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789`~!?@#$%^&_+-*=/\\|,.;:'\"<>()[]{}",
        ],
        "length" => [
            "username" => [2, 20],
            "password" => [8, 40],
        ],
    ];

    const PASSWORD_HASHING_ALGORITHM = PASSWORD_BCRYPT;

    $mustache = new Mustache_Engine([
        "loader" => new Mustache_Loader_FilesystemLoader(__DIR__ . "/../templates", [
            "extension" => ".html",
        ]),
    ]);

    function get_server() {
        return (isset($_SERVER['HTTPS']) && filter_var($_SERVER['HTTPS'], FILTER_VALIDATE_BOOLEAN)) ? "https" : "http" . "://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]);
    }

    function generate_id($length = 16) {
        try {
            return bin2hex(random_bytes($length));
        } catch(Exception $_) {
            return false;
        }
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

    function send_mail($receiver, $subject, $primary_body, $alternative_body) {
        try {
            $mail = new PHPMailer();

            $mail -> isSMTP();
            $mail -> SMTPDebug = SMTP::DEBUG_OFF;
            $mail -> SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail -> SMTPAuth = true;
            $mail -> AuthType = "XOAUTH2";
            $mail -> Host = "smtp.gmail.com";
            $mail -> Port = 465;
            $mail -> setOAuth(new OAuth([
                "userName" => $_ENV["email_address"],
                "clientId" => $_ENV["gmail_client_id"],
                "clientSecret" => $_ENV["gmail_client_secret"],
                "refreshToken" => $_ENV["gmail_refresh_token"],
                "provider" => new Google([
                    "clientId" => $_ENV["gmail_client_id"],
                    "clientSecret" => $_ENV["gmail_client_secret"],
                ]),
            ]));

            $mail -> setFrom($_ENV["email_address"], "Pleasant Tours");
            $mail -> addAddress($receiver);

            $mail -> isHTML(true);

            $mail -> Subject = $subject;
            $mail -> Body = $primary_body;
            $mail -> AltBody = $alternative_body;

            $mail -> send();

            return true;
        } catch (Exception $_) {
            return false;
        }
    }
?>