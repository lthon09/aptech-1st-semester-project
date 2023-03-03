<?php
    require_once "../../global.php";

    not_logged_in_only();

    $query_string = $_SERVER["QUERY_STRING"];

    $queries = [];
    parse_str($query_string, $queries);

    if (!isset($queries["id"])) {
        //
    } else {
        $id = $queries["id"];

        if (!validate_id($id)) {
            //
        }

        $connection = connect();

        $statement = $connection -> prepare("
            SELECT * FROM ResetPasswordMembers WHERE ID = :id LIMIT 1;
        ");

        $statement -> execute(["id" => $id]);

        if ($statement -> rowCount() === 0) {
            //
        } else {
            if (!isset($_POST["submit"])) {
                render_template("base", [
                    "title" => "Reset Password",
                    "content" => <<<CONTENT
                        <form method="post" action="{$script}?{$query_string}">
                            <input type="password" name="new_password">
                            <input type="password" name="confirm_new_password">
                            <input type="submit" name="submit">
                        </form>
                    CONTENT,
                ]);
            } else {
                $password = $_POST["new_password"];
                $confirm_new_password = $_POST["confirm_new_password"];

                if ($password !== $confirm_new_password) {
                    //
                } else {
                    if (!validate_password($password)) {
                        //
                    } else {
                        $hashed_password = hash_password($password);

                        if ($hashed_password === false) {
                            //
                        }

                        $member = ($statement -> fetch())["Member"];
        
                        $connection -> prepare("
                            DELETE FROM ResetPasswordMembers WHERE ID = :id;
                        ") -> execute(["id" => $id]);

                        $connection -> prepare("
                            UPDATE Members
                            SET `Password` = :password
                            WHERE ID = :id;
                        ") -> execute([
                            "id" => $member,
                            "password" => $hashed_password,
                        ]);

                        redirect("log_in.php");
                    }
                }
            }
        }
    }
?>