<?php
    require_once "../../global.php";

    not_logged_in_only();

    if (isset($_POST["submit"])) {
        $username = $_POST["username"];

        $email = $_POST["email"];

        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];

        if ($password !== $confirm_password) {
            //
        } else {
            if (!validate_credentials($username, $password) || !validate_email($email)) {
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

                $hashed_password = hash_password($password);

                if ($hashed_password === false) {
                    //
                } else {
                    $id = generate_id(IDS["lengths"]["secure"], "UnverifiedMembers");

                    if ($id === false) {
                        //
                    }

                    $link = get_directory() . "/verify.php?id=" . $id;

                    if(!send_mail($email, "Confirm Your Email Address",
                        <<<BODY
                            <strong>{$username}</strong>,
                            <br><br>
                            It looks like a Pleasant Tours account has just been created using your email address. Please verify this by opening the link below.
                            <br>
                            This link will expire in <strong>15 minutes</strong>.
                            <br>
                            <br>
                            If this wasn't you, please ignore this email.
                            <br><br>
                            <a target="_blank" href="{$link}">CONFIRM YOUR EMAIL ADDRESS</a>
                            <br><br>
                            <strong>
                                Cheers,
                                <br>
                                Pleasant Tours
                            </strong>
                        BODY,
                        "
                            {$username},

                            It looks like a Pleasant Tours account has just been created using your email address. Please verify this by clicking on the link below.
                            This link will expire in 15 minutes.

                            If this wasn't you, please ignore this email.

                            CONFIRM YOUR EMAIL ADDRESS: {$link}

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

                        //
                    }
                }
            }
        }
    }

    render_template("base", [
        "title" => "Sign Up",
        "content" => <<<CONTENT
            <form method="post" action="{$script}">
                <input type="text" name="username">
                <input type="email" name="email">
                <input type="password" name="password">
                <input type="password" name="confirm_password">
                <input type="submit" name="submit">
            </form>
        CONTENT,
    ]);
?>