<?php
    require_once "../../utilities.php";

    require_once "../../dependencies/loaders/mustache.php";

    if (isset($_POST["submit"])) {
        $username = $_POST["username"];

        $email = $_POST["email"];

        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];

        if ($password !== $confirm_password) {
            //
        } else {
            if (!validate_credentials($username, $password)) {
                //
            } else {
                $hashed_password = password_hash($password, PASSWORD_HASHING_ALGORITHM);

                if ($hashed_password === false) {
                    //
                } else {
                    $connection = connect();

                    $statement = $connection -> prepare("
                        INSERT INTO Members (ID, Username, Email, `Password`, Administrator) VALUES (:id, :username, :email, :password, FALSE);
                    ");

                    $statement -> execute(array(
                        "id" => generate_id(),
                        "username" => $username,
                        "email" => $email,
                        "password" => $hashed_password,
                    ));

                    redirect("log_in.php");
                }
            }
        }
    }

    echo $mustache -> render("base", array(
        "title" => "Sign Up",
        "content" => <<<"CONTENT"
            <form method="post" action="{$_SERVER["PHP_SELF"]}">
                <input type="text" name="username">
                <input type="email" name="email">
                <input type="password" name="password">
                <input type="password" name="confirm_password">
                <input type="submit" name="submit">
            </form>
        CONTENT,
    ));
?>