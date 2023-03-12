<?php
    require_once "../../global.php";

    not_logged_in_only();

    $message_color = "";
    $message = "";

    $query_string = $_SERVER["QUERY_STRING"];
    $_query_string = ($query_string !== "") ? "?$query_string" : "";

    if (isset($_POST["submit"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        if (!validate_credentials($username, $password)) {
            $message_color = "red";
            $message = "Invalid credentials entered!";
        } else {
            $connection = connect();

            $statement = $connection -> prepare("
                SELECT * FROM Members WHERE Username = :username LIMIT 1;
            ");

            $statement -> execute(["username" => $username]);

            if ($statement -> rowCount() === 0) {
                $message_color = "red";
                $message = "Invalid credentials entered!";
            } else {
                $hashed_password = ($statement -> fetch())["Password"];

                if (!password_verify($password, $hashed_password)) {
                    $message_color = "red";
                    $message = "Invalid credentials entered!";
                } else {
                    if (password_needs_rehash($password, HASH["algorithm"], HASH["options"])) {
                        $hashed_password = hash_password($password);

                        $connection -> prepare("
                            UPDATE Members SET `Password` = :password WHERE Username = :username LIMIT 1;
                        ") -> execute([
                            "password" => $hashed_password,
                            "username" => $username,
                        ]);
                    }

                    setcookie(
                        "member",
                        base64_encode("$username|$hashed_password"),
                        (isset($_POST["remember"])) ? time() + (86400 * 30) : 0,
                        "/",
                        "",
                        false,
                        true,
                    );

                    $queries = get_queries();

                    redirect( // FIXME: this isnt redirecting to the destination
                        (isset($queries["destination"]) && $queries["destination"] !== "")
                            ? urldecode($queries["destination"])
                            : "/"
                    );
                }
            }
        }
    }

    render_template("form", [
        "title" => "Log In",
        "content" => <<<HTML
            <div class="signin-content">
                <div class="signin-form">
                    <h2 class="form-title">Log In</h2>
                    <form method="POST" class="register-form" id="login-form" action="{$script}">
                        <div class="form-group">
                            <label for="username"><i class="zmdi zmdi-account material-icons-name"></i></label>
                            <input type="text" name="username" id="your_name" placeholder="Username" />
                        </div>
                        <div class="form-group">
                            <label for="password"><i class="zmdi zmdi-lock"></i></label>
                            <input type="password" name="password" id="your_pass" placeholder="Password" />
                        </div>
                        <div class="form-group">
                            <input type="checkbox" name="remember" id="remember" class="agree-term" />
                            <label for="remember" class="label-agree-term"><span><span></span></span>Remember Me</label>
                        </div>
                        <div class="form-group form-button">
                            <input type="submit" name="submit" id="signin" class="form-submit" value="Log In" />
                        </div>
                        <div class="form-group">
                            <span style="color:{$message_color}">{$message}</span>
                        </div>
                        <div class="form-group" style="margin-top:10px">
                            <a href="forgot_password.php" class="signup-image-link" style="text-align:left;margin-bottom:5px">I Forgot My Password</a>
                            <a href="sign_up.php" class="signup-image-link" style="text-align:left">I'm Not A Member Yet</a>
                        </div>
                    </form>
                </div>
            </div>
        HTML,
    ]);
?>