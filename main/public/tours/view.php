<?php
    require_once "../../global.php";

    $queries = get_queries();

    $id = $queries["id"];

    if (!isset($id)) {
        redirect("list.php");
    } else {
        if (!validate_id($id)) {
            redirect("list.php");
        } else {
            $connection = connect();

            $statement = $connection -> prepare("
                SELECT * FROM Tours WHERE ID = :id LIMIT 1;
            ");

            $statement -> execute(["id" => $id]);

            if ($statement -> rowCount() === 0) {
                redirect("list.php");
            } else {
                $tour = $statement -> fetch();
            }

            render_template("regular", [
                "title" => "View Tour",
                "navigation_bar" => true,
                "footer" => true,
                "resources" => <<<HTML
                HTML,
                "content" => <<<HTML
                    <div class="container" style="display:flex;flex-direction:;margin-top:30px;margin-bottom:50px">
                        <a href="list.php">Back to Tours</a>
                        <div>
                            content
                        </div>
                        <div>
                            sidebar
                        </div>
                    </div>
                HTML,
            ]);
        }
    }
?>