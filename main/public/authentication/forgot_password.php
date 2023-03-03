<?php
    require_once "../../global.php";

    not_logged_in_only();

    if (isset($_POST["submit"])) {
        $method = $_POST["method"];

        $username = $_POST["username"];
        $email = $_POST["email"];

        switch ($method) {
            case "username":
                if (!validate_username($username)) {
                    //
                }

                break;
            case "email":
                if (!validate_email($email)) {
                    //
                }

                break;
            default:
                //

                break;
        }

        $connection = connect();

        $statement = $connection -> prepare(
            "SELECT * FROM Members WHERE "
            . strtoupper($method)
            . " = :input LIMIT 1;"
        );

        $statement -> execute(["input" => $$method]);

        if ($statement -> rowCount() === 0) {
            //
        } else {
            $member = $statement -> fetch();

            $member_id = $member["ID"];
            $username = $member["Username"];
            $email = $member["Email"];

            $reset_password_id = generate_id(IDS["lengths"]["secure"], "ResetPasswordMembers");

            $link = get_directory() . "/reset_password.php?id=" . $reset_password_id;

            if(!send_mail($email, "Reset Your Password",
                <<<BODY
                    <strong>{$username}</strong>,
                    <br><br>
                    A password reset request has just been for with your account. Please click on the link below in order to reset your password.
                    <br>
                    This link will expire in <strong>15 minutes</strong>.
                    <br>
                    <br>
                    If this wasn't you, please ignore this email.
                    <br><br>
                    <a target="_blank" href="{$link}">RESET YOUR PASSWORD</a>
                    <br><br>
                    <strong>
                        Cheers,
                        <br>
                        Pleasant Tours
                    </strong>
                BODY,
                "
                    {$username},

                    A password reset request has just been for with your account. Please open the link below in order to reset your password.
                    This link will expire in 15 minutes.

                    If this wasn't you, please ignore this email.

                    RESET YOUR PASSWORD: {$link}

                    Cheers,
                    Pleasant Tours
                ",
            )) {
                //
            } else {
                $connection -> prepare("
                    INSERT INTO ResetPasswordMembers
                    (ID, Member)
                    VALUES (:id, :member);
                ") -> execute([
                    "id" => $reset_password_id,
                    "member" => $member_id,
                ]);

                //
            }
        }
    }

    render_template("authentication", [
        "title" => "Forgot Password",
        "content" => <<<CONTENT
            <form method="post" action="{$script}">
                <select name="method">
                    <option>select</option>
                    <option value="username">username</option>
                    <option value="email">email</option>
                </select>
                <input type="text" name="username">
                <input type="email" name="email">
                <input type="submit" name="submit">
            </form>
        CONTENT,
    ]);
?>