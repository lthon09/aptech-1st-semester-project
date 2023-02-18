<?php
    require_once "../../global.php";

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
                    $server = (isset($_SERVER['HTTPS']) && filter_var($_SERVER['HTTPS'], FILTER_VALIDATE_BOOLEAN)) ? "https" : "http" . "://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]);

                    $id = generate_id(32);

                    if ($id === false) {
                        //
                    }

                    $connection = connect();

                    $statement = $connection -> prepare("
                        INSERT INTO UnverifiedMembers (ID, Username, Email, `Password`) VALUES (:id, :username, :email, :password);
                    ");

                    $statement -> execute([
                        "id" => $id,
                        "username" => $username,
                        "email" => $email,
                        "password" => $hashed_password,
                    ]);

                    if(!send_mail($email, "Confirm Your Email Address",
                        <<<"BODY"
                            <strong>{$username}</strong>,
                            <br><br>
                            It seems like a Pleasant Tours account has just been created using your email address. Please verify this by clicking on the link below.
                            <br>
                            If this wasn't you, please ignore this email.
                            <br><br>
                            <a target="_blank" href="{$server}/verify/{$id}">CONFIRM YOUR EMAIL ADDRESS</a>
                            <br><br>
                            <strong>
                                Cheers,
                                <br>
                                Pleasant Tours
                            </strong>
                        BODY,
                        "
                            {$username},

                            It seems like a Pleasant Tours account has just been created using your email address. Please verify this by clicking on the link below.
                            If this wasn't you, please ignore this email.

                            CONFIRM YOUR EMAIL ADDRESS: {$server}/verify/{$id}

                            Cheers,
                            Pleasant Tours
                        ",
                    )) {
                        //
                    } else {
                        //
                    }
                }
            }
        }
    }

    echo $mustache -> render("base", [
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
    ]);
?>