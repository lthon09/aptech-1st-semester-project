<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $file = $_FILES['filename'];
    echo '<pre>';
    print_r($file);
    echo '</pre>';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <!-- Status message -->
    <div class="statusMsg"></div>

    <!-- File upload form -->
    <div class="col-lg-12">
        <form id="fupForm" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" name="name" placeholder="Enter name" required />
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" placeholder="Enter email" required />
            </div>
            <div class="form-group">
                <label for="file">File:</label>
                <input type="file" class="form-control" id="file" name="file" required />
            </div>

            <input type="submit" name="submit" class="btn btn-primary submitBtn" value="SUBMIT" />
        </form>
    </div>
</body>

</html>
<script type="text/javascript">
    // $(document).ready(function(e) {
    // Submit form data via Ajax
    $("#fupForm").on('submit', function(e) {
        e.preventDefault();

        alert(123)
        //     $.ajax({
        //         type: 'POST',
        //         url: 'submit.php',
        //         data: new FormData(this),
        //         dataType: 'json',
        //         contentType: false,
        //         cache: false,
        //         processData: false,
        //         beforeSend: function() {
        //             $('.submitBtn').attr("disabled", "disabled");
        //             $('#fupForm').css("opacity", ".5");
        //         },
        //         success: function(response) {
        //             $('.statusMsg').html('');
        //             if (response.status == 1) {
        //                 $('#fupForm')[0].reset();
        //                 $('.statusMsg').html('<p class="alert alert-success">' + response.message + '</p>');
        //             } else {
        //                 $('.statusMsg').html('<p class="alert alert-danger">' + response.message + '</p>');
        //             }
        //             $('#fupForm').css("opacity", "");
        //             $(".submitBtn").removeAttr("disabled");
        //         }
        //     });
        // });
    });
</script>