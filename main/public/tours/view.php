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
                $username = $member["Username"];

                $tour = $statement -> fetch();

                $statement1 = $connection -> prepare("
                    SELECT * FROM Categories WHERE ID = :id LIMIT 1;
                ");
                $statement2 = $connection -> prepare("
                    SELECT * FROM Countries WHERE ID = :id LIMIT 1;
                ");

                $statement1 -> execute(["id" => $tour["Category"]]);
                $statement2 -> execute(["id" => $tour["Country"]]);

                $avatar = htmlentities($tour["Avatar"]);
                $name = htmlentities($tour["Name"]);
                $category = htmlentities(($statement1 -> fetch())["Name"]);
                $country = htmlentities(($statement2 -> fetch())["Name"]);
                $description = htmlentities($tour["LongDescription"]);
                $original_price = $tour["Price"];
                $sale = $tour["Sale"];
                $_original_price = "$" . format_float($original_price, (int)$original_price, $original_price);

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

                $reviews = "";

                $statement = $connection -> prepare("
                    SELECT AVG(Rating) FROM Reviews WHERE Tour = :tour;
                ");

                $statement -> execute(["tour" => $id]);

                $_average = ($statement -> fetch())[0];
                $average = ($_average) ? format_float($_average, (int)$_average, bcadd($_average, 0, 1)) : 0;

                $_reviews = large_query("Reviews", "WHERE Tour = ?", [$id]);

                if (!is_logged_in()) {
                    $current_page = urlencode(get_server() . $script . "?id={$id}");

                    $create = <<<HTML
                        <a href="/authentication/log_in.php?destination={$current_page}">Log in</a> to review on this tour.
                    HTML;
                } else {
                    $statement = $connection -> prepare("
                        SELECT * FROM Reviews WHERE Tour = :tour AND Author = :author LIMIT 1;
                    ");

                    $statement -> execute([
                        "tour" => $id,
                        "author" => $username,
                    ]);

                    if ($statement -> rowCount() !== 0) {
                        $jump_review_id = ($statement -> fetch())["ID"];

                        $create = <<<HTML
                            You've already left a review on this tour: <a href="#review-${jump_review_id}">Jump to your review</a>
                        HTML;
                    } else {
                        $create = <<<HTML
                            <form id="review-form" method="post" action="review/create.php">
                                <div class="form-group">
                                    <textarea class="form-control" name="review" form="review-form" placeholder="Review..." rows="10" style="width:100%!important;resize:none"></textarea>
                                </div>
                                <div class="form-group rating-group">
                                    <label class="label">Rating:</label>
                                    <div class="rating">
                                        <input name="star" id="star5" type="radio" value="5">
                                        <label for="star5"><i class="bi bi-star-fill"></i></label>
                                        <input name="star" id="star4" type="radio" value="4">
                                        <label for="star4"><i class="bi bi-star-fill"></i></label>
                                        <input name="star" id="star3" type="radio" value="3">
                                        <label for="star3"><i class="bi bi-star-fill"></i></label>
                                        <input name="star" id="star2" type="radio" value="2">
                                        <label for="star2"><i class="bi bi-star-fill"></i></label>
                                        <input name="star" id="star1" type="radio" value="1">
                                        <label for="star1"><i class="bi bi-star-fill"></i></label>
                                    </div>
                                </div>
                                <div class="form-group" style="padding-top:20px">
                                    <input class="button" type="submit" value="Submit">
                                </div>
                            </form>
                        HTML;
                    }
                }

                foreach($_reviews as $review) {
                    $_id = $review["ID"];
                    $author = $review["Author"];
                    $content = $review["Content"];
                    $rating = $review["Rating"];

                    $reviews .= <<<HTML
                        <div>
                            {$_id} {$author} {$content} {$rating}
                        </div>
                    HTML;
                }
            }

            render_template("regular", [
                "title" => "View Tour",
                "navigation_bar" => true,
                "footer" => true,
                "resources" => <<<HTML
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
                    <style>
                        .rating-group {
                            display: flex;
                            flex-direction: row;
                            align-items: center;
                            gap: 10px;
                        }

                        .rating-group .label {
                            font-size: 15px;
                        }

                        .rating {
                            display: inline-flex;
                            flex-direction: row-reverse;
                        }

                        .rating input {
                            display: none;
                        }

                        .rating label {
                            font-size: 25px;
                            cursor: pointer;
                            padding-right: 10px;
                        }

                        .rating input:not(:checked) ~ label {
                            color: gray;
                        }

                        .rating > input:checked ~ label, .rating:not(:checked) > label:hover, .rating:not(:checked) > label:hover ~ label {
                            color: orange;
                        }

                        @media only screen and (min-width: 1000px) {
                            .main {
                                flex-direction: row;
                            }

                            .left {
                                width: 70%;
                            }

                            .right {
                                position: sticky;
                                width: 30%;
                                height: min-content;
                                padding-top: 175px;
                                padding-bottom: 200px;
                                top: 0;
                            }
                        }

                        @media only screen and (max-width: 1000px) {
                            .main {
                                flex-direction: column;
                            }

                            .right {
                                padding-top: 50px;
                                padding-bottom: 75px;
                            }
                        }
                    </style>
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
                        <div class="main" style="display:flex;gap:40px">
                            <div class="left">
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
                                                    test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test. test.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </figure>
                                <div class="comments">
                                    <h6 style="margin-top:40px">
                                        <i class="fa fa-comments" style="font-size:25px;margin-right:7px"></i>
                                        Reviews - Average: <span style="color:orange">{$average} star(s)</span>
                                    </h6>
                                    <div class="container" style="margin-top:15px">
                                        <div>{$create}</div>
                                        <div>{$reviews}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="right bg-gray-4" style="text-align:center"> <!-- TODO: not sticking -->
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