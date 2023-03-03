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
                $member = $statement -> fetch();

                $id = $member["ID"];
                $hashed_password = $member["Password"];

                if (!password_verify($password, $hashed_password)) {
                    //
                } else {
                    if (password_needs_rehash($password, HASH["algorithm"], HASH["options"])) {
                        $hashed_password = hash_password($password);

                        $connection -> prepare("
                            UPDATE Members SET `Password` = :password WHERE ID = :id;
                        ") -> execute([
                            "password" => $hashed_password,
                            "id" => $id,
                        ]);
                    }

                    setcookie(
                        "member",
                        base64_encode("$id|$hashed_password"),
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

    echo render_template("base", [
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