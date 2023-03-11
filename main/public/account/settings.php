<?php
    require_once "../../global.php";

    logged_in_only();

    $username = $member["Username"];

    render_template("regular", [
        "title" => "Settings",
        "resources" => <<<HTML
            <style>
                .button {
                    color: white !important;
                    font-family: "Montserrat", sans-serif, Arial, sans-serif;
                    font-weight: 500;
                    border: none;
                    cursor: pointer;
                    padding-top: 8px;
                    padding-left: 15px;
                    padding-right: 15px;
                    padding-bottom: 8px;
                }

                .button.change-password {
                    background-color: #01b3a7;
                }

                .button.change-password:hover {
                    background-color: #008f85;
                }

                .button.change-password:active {
                    background-color: #007d74;
                }

                .button.delete-account {
                    background-color: #fe0314;
                }

                .button.delete-account:hover {
                    background-color: #e40212;
                }

                .button.delete-account:active {
                    background-color: #b1020e;
                }
            </style>
        HTML,
        "content" => <<<HTML
            <form class="delete-account-form" method="post" action="/account/delete.php" style="display:none">
                <input name="username" value="{$username}">
                <input name="password">
            </form>
            <div class="container" style="text-align:left">
                <h1 class="text-spacing-25 font-weight-normal" style="font-size:50px;margin-bottom:20px">Settings</h1>
                <div class="container group">
                    <a class="button change-password" href="/account/change_password.php">Change Your Password</a>
                    <a class="button delete-account" onclick="delete_account()">Delete Your Account</a>
                </div>
            </div>
            <script>
                function delete_account() {
                    const password = prompt("Enter your password:");

                    if ((password.length === 0) || (!password.trim())) return;

                    document.getElementsByName("password")[0].setAttribute("value", password);
                    document.getElementsByClassName("delete-account-form")[0].submit();
                }
            </script>
        HTML,
    ]);
?>