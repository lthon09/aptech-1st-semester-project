<?php
    require_once "../../../global.php";

    logged_in_only();

    $queries = get_queries();

    if (isset($queries["tour"]) && isset($queries["review"])) {
        $tour = $queries["tour"];
        $review = $queries["review"];

        if (!validate_id($tour) || !validate_id($review)) {
            redirect("/tours/view.php?id={$tour}");
        } else {
            connect() -> prepare("
                DELETE FROM Reviews WHERE ID = :id LIMIT 1;
            ") -> execute(["id" => $review]);

            redirect("/tours/view.php?id={$tour}");
        }
    }
?>