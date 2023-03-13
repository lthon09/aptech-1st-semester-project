<?php
    require_once "../../../global.php";

    $queries = get_queries();

    if (isset($queries["id"])) {
        $id = $queries["id"];

        if (validate_id($id)) {
            $pdf = new FPDF();

            $pdf -> AddPage();

            $pdf -> SetFont("Times", "B", 40);
            $pdf -> SetTextColor(1, 179, 167);
            $pdf -> Cell(0, 10, "Tour", 0, 0, "C");
            $pdf -> Ln(20);

            $pdf -> SetFont("Arial", "", 12);

            $connection = connect();

            $statement = $connection -> prepare("
                SELECT * FROM Tours WHERE ID = :id LIMIT 1;
            ");

            $statement -> execute(["id" => $id]);

            $tour = $statement -> fetch();

            $name = $tour["Name"];
            $price = $tour["Price"];
            $sale = $tour["Sale"];

            $statement = $connection -> prepare("
                SELECT * FROM Countries WHERE ID = :id LIMIT 1;
            ");

            $statement -> execute(["id" => $tour["Country"]]);

            $pdf -> Image($tour["Avatar"], null, null, 90, 60);
            $pdf -> Ln(7);

            $pdf -> Write(5, "Tour: ");

            $pdf -> SetTextColor(0, 0, 0);
            $pdf -> Write(5, htmlentities($name . ", " . ($statement -> fetch())["Name"]), get_server() . "/tours/view.php?id={$id}");

            $pdf -> Ln(7);

            $pdf -> SetTextColor(1, 179, 167);
            $pdf -> Write(5, "Price: ");

            if ($tour["Sale"] === 0) {
                $pdf -> SetTextColor(252, 232, 131);
                $pdf -> Write(5, "$" . calculate_price($price, $sale));
            } else {
                $pdf -> SetFontSize(9);
                $pdf -> SetTextColor(169, 169, 169);
                $pdf -> Write(5, "$" . format_float($price, (int)$price, bcadd($price, 0, 2)));

                $pdf -> SetFontSize(12);
                $pdf -> SetTextColor(255, 0, 0);
                $pdf -> Write(5, " $" . calculate_price($price, $sale));
            }

            $pdf -> Ln(18);

            $pdf -> SetFontSize(14);
            $pdf -> SetTextColor(0, 0, 0);
            $pdf -> Write(7, $tour["DetailedInformations"]);

            $pdf -> Output("D", "[Pleasant Tours] {$name}.pdf");
        }
    }
?>