<?php
    require_once "../../global.php";

    not_logged_in_only();

    $queries = get_queries();

    if (!isset($queries["id"])) {
        redirect("/");
    } else {
        $unverified_id = $queries["id"];

        if (!validate_id($unverified_id)) {
            redirect("/");
        } else {
            $connection = connect();

            $statement = $connection -> prepare("
                SELECT * FROM UnverifiedMembers WHERE ID = :id LIMIT 1;
            ");

            $statement -> execute(["id" => $unverified_id]);

            if ($statement -> rowCount() === 0) {
                redirect("/");
            } else {
                $verified_id = generate_id(IDS["lengths"]["general"], "Members");

                if ($verified_id === false) {
                    echo "Something went wrong, please try again.";
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
                        ") -> execute(["id" => $unverified_id]);

                        $connection -> prepare("
                            INSERT INTO Members
                            (ID, Username, `Password`, Email, Administrator)
                            VALUES (:id, :username, :password, :email, FALSE);
                        ") -> execute([
                            "id" => $verified_id,
                            "username" => $username,
                            "password" => $password,
                            "email" => $email,
                        ]);

                        redirect("log_in.php");
                    }
                }
            }
        }
    }
?>