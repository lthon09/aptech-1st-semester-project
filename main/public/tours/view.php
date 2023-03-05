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

                $statement1 = $connection -> prepare("
                    SELECT * FROM Categories WHERE ID = :id LIMIT 1;
                ");
                $statement2 = $connection -> prepare("
                    SELECT * FROM Countries WHERE ID = :id LIMIT 1;
                ");

                $statement1 -> execute(["id" => $tour["Category"]]);
                $statement2 -> execute(["id" => $tour["Country"]]);

                $id = htmlentities($tour["ID"]);
                $avatar = $tour["Avatar"];
                $name = htmlentities($tour["Name"]);
                $category = htmlentities(($statement1 -> fetch())["Name"]);
                $country = htmlentities(($statement2 -> fetch())["Name"]);
                $description = htmlentities($tour["LongDescription"]);
                $original_price = $tour["Price"];
                $sale = $tour["Sale"];
                $_original_price = "$" . htmlentities(format_price($original_price, (int)$original_price, $original_price));

                $style = "";
                $_sale = "";

                if ($sale !== 0) {
                    $discounted_price = "$" . calculate_price($original_price, $sale);

                    $_sale = <<<HTML
                        <span style="color:#d3d3d3;text-decoration:line-through;font-size:15px">{$_original_price}</span>
                    HTML;

                    $_price = <<<HTML
                        <span style="color:red;font-weight:bold">{$discounted_price}</span>
                    HTML;
                } else {
                    $style = "justify-content:center";
                    $_price = $_original_price;
                }
            }

            render_template("regular", [
                "title" => "View Tour",
                "navigation_bar" => true,
                "footer" => true,
                "resources" => <<<HTML
                HTML,
                "content" => <<<HTML
                    <div class="container" style="text-align:left;word-wrap:break-word;margin-top:30px;margin-bottom:50px">
                        <a href="list.php" style="display:flex;flex-direction:row;align-items:center;gap:5px;color:gray">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="bi bi-house-fill" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6zm5-.793V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z"/> <path fill-rule="evenodd" d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z"/> </svg>
                            Back to Tours
                        </a>
                        <div style="margin-top:30px;margin-bottom:20px">
                            <h4 class="product-title">{$name}, {$country}</h4>
                            <span style="color:gray;font-size:16px">{$category}</span>
                        </div>
                        <div style="display:flex;flex-direction:row;gap:70px">
                            <div style="width:70%">
                                <figure>
                                    <div class="unit flex-column flex-md-column align-items-md-stretch">
                                        <div class="unit-left">
                                            <img class="product-figure"
                                                src="/static/assets/images/tours/{$avatar}" alt="" width="100%" height="100%" />
                                        </div>
                                        <div class="unit-body">
                                            <div class="product-body">
                                                <div class="product-text" style="display:flex;flex-direction:column;gap:20px;margin-top:10px">
                                                    <p>{$description}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </figure>
                                <h6 style="margin-top:40px">
                                    <i class="fa fa-comments" style="font-size:25px;margin-right:7px"></i>
                                    Reviews
                                </h6>
                                <!-- TODO: reviews -->
                            </div>
                            <div class="bg-gray-4" style="text-align:center;width:30%;padding-top:175px">
                                <div class="product-price-wrap">
                                    <span class="product-price" style="display:flex;flex-direction:row;justify-content:center;gap:50px">
                                        {$_sale}
                                        {$_price}
                                    </span>
                                </div>
                                <a class="button button-secondary button-pipaluk" href="get/document.php?id={$id}">Learn More</a>
                            </div>
                        </div>
                    </div>
                HTML,
            ]);
        }
    }
?>