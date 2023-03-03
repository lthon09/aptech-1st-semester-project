<?php
    require_once "../../global.php";

    not_logged_in_only();

    $query_string = $_SERVER["QUERY_STRING"];
    $_query_string = ($query_string !== "") ? "?$query_string" : "";

    if (isset($_POST["submit"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        if (!validate_credentials($username, $password)) {
            //
        } else {
            $connection = connect();

            $statement = $connection -> prepare("
                SELECT * FROM Members WHERE Username = :username LIMIT 1;
            ");

            $statement -> execute(["username" => $username]);

            if ($statement -> rowCount() === 0) {
                //
            } else {
                $member = $statement -> fetch();

                $id = $member["ID"];
                $hashed_password = $member["Password"];

                if (!password_verify($password, $hashed_password)) {
                    //
                } else {
                    if (password_needs_rehash($password, HASH["algorithm"], HASH["options"])) {
                        $hashed_password = hash_password($password);

                        $connection -> prepare("
                            UPDATE Members SET `Password` = :password WHERE ID = :id;
                        ") -> execute([
                            "password" => $hashed_password,
                            "id" => $id,
                        ]);
                    }

                    setcookie(
                        "member",
                        base64_encode("$id|$hashed_password"),
                        (isset($_POST["remember"])) ? time() + (86400 * 30) : 0,
                        "/",
                        "",
                        false,
                        true,
                    );

                    $queries = [];
                    parse_str($query_string, $queries);

                    redirect((isset($queries["destination"]))
                        ? urldecode($queries["destination"])
                        : ""
                    );
                }
            }
        }
    }

    render_template("authentication", [
        "title" => "Log In",
        "content" => <<<CONTENT
            <div class="signin-content">
                <div class="signin-form">
                    <h2 class="form-title">Log In</h2>
                    <form method="POST" class="register-form" id="login-form">
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
                            <label for="remember" class="label-agree-term"><span><span></span></span>Remember</label>
                        </div>
                        <div class="form-group form-button">
                            <input type="submit" name="submit" id="signin" class="form-submit" value="Log In" />
                        </div>
                        <div class="form-group">
                            <a href="sign_up.php" class="signup-image-link" style="text-align:left;margin-top:10px">I'm Not A Member</a>
                        </div>
                    </form>
                </div>
            </div>
        CONTENT,
    ]);
?>