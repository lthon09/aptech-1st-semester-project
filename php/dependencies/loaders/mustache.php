<?php
    require __DIR__ . "/../src/Mustache/Autoloader.php";

    Mustache_Autoloader::register();

    $mustache = new Mustache_Engine(array(
        "loader" => new Mustache_Loader_FilesystemLoader(__DIR__ . "/../../templates", array(
            "extension" => ".html",
        )),
    ));
?>