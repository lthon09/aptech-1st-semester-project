<?php
    require_once "../../global.php";

    logged_in_only();

    if (isset($_POST["password"])) {
        $password = $_POST["password"];

        if (!validate_password($password)) {
            redirect("/account/settings.php?deleteAccountError");
        } else {
            if (!password_verify($password, $member["Password"])) {
                redirect("/account/settings.php?deleteAccountError");
            } else {
                $username = $member["Username"];
                $connection = connect();

                $connection -> prepare("
                    DELETE FROM Members WHERE Username = :username LIMIT 1;
                ") -> execute(["username" => $username]);

                foreach(large_query("Reviews", "WHERE Author = ?", [$username]) as $review) {
                    $connection -> prepare("
                        DELETE FROM Reviews WHERE ID = :id LIMIT 1;
                    ") -> execute(["id" => $review["ID"]]);
                }

                log_out();

                redirect("/");
            }
        }
    }
?>