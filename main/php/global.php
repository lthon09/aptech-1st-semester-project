<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\OAuth;

    use League\OAuth2\Client\Provider\Google;

    require_once __DIR__ . "/vendor/autoload.php";

    Dotenv\Dotenv::createImmutable(__DIR__) -> load();

    const IDS = [
        "characters" => "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
        "lengths" => [
            "member" => 16,
            "secure" => 32,
        ],
    ];

    $id_characters_length = strlen(IDS["characters"]);

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

    function generate_id($length, $database) {
        global $id_characters_length;

        $tries = 0;

        try {
            $connection = connect();

            while ($tries < 5) {
                $id = "";

                for ($index = 0; $index < $length; $index++) {
                    $id .= IDS["characters"][random_int(0, $id_characters_length)];
                }

                $statement = $connection -> prepare("
                    SELECT * FROM $database
                    WHERE ID = :id
                    LIMIT 1;
                ");

                $statement -> execute(["id" => $id]);

                if ($statement -> rowCount() !== 0) {
                    $tries += 1;

                    continue;
                }

                return $id;
            }
        } catch(Exception $_) {
            return false;
        }
    }

    function validate_email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    function validate_username($username) {
        if (
            (strlen($username) < CREDENTIALS["length"]["username"][0]) ||
            (strlen($username) > CREDENTIALS["length"]["username"][1])
        ) {
            return false;
        }

        foreach (mb_str_split($username) as $character) {
            if (!str_contains(CREDENTIALS["characters"]["username"], $character)) {
                return false;
            }
        }

        return true;
    }

    function validate_password($password) {
        if (
            (strlen($password) < CREDENTIALS["length"]["password"][0]) ||
            (strlen($password) > CREDENTIALS["length"]["password"][1])
        ) {
            return false;
        }

        foreach (mb_str_split($password) as $character) {
            if (!str_contains(CREDENTIALS["characters"]["password"], $character)) {
                return false;
            }
        }

        return true;
    }

    function validate_credentials($username, $password) {
        return validate_username($username) && validate_password($password);
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