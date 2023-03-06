<?php
    require_once "../../global.php";

    not_logged_in_only();

    $queries = get_queries();

    if (!isset($queries["id"])) {
        redirect("/");
    } else {
        $id = $queries["id"];

        if (!validate_id($id)) {
            redirect("/");
        } else {
            $connection = connect();

            $statement = $connection -> prepare("
                SELECT * FROM UnverifiedMembers WHERE ID = :id LIMIT 1;
            ");

            $statement -> execute(["id" => $id]);

            if ($statement -> rowCount() === 0) {
                redirect("/");
            } else {
                $member = $statement -> fetch();

                $username = $member["Username"];
                $password = $member["Password"];
                $email = $member["Email"];

                $statement1= $connection -> prepare("
                    SELECT * FROM Members WHERE Username = :username LIMIT 1;
                ");
                $statement2 = $connection -> prepare("
                    SELECT * FROM Members WHERE Email = :email LIMIT 1;
                ");

                $statement1 -> execute(["username" => $username]);
                $statement2 -> execute(["email" => $email]);

                if ($statement1 -> rowCount() !== 0) {
                    echo "This username has already been taken!";
                } elseif ($statement2 -> rowCount() !== 0) {
                    echo "This email has already been taken!";
                } else {
                    $connection -> prepare("
                        DELETE FROM UnverifiedMembers WHERE ID = :id;
                    ") -> execute(["id" => $id]);

                    $connection -> prepare("
                        INSERT INTO Members
                        (Username, `Password`, Email, Administrator)
                        VALUES (:username, :password, :email, FALSE);
                    ") -> execute([
                        "username" => $username,
                        "password" => $password,
                        "email" => $email,
                    ]);

                    redirect("log_in.php");
                }
            }
        }
    }
?>