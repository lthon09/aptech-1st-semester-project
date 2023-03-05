<?php
    require_once "../global.php";

    $queries = get_queries();

    $categories = "";
    $countries = "";
    $tours = "";

    $_categories = large_query("Categories");
    $_countries = large_query("Countries");

    foreach ($_categories as $category) {
        $id = htmlentities($category["ID"]);
        $name = htmlentities($category["Name"]);

        $selected = (isset($queries["categories"])) ? ((in_array($id, $queries["categories"])) ? "selected" : "") : "";

        $categories .= <<<HTML
            <option value="{$id}" {$selected}>{$name}</option>
        HTML;
    }

    foreach ($_countries as $country) {
        $id = htmlentities($country["ID"]);
        $name = htmlentities($country["Name"]);

        $selected = (isset($queries["countries"])) ? ((in_array($id, $queries["countries"])) ? "selected" : "") : "";

        $countries .= <<<HTML
            <option value="{$id}" {$selected}>{$name}</option>
        HTML;
    }

    foreach (large_query("Tours") as $tour) {
        $id = htmlentities($tour["ID"]);
        $name = htmlentities($tour["Name"]);
        $country = htmlentities($_countries[$tour["Country"]]["Name"]);
        $category = htmlentities($_categories[$tour["Category"]]["Name"]);
        $description = htmlentities($tour["ShortDescription"]);
        $original_price = $tour["Price"];
        $sale = $tour["Sale"];
        $_original_price = "$" . format_price($original_price, (int)$original_price, $original_price);

        $style = "";
        $_sale = "";

        if ($sale !== 0) {
            $discounted_price = "$" . calculate_discounted_price($original_price, $sale);

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

        $tours .= <<<HTML
            <div class="col-sm-6 col-lg-4">
                <figure>
                    <div class="unit flex-column flex-md-column align-items-md-stretch">
                        <div class="unit-left">
                            <a href="/tours.php?id={$id}">
                                <img class="product-figure"
                                    src="/static/assets/tours/{$id}/avatar.jpg" alt="" style="width:100%;height:200px" />
                            </a>
                        </div>
                        <div class="unit-body">
                            <div class="product-body">
                                <h5 class="product-title">
                                    <a href="/tours.php?id={$id}">{$name}, {$country}</a>
                                </h5>
                                <div class="product-text" style="display:flex;flex-direction:column;gap:20px;margin-top:10px">
                                    <span style="color:gray;font-size:16px">{$category}</span>
                                    <span>{$description}</span>
                                </div>
                                <div class="product-price-wrap" style="margin-top:40px">
                                    <span class="product-price" style="display:flex;flex-direction:row;align-items:center;gap:40px;{$style}">
                                        {$_sale}
                                        {$_price}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </figure>
        </div>
        HTML;
    }

    render_template("regular", [
        "title" => "Tours",
        "navigation_bar" => true,
        "footer" => true,
        "resources" => <<<HTML
            <style>
                .chosen-select {
                    width: 200px;
                }

                .chosen-container .chosen-choices {
                    background-image: none !important;
                }

                .chosen-container.chosen-container-active .chosen-choices {
                    border-color: #01b3a7 !important;
                }

                .chosen-container .chosen-choices .search-field .chosen-search-input {
                    font-family: "Poppins" !important;
                }

                .chosen-container .chosen-choices .search-choice .search-choice-close {
                    transition: none;
                }

                .chosen-container .chosen-drop .chosen-results {
                    text-align: left;
                }

                .chosen-container .chosen-drop .chosen-results .active-result.highlighted {
                    background-color: #01b3a7 !important;
                    background-image: none !important;
                }

                .update {
                    color: white;
                    background-color: #01b3a7;
                    border: none;
                    cursor: pointer;
                    padding-top: 8px;
                    padding-left: 15px;
                    padding-right: 15px;
                    padding-bottom: 8px;
                }

                .update:hover {
                    background-color: #008f85;
                }
            </style>
            <link rel="stylesheet" href="https://harvesthq.github.io/chosen/chosen.css">
            <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
            <script src="//harvesthq.github.io/chosen/chosen.jquery.js"></script>
        HTML,
        "content" => <<<HTML
            <div class="container" style="margin-top:30px;margin-bottom:50px">
                <form method="get" style="display:flex;flex-direction:row;flex-wrap:wrap;align-items:center;column-gap:25px;row-gap:10px">
                    <select class="chosen-select" name="categories[]" data-placeholder="Categories" multiple>
                        {$categories}
                    </select>
                    <select class="chosen-select" name="countries[]" data-placeholder="Countries" multiple>
                        {$countries}
                    </select>
                    <input class="update" type="submit" value="Update">
                </form>
                <div class="row" style="display:flex;flex-direction:row;gap:75px">
                    {$tours}
                </div>
            </div>
            <script>$(".chosen-select").chosen()</script>
        HTML,
    ]);
?>