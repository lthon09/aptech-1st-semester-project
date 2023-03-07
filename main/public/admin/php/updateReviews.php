<?php
include '../connect.php';
include '../../../global.php';

$id = $_POST["id"];
$tour = $_POST['Tour'];
$author = $_POST['Author'];
$content = $_POST['Content'];
$rating = $_POST['rate'];
$sql = "update `reviews` set Tour = '$tour',
                           Author = '$author',
                           Content = '$content',
                           Rating = '$rating'
                           where ID = '".$id."'";
$result = mysqli_query($con, $sql);
if ($result) {
  echo "ok";
} else {
  echo "lỗi";
}