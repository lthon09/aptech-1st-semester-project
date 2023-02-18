<?php
    require_once "../../global.php";

    $queries = [];
    parse_str($_SERVER["QUERY_STRING"], $queries);

    if (!isset($queries["id"])) {
        //
    } else {
        $unverified_id = $queries["id"];

        foreach (mb_str_split($unverified_id) as $character) {
            if (!str_contains(ID_CHARACTERS, $character)) {
                //
            }
        }

        $connection = connect();

        $statement = $connection -> prepare("
            SELECT Username, `Password`, Email
            FROM UnverifiedMembers
            WHERE ID = :id
            LIMIT 1;
        ");

        $statement -> execute(["id" => $unverified_id]);

        if ($statement -> rowCount() === 0) {
            //
        } else {
            $verified_id = generate_id(IDS["lengths"]["member"], "Members");

            if ($verified_id === false) {
                //
            }

            $member = $statement -> fetch();

            $username = $member["Username"];
            $password = $member["Password"];
            $email = $member["Email"];

            $connection -> prepare("
                DELETE FROM UnverifiedMembers WHERE ID = :id;
            ") -> execute(["id" => $unverified_id]);

            $connection -> prepare("
                INSERT INTO Members
                (ID, Username, `Password`, Email, Administrator)
                VALUES (:id, :username, :password, :email, FALSE);
            ") -> execute([
                "id" => $verified_id,
                "username" => $username,
                "password" => $password,
                "email" => $email,
            ]);

            //
        }
    }
?>