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
            if (!validate_credentials($username, $password) || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                //
            } else {
                $connection = connect();

                $statement1 = $connection -> prepare("
                    SELECT * FROM UnverifiedMembers WHERE Email = :email LIMIT 1;
                ");
                $statement2 = $connection -> prepare("
                    SELECT * FROM Members WHERE Email = :email LIMIT 1;
                ");

                $statement1 -> execute(["email" => $email]);
                $statement2 -> execute(["email" => $email]);

                if ($statement1 -> rowCount() !== 0 || $statement2 -> rowCount() !== 0) {
                    //
                }

                $hashed_password = password_hash($password, PASSWORD_HASHING_ALGORITHM);

                if ($hashed_password === false) {
                    //
                } else {
                    $server = get_server();

                    $id = generate_id(32, "UnverifiedMembers");

                    if ($id === false) {
                        //
                    }

                    if(!send_mail($email, "Confirm Your Email Address",
                        <<<"BODY"
                            <strong>{$username}</strong>,
                            <br><br>
                            It seems like a Pleasant Tours account has just been created using your email address. Please verify this by clicking on the link below.
                            <br>
                            This link will expire in <strong>15 minutes</strong>.
                            <br>
                            <br>
                            If this wasn't you, please ignore this email.
                            <br><br>
                            <a target="_blank" href="{$server}/verify.php?id={$id}">CONFIRM YOUR EMAIL ADDRESS</a>
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
                            This link will expire in 15 minutes.

                            If this wasn't you, please ignore this email.

                            CONFIRM YOUR EMAIL ADDRESS: {$server}/verify.php?id={$id}

                            Cheers,
                            Pleasant Tours
                        ",
                    )) {
                        //
                    } else {
                        $connection -> prepare("
                            INSERT INTO UnverifiedMembers
                            (ID, Username, Email, `Password`)
                            VALUES (:id, :username, :email, :password);
                        ") -> execute([
                            "id" => $id,
                            "username" => $username,
                            "email" => $email,
                            "password" => $hashed_password,
                        ]);
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