<?php
    require_once "../global.php";

    echo $mustache -> render("base", [
        "title" => "Home",
        "content" => <<<"CONTENT"
        CONTENT,
    ]);
?>