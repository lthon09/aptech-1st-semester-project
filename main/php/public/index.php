<?php
    require_once "../dependencies/loaders/mustache.php";

    echo $mustache -> render("base", array(
        "title" => "Home",
        "content" => <<<"CONTENT"
        CONTENT,
    ));
?>