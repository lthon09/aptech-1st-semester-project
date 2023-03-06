<?php
  require_once('../connect.php');
    $id = $_POST['id'];
    $name = $_POST['name'];
    $sql = "update `categories`set Name = '".$name."' where ID = '".$id."'";
    $result = mysqli_query($con, $sql);
    if($result){
      echo 'ok';
    }else{
      echo 'error';
    }
?>