<?php
    require_once "../../global.php";

    not_logged_in_only();

    $message_color = "";
    $message = "";

    if (isset($_POST["submit"])) {
        $method = $_POST["method"];

        $username = $_POST["username"];
        $email = $_POST["email"];

        switch ($method) {
            case "username":
                if (!validate_username($username)) {
                    $message_color = "red";
                    $message = "Invalid credentials entered!";
                }

                break;
            case "email":
                if (!validate_email($email)) {
                    $message_color = "red";
                    $message = "Invalid credentials entered!";
                }

                break;
            default:
                $message_color = "red";
                $message = "Invalid input method!";

                break;
        }

        $connection = connect();

        $statement = $connection -> prepare(
            "SELECT * FROM Members WHERE "
            . strtoupper($method)
            . " = :input LIMIT 1;"
        );

        $statement -> execute(["input" => $$method]);

        if ($statement -> rowCount() === 0) {
            $message_color = "red";
            $message = "Invalid credentials entered!";
        } else {
            $member = $statement -> fetch();

            $member_id = $member["ID"];
            $username = $member["Username"];
            $email = $member["Email"];

            $reset_password_id = generate_id(IDS["lengths"]["secure"], "ResetPasswordMembers");

            $link = get_directory() . "/reset_password.php?id=" . $reset_password_id;

            if(!send_mail($email, "Reset Your Password",
                <<<BODY
                    <strong>{$username}</strong>,
                    <br><br>
                    A password reset request has just been for with your account. Please click on the link below in order to reset your password.
                    <br>
                    This link will expire in <strong>15 minutes</strong>.
                    <br>
                    <br>
                    If this wasn't you, please ignore this email.
                    <br><br>
                    <a target="_blank" href="{$link}">RESET YOUR PASSWORD</a>
                    <br><br>
                    <strong>
                        Cheers,
                        <br>
                        Pleasant Tours
                    </strong>
                BODY,
                "
                    {$username},

                    A password reset request has just been for with your account. Please open the link below in order to reset your password.
                    This link will expire in 15 minutes.

                    If this wasn't you, please ignore this email.

                    RESET YOUR PASSWORD: {$link}

                    Cheers,
                    Pleasant Tours
                ",
            )) {
                $message_color = "red";
                $message = "Something went wrong.";
            } else {
                $connection -> prepare("
                    INSERT INTO ResetPasswordMembers
                    (ID, Member)
                    VALUES (:id, :member);
                ") -> execute([
                    "id" => $reset_password_id,
                    "member" => $member_id,
                ]);

                $message_color = "#00ff00";
                $message = "Please check your email for an email in order to reset your password! (Make sure to check all the folders)";
            }
        }
    }

    render_template("authentication", [
        "title" => "Forgot Password",
        "style" => <<<STYLE
            <style>
                .select-dropdown {
                    position: relative;
                    background-color: #e6e6e6;
                    width: min-content;
                    max-width: 100%;
                    border-radius: 2px;
                }

                .select-dropdown:after {
                    content: " ";
                    position: absolute;
                    top: 50%;
                    margin-top: -2px;
                    right: 8px;
                    width: 0;
                    height: 0;
                    border-left: 5px solid transparent;
                    border-right: 5px solid transparent;
                    border-top: 5px solid #aaa;
                }

                select {
                    font-size: 14px;
                    max-width: 100%;
                    padding: 8px 24px 8px 10px;
                    border: none;
                    background-color: transparent;
                    -webkit-appearance: none;
                    -moz-appearance: none;
                    appearance: none;
                }

                select:active,
                select:focus {
                    outline: none;
                    box-shadow: none;
                }

                #input-username, #input-email {
                    display: none;
                }
            </style>
        STYLE,
        "content" => <<<CONTENT
            <div class="signin-content">
                <div class="signin-form">
                    <h2 class="form-title">Forgot Password</h2>
                    <form method="POST" class="register-form" id="login-form" action="{$script}">
                        <div class="form-group">
                            <div class="select-dropdown">
                                <select name="method">
                                    <option>Select An Input Method</option>
                                    <option value="username">Username</option>
                                    <option value="email">Email</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="input-username">
                            <label for="username"><i class="zmdi zmdi-account material-icons-name"></i></label>
                            <input type="text" name="username" id="your_name" placeholder="Username" />
                        </div>
                        <div class="form-group" id="input-email">
                            <label for="email"><i class="zmdi zmdi-email"></i></label>
                            <input type="email" name="email" id="email" placeholder="Email" />
                        </div>
                        <div class="form-group form-button">
                            <input type="submit" name="submit" id="signin" class="form-submit" value="Reset Password" />
                        </div>
                        <div class="form-group">
                            <span style="color:{$message_color}">{$message}</span>
                        </div>
                    </form>
                </div>
            </div>
            <script>
                const method = document.getElementsByName("method")[0];
                const values = ["username", "email"];

                method.addEventListener("change", () => {
                    for (const input of values) {
                        document.getElementById("input-" + input).style = "";
                    }

                    const value = method.value;

                    if (values.includes(value)) {
                        document.getElementById("input-" + value).style = "display:block";
                    }
                });
            </script>
        CONTENT,
    ]);
?>