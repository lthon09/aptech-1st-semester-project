<?php
    require_once "../../global.php";

    logged_in_only();

    if (isset($_POST["password"])) {
        $password = $_POST["password"];

        if (!validate_password($password)) {
            echo "error";
        } else {
            if (!password_verify($password, $member["Password"])) {
                echo "error";
            } else {
                $username = $member["Username"];

                // connect() -> prepare("
                //     DELETE FROM Members WHERE Username = :username LIMIT 1;
                // ") -> execute(["username" => $username]);

                foreach(large_query("Reviews", "WHERE Author = ?", [$username]) as $review) {
                    echo $review;
                }

                log_out();

                redirect("/");
            }
        }
    }
?>