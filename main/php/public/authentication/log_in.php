<?php
    require_once "../../global.php";

    not_logged_in_only();

    $query_string = $_SERVER["QUERY_STRING"];
    $_query_string = ($query_string !== "") ? "?$query_string" : "";

    if (isset($_POST["submit"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        if (!validate_credentials($username, $password)) {
            //
        } else {
            $connection = connect();

            $statement = $connection -> prepare("
                SELECT * FROM Members WHERE Username = :username LIMIT 1;
            ");

            $statement -> execute(["username" => $username]);

            if ($statement -> rowCount() === 0) {
                //
            } else {
                $hashed_password = ($statement -> fetch())["Password"];

                if (!password_verify($password, $hashed_password)) {
                    //
                } else {
                    setcookie(
                        "member",
                        base64_encode("$username|$hashed_password"),
                        (isset($_POST["remember"])) ? time() + (86400 * 30) : 0,
                        "/",
                        "",
                        false,
                        true,
                    );

                    $queries = [];
                    parse_str($query_string, $queries);

                    redirect((isset($queries["destination"]))
                        ? urldecode($queries["destination"])
                        : ""
                    );
                }
            }
        }
    }

    echo $mustache -> render("base", [
        "title" => "Log In",
        "content" => <<<CONTENT
            <form method="post" action="{$script}{$_query_string}">
                <input type="text" name="username">
                <input type="password" name="password">
                <input type="checkbox" name="remember">
                <input type="submit" name="submit">
            </form>
        CONTENT,
    ]);
?>