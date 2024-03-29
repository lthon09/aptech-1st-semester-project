<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\OAuth;

    use League\OAuth2\Client\Provider\Google;

    require_once __DIR__ . "/fpdf/fpdf.php";
    require_once __DIR__ . "/vendor/autoload.php";

    Dotenv\Dotenv::createImmutable(__DIR__) -> load();

    error_reporting(0);

    $script = $_SERVER["SCRIPT_NAME"];

    const IDS = [
        "characters" => "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
        "lengths" => [
            "general" => 16,
            "secure" => 32,
        ],
    ];

    $id_characters_length = strlen(IDS["characters"]);

    const LARGE_QUERY_PAGE_ITEMS_COUNT = 50;
    const MAXIMUM_REVIEW_CONTENT_LENGTH = 4000;

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

    const HASH = [
        "algorithm" => PASSWORD_ARGON2ID,
        "options" => [
            "memory_cost" => 64 * 1024,
            "time_cost" => 8,
            "threads" => 8,
        ],
    ];

    function get_server() : string {
        $port = $_SERVER["SERVER_PORT"];

        return (isset($_SERVER['HTTPS']) && filter_var($_SERVER['HTTPS'], FILTER_VALIDATE_BOOLEAN)) ? "https" : "http" . "://" . $_SERVER["SERVER_NAME"] . (((!in_array($port, [80, 443]))) ? ":{$port}" : ""); // using $_SERVER variables here is fine here since we have UseCanonicalName turned on
    }

    function get_directory() : string {
        global $script;

        return get_server() . dirname($script);
    }

    function format_float(int | float $float, int $true, float $false) : int | float {
        return (floor($float) == $float) ? $true : $false;
    }

    function calculate_price(int | float $price, int $sale) : int | float {
        $calculated_price = $price - ($price * $sale / 100);

        return format_float($calculated_price, (int)$calculated_price, bcadd($calculated_price, 0, 2));
    }

    function generate_id(int $length, string $database) : string | false {
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

    function validate_id(string $id) : bool {
        foreach (mb_str_split($id) as $character) {
            if (!str_contains(IDS["characters"], $character)) {
                return false;
            }
        }

        return true;
    }

    function validate_email(string $email) : bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    function validate_username(string $username) : bool {
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

    function validate_password(string $password) : bool {
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

    function validate_hashed_password(string $password) : bool {
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

    function validate_credentials(string $username, string $password) : bool {
        return validate_username($username) && validate_password($password);
    }

    function hash_password(string $password) : string {
        return password_hash($password, HASH["algorithm"], HASH["options"]);
    }

    function get_queries() : array {
        $query_string = $_SERVER["QUERY_STRING"];

        $queries = [];
        parse_str($query_string, $queries);

        return $queries;
    }

    function redirect(string $url) {
        header("Location: $url");

        die();
    }

    function get_member() : array | false {
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

        if (count($member) !== 2) {
            return false;
        }

        $username = $member[0];
        $password = $member[1];

        if (!validate_username($username) || !validate_hashed_password($password)) {
            return false;
        }

        $statement = connect() -> prepare("
            SELECT * FROM Members WHERE Username = :username AND `Password` = :password LIMIT 1;
        ");

        $statement -> execute([
            "username" => $username,
            "password" => $password,
        ]);

        if (($statement -> rowCount()) === 0) {
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

    function is_logged_in() : bool {
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

    function log_out() {
        unset($_COOKIE["member"]);
        setcookie("member", null, -1, "/");
    }

    function connect() : PDO {
        return new PDO("mysql:host=localhost;port=3306;dbname=PleasantTours", "root", "");
    }

    function large_query(string $database, string $conditions, array $parameters) : array {
        $offset = 0;
        $results = [];

        $connection = connect();

        while (true) {
            $statement = $connection -> prepare(
                "
                    SELECT * FROM $database 
                "
                    . $conditions
                    . " 
                        LIMIT $offset, 
                    "
                        . LARGE_QUERY_PAGE_ITEMS_COUNT
                        . ";"
            );

            $statement -> execute($parameters);

            if ($statement -> rowCount() === 0) {
                break;
            }

            foreach (($statement -> fetchAll()) as $row) {
                $results[$row["ID"]] = $row;
            }

            $offset += LARGE_QUERY_PAGE_ITEMS_COUNT;
        }

        return $results;
    }

    function send_mail(string $receiver, string $subject, string $primary_body, string $alternative_body) : bool {
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

    function render_template(string $template, array $variables) {
        global $member;

        $is_logged_in = is_logged_in();

        $variables["logged_in"] = $is_logged_in;

        if ($is_logged_in) {
            $variables["username"] = htmlentities($member["Username"]);
        }

        echo (new Mustache_Engine([
            "loader" => new Mustache_Loader_FilesystemLoader(__DIR__ . "/templates", [
                "extension" => ".html",
            ]),
        ])) -> render($template, $variables);
    }
?>