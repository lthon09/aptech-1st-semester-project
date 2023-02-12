<?php
    require_once "../../utilities.php";

    require_once "../../dependencies/loaders/mustache.php";

    if (isset($_POST["submit"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $remember = $_POST["remember"];

        $hashed_password = _hash($password);

        if ($hashed_password === false) {
            //
        } else {
            if (!validate_credentials($username, $password)) {
                //
            } else {
                $connection = connect();

                $statement = $connection -> prepare("
                    SELECT FROM Members WHERE Username = :username AND `Password` = :password;
                ");

                $statement -> execute(array(
                    "username" => $username,
                    "password" => $hashed_password,
                ));

                if ($statement -> rowCount() === 0) {
                    //
                } else {
                    setcookie(
                        "member",
                        base64_encode("$username|$hashed_password"),
                        ($remember) ? time() + (86400 * 30) : 0,
                        "/",
                        "",
                        false,
                        true,
                    );

                    redirect("/");
                }
            }
        }
    }

    echo $mustache -> render("base", array(
        "title" => "Log In",
        "content" => <<<"CONTENT"
        CONTENT,
    ));
?>