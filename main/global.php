<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\OAuth;

    use League\OAuth2\Client\Provider\Google;

    require_once __DIR__ . "/vendor/autoload.php";

    Dotenv\Dotenv::createImmutable(__DIR__) -> load();

    // error_reporting(0);

    $script = $_SERVER["SCRIPT_NAME"];

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
            "hashed_password" => "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789$,.+=/",
        ],
        "length" => [
            "username" => [2, 20],
            "password" => [8, 40],
            "hashed_password" => 97,
        ],
    ];

    const BLACKLISTED_USERNAMES = ["admin", "administrator"];

    const HASH = [
        "algorithm" => PASSWORD_ARGON2ID,
        "options" => [
            "memory_cost" => 64 * 1024,
            "time_cost" => 8,
            "threads" => 8,
        ],
    ];

    function get_server() {
        $port = $_SERVER["SERVER_PORT"];

        return (isset($_SERVER['HTTPS']) && filter_var($_SERVER['HTTPS'], FILTER_VALIDATE_BOOLEAN)) ? "https" : "http" . "://" . $_SERVER["SERVER_NAME"] . (((!in_array($port, [80, 443]))) ? ":{$port}" : ""); // using $_SERVER variables here is fine here since we have UseCanonicalName turned on
    }

    function get_directory() {
        global $script;

        return get_server() . dirname($script);
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
                    SELECT * FROM {$database} WHERE ID = :id LIMIT 1;
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

    function validate_id($id) {
        foreach (mb_str_split($id) as $character) {
            if (!str_contains(IDS["characters"], $character)) {
                return false;
            }
        }

        return true;
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

        if (in_array(strtolower($username), BLACKLISTED_USERNAMES)) {
            return false;
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

    function validate_hashed_password($password) {
        if (strlen($password) !== CREDENTIALS["length"]["hashed_password"]) {
            return false;
        }

        foreach (mb_str_split($password) as $character) {
            if (!str_contains(CREDENTIALS["characters"]["hashed_password"], $character)) {
                return false;
            }
        }

        return true;
    }

    function validate_credentials($username, $password) {
        return validate_username($username) && validate_password($password);
    }

    function hash_password($password) {
        return password_hash($password, HASH["algorithm"], HASH["options"]);
    }

    function redirect($url) {
        header("Location: $url");

        die();
    }

    function get_member() {
        if (!is_logged_in()) {
            return false;
        }

        $decoded = base64_decode($_COOKIE["member"], true);

        if ($decoded === false) {
            return false;
        }

        try {
            $member = explode("|", $decoded, 2 + 1);
        } catch (ValueError $_) {
            return false;
        }

        if (count($member) === 0) {
            return false;
        }

        $id = $member[0];
        $password = $member[1];

        if (!validate_id($id) || !validate_hashed_password($password)) {
            return false;
        }

        $statement = connect() -> prepare("
            SELECT * FROM Members WHERE ID = :id AND PASSWORD = :password LIMIT 1;
        ");

        $statement -> execute([
            "id" => $id,
            "password" => $password,
        ]);

        if ($statement -> rowCount() === 0) {
            return false;
        }

        $member = $statement -> fetch();

        if (password_needs_rehash($member["Password"], HASH["algorithm"], HASH["options"])) {
            log_out();
    
            redirect("/authentication/log_in.php");
        }

        return $member;
    }

    $member = get_member();

    function is_logged_in() {
        return isset($_COOKIE["member"]);
    }

    function not_logged_in_only() {
        if (is_logged_in()) {
            redirect("/");
        }
    }

    function logged_in_only() {
        if (!is_logged_in()) {
            redirect("/authentication/log_in.php?destination=" . urlencode($_SERVER["REQUEST_URI"]));
        }
    }

    function administrator_only() {
        global $member;

        if ($member === false) {
            redirect("/");
        }

        if ($member["Administrator"] === 0) {
            redirect("/");
        }
    }

    function log_out() {
        unset($_COOKIE["member"]);
        setcookie("member", null, -1, "/");
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
        } catch (\Exception | \Throwable $_) {
            return false;
        }
    }

    function render_template($template, $variables) {
        global $member;

        $is_logged_in = is_logged_in();

        $variables["logged_in"] = $is_logged_in;

        if ($is_logged_in) {
            $variables["username"] = $member["Username"];
        }

        echo (new Mustache_Engine([
            "loader" => new Mustache_Loader_FilesystemLoader(__DIR__ . "/templates", [
                "extension" => ".html",
            ]),
        ])) -> render($template, $variables);
    }
?>