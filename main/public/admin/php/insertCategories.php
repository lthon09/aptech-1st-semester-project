<?php
  require_once('../connect.php');
  include '../../../global.php';
  $id = generate_id(IDS["lengths"]["general"], "categories");
    $name = $_POST['name'];
    $sql = "Insert into `categories` (ID, Name) 
        Values ('$id', '$name') 
        ";
    $result = mysqli_query($con, $sql);
    if($result){
      echo 'ok';
    }else{
      echo 'error';
    }
?>