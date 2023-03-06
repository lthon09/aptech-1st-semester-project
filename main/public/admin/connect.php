<?php 
    $con = new mysqli('localhost','root','','PleasantTours');

    if(!$con){
        die(mysqli_error($con));
    }
?>