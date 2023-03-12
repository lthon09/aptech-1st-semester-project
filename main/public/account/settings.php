<?php
    require_once "../../global.php";

    logged_in_only();

    $queries = get_queries();

    $error = (isset($queries["deleteAccountError"])) ? "Couldn't delete your account: Invalid credentials entered" : "";

    render_template("regular", [
        "title" => "Settings",
        "resources" => <<<HTML
            <link rel="stylesheet" href="/static/frontend/css/hystmodal.min.css">
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

                .hystmodal--simple .hystmodal__window{
                    position: relative;
                    overflow: visible;
                    border-radius: 4px;
                    padding: 30px 30px;
                    text-align:left;
                }

                .hystmodal__window--form{
                    width: 440px;
                    max-width: 100%;
                    background: linear-gradient(216.25deg, #FFFFFF 0%, #F3F3F3 100%), #FCFCFC;
                    box-shadow: 0px 0px 6px rgba(51, 66, 94, 0.5);
                    padding: 7rem 9rem;
                }

                .loginblock__h1{
                    margin-bottom: 30px;
                    color: #2E3C56;
                    font-weight: 600;
                    font-size: 26px;
                    line-height: 36px;
                }

                .loginblock__h2{
                    margin-top: 4px;
                    color: #8F99AA;
                    font-size: 14px;
                    line-height: 21px;
                }

                .loginblock__form{
                    padding-top: 36px;
                }

                .formitem{
                    padding-bottom: 24px;
                    position: relative;
                }

                .formitem input{
                    outline: none;
                    background: #FFFFFF;
                    box-shadow: inset 0 0 0 100px #fff;
                    border: 1px solid #DDE0E6;
                    box-sizing: border-box;
                    color: #484c53;
                    font-size: 15px;
                    line-height: 26px;
                    padding: 15px 20px;
                    box-sizing: border-box;
                    display: block;
                    width: 100%;
                }

                .formitem input:focus{
                    border-color:#9d9fa3;
                }

                .loginblock__bottom{
                    justify-content: space-between;
                    padding-bottom: 24px;
                }

                .formcheckbox{
                    position: relative;
                    display: block;
                    cursor: pointer;
                }

                .formcheckbox input{
                    position: absolute;
                    top:0;
                    left: 0;
                    opacity: 0;
                    pointer-events: none;
                }

                .formcheckbox:focus-within{
                    outline: 2px dotted #afb3b9;
                    outline-offset:2px;
                }

                .formcheckbox input+span{
                    height: 21px;
                    color: #8F99AA;
                    font-size: 12px;
                    line-height: 21px;
                    position: relative;
                    align-items: center;
                    user-select: none;
                }

                .formcheckbox input:checked + span{
                    color: #2E3C56;
                }

                .checkplace{
                    display: flex;
                    background: #FFFFFF;
                    border: 1px solid #DDE0E6;
                    box-sizing: border-box;
                    width: 18px;
                    height: 18px;
                    margin-right: 1rem;
                }

                .checkplace img{
                    display: block;
                    max-width: 90%;
                    max-height: 95%;
                    margin: auto;
                    opacity: 0;
                }

                .formcheckbox input:checked + span .checkplace{
                    border-color:#9d9fa3;
                }

                .formcheckbox input:checked + span .checkplace img{
                    opacity: 1;
                }

                .loginblock__link{
                    display: block;
                    color:#2E3C56;
                    font-size: 12px;
                    line-height: 21px;
                }

                .formsubmit .button{
                    width: 100%;
                    height: 56px;
                    font-weight: 500;
                }
            </style>
        HTML,
        "content" => <<<HTML
            <div class="hystmodal hystmodal--simple" id="delete-account-modal">
                <div class="hystmodal__wrap">
                    <div class="hystmodal__window hystmodal__window--form">
                        <button class="hystmodal__close" data-hystclose>Close</button>
                        <div class="hystmodal__styled">
                            <div class="loginblock__h1">Delete Your Account</div>
                            <form method="post" action="/account/delete.php">
                                <span>Please enter your password in order to delete your account:</span>
                                <div class="formitem" style="padding-top:10px">
                                    <input name="password" type="password" placeholder="Your Password">
                                </div>
                                <div class="formsubmit" style="padding-bottom:15px">
                                    <input class="button delete-account" type="submit" value="Delete Account">
                                </div>
                                <span>Please keep in mind all of your data (Your account, your reviews, ...) will get deleted if you continue!</span>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container" style="text-align:left;margin-top:30px;margin-bottom:50px">
                <h1 class="text-spacing-25 font-weight-normal" style="font-size:50px;margin-bottom:20px">Settings</h1>
                <div class="container">
                    <span style="color:red">{$error}</span>
                    <div class="group-md">
                        <a class="button change-password" href="/account/change_password.php">Change Your Password</a>
                        <a class="button delete-account" href="#" data-hystmodal="#delete-account-modal">Delete Your Account</a>
                    </div>
                </div>
            </div>
            <script src="/static/frontend/js/hystmodal.min.js"></script>
            <script>const deleteAccountModal = new HystModal();</script>
        HTML,
    ]);
?>