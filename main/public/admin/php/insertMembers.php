<?php
require_once('../connect.php');
include '../../../global.php';

$id = generate_id(IDS["lengths"]["general"], "members");
$username = $_POST['username'];
$password = $_POST['password'];
$checkadmin = $_POST['checkadmin'];
$email = $_POST['email'];
$sql = "Insert into `members` (ID, Username , Password , Email , Administrator) 
        Values ('$id', '$username' ,  '$password' , '$email' , '$checkadmin') 
        ";
$result = mysqli_query($con, $sql);
if ($result) {
  echo 'ok';
} else {
  echo 'error';
}
