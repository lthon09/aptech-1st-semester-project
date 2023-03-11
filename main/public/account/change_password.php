<?php
    require_once "../../global.php";

    logged_in_only();

    $message_color = "";
    $message = "";

    if (isset($_POST["submit"])) {
        $username = $member["Username"];

        $old_password = $_POST["old_password"];

        $new_password = $_POST["new_password"];
        $confirm_new_password = $_POST["confirm_new_password"];

        if ($new_password !== $confirm_new_password) {
            $message_color = "red";
            $message = "The entered new passwords don't match!";
        } else {
            if (!validate_password($old_password)) {
                $message_color = "red";
                $message = "Invalid old password entered!";
            } else {
                $connection = connect();

                $statement = $connection -> prepare("
                    SELECT * FROM Members WHERE Username = :username LIMIT 1;
                ");

                $statement -> execute(["username" => $username]);

                if ($statement -> rowCount() === 0) {
                    $message_color = "red";
                    $message = "Something went wrong.";
                } else {
                    $current_password = ($statement -> fetch())["Password"];

                    if (!password_verify($old_password, $current_password)) {
                        $message_color = "red";
                        $message = "Invalid old password entered!";
                    } else {
                        $connection -> prepare("
                            UPDATE Members SET `Password` = :password WHERE Username = :username;
                        ") -> execute([
                            "password" => hash_password($new_password),
                            "username" => $username,
                        ]);
                    }
                }
            }
        }
    }

    render_template("form", [
        "title" => "Change Password",
        "content" => <<<HTML
            <div class="signin-content">
                <div class="signin-form">
                    <h2 class="form-title">Change Password</h2>
                    <form method="POST" class="register-form" id="register-form" action="{$script}">
                        <div class="form-group">
                            <label for="password"><i class="zmdi zmdi-lock"></i></label>
                            <input type="password" name="old_password" id="pass" placeholder="Old Password" />
                        </div>
                        <div class="form-group">
                            <label for="password"><i class="zmdi zmdi-lock"></i></label>
                            <input type="password" name="new_password" id="pass" placeholder="New Password" />
                        </div>
                        <div class="form-group">
                            <label for="confirm_new_password"><i class="zmdi zmdi-lock-outline"></i></label>
                            <input type="password" name="confirm_new_password" id="re_pass" placeholder="Confirm New Password" />
                        </div>
                        <div class="form-group form-button">
                            <input type="submit" name="submit" id="signin" class="form-submit" value="Change Password" />
                        </div>
                        <div class="form-group">
                            <span style="color:{$message_color}">{$message}</span>
                        </div>
                    </form>
                </div>
            </div>
        HTML,
    ]);
?>