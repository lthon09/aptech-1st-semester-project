<?php
    require_once "../../global.php";

    not_logged_in_only();

    $message_color = "";
    $message = "";

    if (isset($_POST["submit"])) {
        $username = $_POST["username"];

        $email = $_POST["email"];

        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];

        if ($password !== $confirm_password) {
            $message_color = "red";
            $message = "The entered passwords don't match!";
        } else {
            if (!validate_credentials($username, $password) || !validate_email($email)) {
                $message_color = "red";
                $message = "Invalid credentials entered! (The username must be 2-20 characters and the password must be 8-40 characters)";
            } else {
                $connection = connect();

                $statement1= $connection -> prepare("
                    SELECT * FROM Members WHERE Username = :username LIMIT 1;
                ");
                $statement2 = $connection -> prepare("
                    SELECT * FROM Members WHERE Email = :email LIMIT 1;
                ");

                $statement1 -> execute(["username" => $username]);
                $statement2 -> execute(["email" => $email]);

                if ($statement1 -> rowCount() !== 0) {
                    $message_color = "red";
                    $message = "This username is unavailable!";
                } elseif ($statement2 -> rowCount() !== 0) {
                    $message_color = "red";
                    $message = "This email is unavailable!";
                } else {
                    $hashed_password = hash_password($password);

                    if ($hashed_password === false) {
                        $message_color = "red";
                        $message = "Something went wrong, please try again.";
                    } else {
                        $id = generate_id(IDS["lengths"]["secure"], "UnverifiedMembers");

                        if ($id === false) {
                            $message_color = "red";
                            $message = "Something went wrong, please try again.";
                        } else {
                            $link = get_directory() . "/verify.php?id=" . $id;

                            if (!send_mail($email, "Confirm Your Email Address",
                                <<<HTML
                                    <strong>{$username}</strong>,
                                    <br><br>
                                    It looks like a Pleasant Tours account has just been created using your email address. Please verify this by opening the link below.
                                    <br>
                                    This link will expire in <strong>15 minutes</strong>.
                                    <br>
                                    <br>
                                    If this wasn't you, please ignore this email.
                                    <br><br>
                                    <a target="_blank" href="{$link}">CONFIRM YOUR EMAIL ADDRESS</a>
                                    <br><br>
                                    <strong>
                                        Cheers,
                                        <br>
                                        Pleasant Tours
                                    </strong>
                                HTML,
                                "
                                    {$username},

                                    It looks like a Pleasant Tours account has just been created using your email address. Please verify this by clicking on the link below.
                                    This link will expire in 15 minutes.

                                    If this wasn't you, please ignore this email.

                                    CONFIRM YOUR EMAIL ADDRESS: {$link}

                                    Cheers,
                                    Pleasant Tours
                                ",
                            )) {
                                $message_color = "red";
                                $message = "Something went wrong, please try again.";
                            } else {
                                $connection -> prepare("
                                    INSERT INTO UnverifiedMembers
                                    (ID, Username, Email, `Password`)
                                    VALUES (:id, :username, :email, :password);
                                ") -> execute([
                                    "id" => $id,
                                    "username" => $username,
                                    "email" => $email,
                                    "password" => $hashed_password,
                                ]);

                                $message_color = "#00ff00";
                                $message = "Please check your email for an email in order to confirm your membership! (Make sure to check all the folders)";
                            }
                        }
                    }
                }
            }
        }
    }

    render_template("form", [
        "title" => "Sign Up",
        "content" => <<<HTML
            <div class="signin-content">
                <div class="signin-form">
                    <h2 class="form-title">Sign Up</h2>
                    <form method="POST" class="register-form" id="register-form" action="{$script}">
                        <div class="form-group">
                            <label for="username"><i class="zmdi zmdi-account material-icons-name"></i></label>
                            <input type="text" name="username" id="name" placeholder="Username" />
                        </div>
                        <div class="form-group">
                            <label for="email"><i class="zmdi zmdi-email"></i></label>
                            <input type="email" name="email" id="email" placeholder="Email" />
                        </div>
                        <div class="form-group">
                            <label for="password"><i class="zmdi zmdi-lock"></i></label>
                            <input type="password" name="password" id="pass" placeholder="Password" />
                        </div>
                        <div class="form-group">
                            <label for="confirm_password"><i class="zmdi zmdi-lock-outline"></i></label>
                            <input type="password" name="confirm_password" id="re_pass" placeholder="Confirm Password" />
                        </div>
                        <div class="form-group form-button">
                            <input type="submit" name="submit" id="signin" class="form-submit" value="Sign Up" />
                        </div>
                        <div class="form-group">
                            <span style="color:{$message_color}">{$message}</span>
                        </div>
                        <div class="form-group">
                            <a href="log_in.php" class="signup-image-link" style="text-align:left;margin-top:10px">I'm Already A Member</a>
                        </div>
                    </form>
                </div>
            </div>
        HTML,
    ]);
?>