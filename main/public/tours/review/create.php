<?php
    require_once "../../../global.php";

    logged_in_only();

    if (isset($_POST["submit"])) {
        $tour = $_POST["tour"];

        if (!validate_id($tour)) {
            redirect("/tours/view.php?id={$tour}");
        } else {
            $content = $_POST["content"];

            if (mb_strlen($content) > MAXIMUM_REVIEW_CONTENT_LENGTH) {
                redirect("/tours/view.php?id={$tour}");
            } else {
                $rating = ($_POST["rating"]) ? $_POST["rating"] : 0;

                if (($rating < 0) || ($rating > 5)) {
                    redirect("/tours/view.php?id={$tour}");
                } else {
                    $author = $member["Username"];
                    $connection = connect();

                    $statement = $connection -> prepare("
                        SELECT * FROM Reviews WHERE Tour = :tour AND Author = :author LIMIT 1;
                    ");

                    $statement -> execute([
                        "tour" => $tour,
                        "author" => $author,
                    ]);

                    if ($statement -> rowCount() !== 0) {
                        redirect("/tours/view.php?id={$tour}");
                    } else {
                        $id = generate_id(IDS["lengths"]["general"], "Reviews");

                        if ($id === false) {
                            redirect("/tours/view.php?id={$tour}");
                        } else {
                            $connection -> prepare("
                                INSERT INTO Reviews (ID, Tour, Author, Content, Rating)
                                VALUES (:id, :tour, :author, :content, :rating)
                                LIMIT 1;
                            ") -> execute([
                                "id" => $id,
                                "tour" => $tour,
                                "author" => $author,
                                "content" => $content,
                                "rating" => $rating,
                            ]);

                            redirect("/tours/view.php?id={$tour}");
                        }
                    }
                }
            }
        }
    }
?>