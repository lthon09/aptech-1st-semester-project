<?php
    require_once "../../utilities.php";

    require_once "../../dependencies/loaders/mustache.php";

    if (isset($_POST["submit"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        if (!validate_credentials($username, $password)) {
            //
        } else {
            $connection = connect();

            $statement = $connection -> prepare("
                SELECT * FROM Members WHERE Username = :username;
            ");

            $statement -> execute(array("username" => $username));

            if ($statement -> rowCount() === 0) {
                //
            } else {
                $hashed_password = $statement -> fetch()["Password"];

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
    
                    redirect("../");
                }
            }
        }
    }

    echo $mustache -> render("base", array(
        "title" => "Log In",
        "content" => <<<"CONTENT"
            <form method="post" action="{$_SERVER["PHP_SELF"]}">
                <input type="text" name="username">
                <input type="password" name="password">
                <input type="checkbox" name="remember">
                <input type="submit" name="submit">
            </form>
        CONTENT,
    ));
?>