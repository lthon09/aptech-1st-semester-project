<?php
    require_once "../../../global.php";

    $pdf = new FPDF();

    $pdf -> AddPage();

    $pdf -> SetFont("Times", "B", 40);
    $pdf -> SetTextColor(1, 179, 167);
    $pdf -> Cell(0, 10, "Tours Prices List", 0, 0, "C");
    $pdf -> Ln(25);

    $pdf -> SetFont("Arial", "", 12);

    $connection = connect();

    $tours = large_query("Tours", "", []);

    if (!$tours) {
        $pdf -> SetTextColor(0, 0, 0);
        $pdf -> Cell(0, 0, "No Tour", 0, 0, "C");
    } else {
        foreach ($tours as $tour) {
            $id = $tour["ID"];

            $statement = $connection -> prepare("
                SELECT * FROM Countries WHERE ID = :id LIMIT 1;
            ");

            $statement -> execute(["id" => $tour["Country"]]);

            $pdf -> SetTextColor(51, 194, 184);
            $pdf -> Write(5, htmlentities($tour["Name"]), get_server() . "/tours/view.php?id={$id}");

            $pdf -> SetTextColor(0, 0, 0);
            $pdf -> Write(5, ", ");

            $pdf -> SetTextColor(51, 194, 184);
            $pdf -> Write(5, htmlentities(($statement -> fetch())["Name"]));

            $pdf -> SetTextColor(0, 0, 0);
            $pdf -> Write(5, ": ");

            if ($tour["Sale"] === 0) {
                $pdf -> SetTextColor(252, 232, 131);
            } else {
                $pdf -> SetTextColor(255, 0, 0);
            }

            $pdf -> Write(5, "$" . calculate_price($tour["Price"], $tour["Sale"]));

            $pdf -> Ln(7);
        }
    }

    $pdf -> Output("D", "[Pleasant Tours] Tours Prices List.pdf");
?>