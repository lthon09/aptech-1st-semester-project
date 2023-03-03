<?php
    require_once "../global.php";

    echo render_template("base", [
        "title" => "Home",
        "navigation_bar" => true,
        "footer" => true,
        "content" => <<<CONTENT
        CONTENT,
    ]);
?>