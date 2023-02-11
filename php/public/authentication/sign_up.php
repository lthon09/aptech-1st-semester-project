<?php
    require_once "../../utilities.php";

    if (isset($_POST["submit"])) {
        $username = $_POST["username"];

        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];

        if ($password !== $confirm_password) {
            //
        } else {
            if (!validate_credentials($username, $password)) {
                //
            } else {
                $hashed_password = _hash($password);

                if ($hashed_password === false) {
                    //
                } else {
                    $connection = connect();

                    $statement = $connection -> prepare("
                        INSERT INTO Members (ID, Username, `Password`) VALUES (:id, :username, :password);
                    ");

                    $statement -> execute(array(
                        "id" => generate_id(),
                        "username" => $username,
                        "password" => $hashed_password,
                    ));

                    redirect("login.php");
                }
            }
        }
    }
?>