<?php
require_once('../connect.php');
session_start();
$username = $_POST["Username"];
$password = $_POST["Password"];
$sql = "Select COUNT(Username) as Username from members where Username = '" . $username . "'";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
if ($row["Username"] == 0) {
    echo "0";
} else {
    $sql = "Select COUNT(Password) as Password from members where Administrator = 1 AND Username = '" . $username . "' AND Password = '" . $password . "'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    if ($row["Password"] == 0) {
        echo "1";
    } else {
        $sql = "Select Username ,Password as Password from members where Administrator = 1 AND Username = '" . $username . "' AND Password = '" . $password . "'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($result);
        $_SESSION["Username"] = $row["Username"];
        $_SESSION["Password"] = $row["Password"];
        echo "2";
    }
}
