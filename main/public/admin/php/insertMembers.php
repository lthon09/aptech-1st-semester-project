<?php
require_once('../connect.php');
include '../../../global.php';

$username = $_POST['username'];
$password = hash_password($_POST['password']);
// $password = $_POST['password'];
$checkadmin = $_POST['checkadmin'];
$email = $_POST['email'];
$sql = "Insert into `members` (Username , Password , Email , Administrator) 
        Values ('$username' ,  '$password' , '$email' , '$checkadmin') 
        ";
$result = mysqli_query($con, $sql);
if ($result) {
  echo 'ok';
} else {
  echo 'error';
}
