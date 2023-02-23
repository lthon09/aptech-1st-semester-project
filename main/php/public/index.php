<?php
    require_once "../global.php";

    echo $mustache -> render("base", [
        "title" => "Home",
        "navigation_bar" => true,
        "footer" => true,
        "content" => <<<CONTENT
        CONTENT,
    ]);
?>