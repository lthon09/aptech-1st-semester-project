<?php
    require_once "../../global.php";

    not_logged_in_only();

    $query_string = $_SERVER["QUERY_STRING"];

    $queries = [];
    parse_str($query_string, $queries);

    if (!isset($queries["id"])) {
        redirect("/");
    } else {
        $id = $queries["id"];

        if (!validate_id($id)) {
            redirect("/");
        } else {
            $connection = connect();

            $statement = $connection -> prepare("
                SELECT * FROM ResetPasswordMembers WHERE ID = :id LIMIT 1;
            ");

            $statement -> execute(["id" => $id]);

            if ($statement -> rowCount() === 0) {
                redirect("/");
            } else {
                $message_color = "";
                $message = "";

                if (isset($_POST["submit"])) {
                    $password = $_POST["new_password"];
                    $confirm_new_password = $_POST["confirm_new_password"];

                    if ($password !== $confirm_new_password) {
                        $message_color = "red";
                        $message = "The entered passwords don't match!";
                    } else {
                        if (!validate_password($password)) {
                            $message_color = "red";
                            $message = "Invalid credentials entered! (The password must be 8-40 characters)";
                        } else {
                            $hashed_password = hash_password($password);

                            if ($hashed_password === false) {
                                $message_color = "red";
                                $message = "Something went wrong, please try again.";
                            } else {
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

                render_template("authentication", [
                    "title" => "Reset Password",
                    "content" => <<<CONTENT
                        <div class="signin-content">
                            <div class="signin-form">
                                <h2 class="form-title">Reset Password</h2>
                                <form method="POST" class="register-form" id="login-form" action="{$script}?{$query_string}">
                                    <div class="form-group">
                                        <label for="new_password"><i class="zmdi zmdi-lock"></i></label>
                                        <input type="password" name="new_password" id="pass" placeholder="New Password" />
                                    </div>
                                    <div class="form-group">
                                        <label for="confirm_new_password"><i class="zmdi zmdi-lock-outline"></i></label>
                                        <input type="password" name="confirm_new_password" id="re_pass" placeholder="Confirm New Password" />
                                    </div>
                                    <div class="form-group form-button">
                                        <input type="submit" name="submit" id="signup" class="form-submit" value="Reset Password" />
                                    </div>
                                    <div class="form-group">
                                        <span style="color:{$message_color}">{$message}</span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    CONTENT,
                ]);
            }
        }
    }
?>