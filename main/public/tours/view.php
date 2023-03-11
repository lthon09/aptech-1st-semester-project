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
            $comments = "";

            if (!is_logged_in()) {
                $current_page = urlencode(get_server() . $script . "?id={$id}");

                $create = <<<HTML
                    <a href="/authentication/log_in.php?destination={$current_page}">Log In</a> to review on this tour.
                HTML;
            } else {
                $create = <<<HTML
                HTML; // TODO: create comment form
            }

            // TODO: list comments

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
                        <a href="list.php" style="display:flex;flex-direction:row;align-items:center;gap:7.5px;color:gray">
                            <span>←</span>
                            <span>Back to Tours</span>
                        </a>
                        <div style="margin-top:30px;margin-bottom:20px">
                            <h4 class="product-title">{$name}, {$country}</h4>
                            <span style="color:gray;font-size:16px">{$category}</span>
                        </div>
                        <div style="display:flex;flex-direction:row;gap:40px">
                            <div style="width:70%">
                                <figure>
                                    <div class="unit flex-column flex-md-column align-items-md-stretch">
                                        <div class="unit-left">
                                            <img class="product-figure"
                                                src="{$avatar}" alt="" width="100%" height="100%" />
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
                                <div class="container" style="margin-top:15px">
                                    <div>{$create}</div>
                                    <div>{$comments}</div>
                                </div>
                            </div>
                            <div class="bg-gray-4" style="text-align:center;width:30%;padding-top:175px;padding-bottom:200px">
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