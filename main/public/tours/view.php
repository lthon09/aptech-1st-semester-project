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
                    <div class="container" style="margin-top:30px;margin-bottom:50px">
                        <a href="list.php" style="display:flex;flex-direction:row;align-items:center;gap:5px;color:gray">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="bi bi-house-fill" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6zm5-.793V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z"/> <path fill-rule="evenodd" d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z"/> </svg>
                            Back to Tours
                        </a>
                        <div style="display:flex;flex-direction:row">
                            <div>
                                content
                            </div>
                            <div>
                                sidebar
                            </div>
                        </div>
                    </div>
                HTML,
            ]);
        }
    }
?>