<?php
  require_once('../connect.php');
    $id = $_POST['id'];
    $sql = "delete from  `members` where Username = '".$id."'";
    $result = mysqli_query($con, $sql);
    if($result){
      echo 'ok';
    }else{
      echo 'error';
    }
?>