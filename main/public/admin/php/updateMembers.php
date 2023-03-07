<?php
require_once('../connect.php');
include '../../../global.php';

$id = $_POST["id"];
$username = $_POST['username'];
$password = $_POST['password'];
$checkadmin = $_POST['checkadmin'];
$email = $_POST['email'];
$sql = "update `members` set Username = '$username', Password = '$password' , Email = '$email' , Administrator = '$checkadmin'
where Username = '" . $id . "'";
$result = mysqli_query($con, $sql);
if ($result) {
  echo 'ok';
} else {
  echo 'error';
}
