<?php
  require_once('../connect.php');
    $id = $_POST['id'];
    $sql = "delete from  `countries` where ID = '".$id."'";
    $result = mysqli_query($con, $sql);
    if($result){
      echo 'ok';
    }else{
      echo 'error';
    }
?>