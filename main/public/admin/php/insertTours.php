<?php
include '../connect.php';
include '../../../global.php';

$id = generate_id(IDS["lengths"]["general"], "tours");

$name = $_POST['name'];
$sale = $_POST['sale'];
$shortdescription = $_POST['shortdescription'];
$longdescription = $_POST['longdescription'];
$price = $_POST['price'];
$country = $_POST['country'];
$category = $_POST['category'];
$detailedInformation = $_POST['detailedInformation'];
$avatar = $_POST['fileToUpload'];
$checkhotTour = $_POST['checkboxTour'];
// $avatar = $_FILES['fileToUpload']['tmp_name'];
// $avatar = addslashes(file_get_contents($image));

$sql = "Insert into `tours` (ID, Name,ShortDescription,LongDescription,Price,Sale,Country,Category , DetailedInformations , avatar , Hot) 
  Values ('$id','$name','$shortdescription' , '$longdescription', '$price' , '$sale' , '$country' , '$category' ,  '$detailedInformation' , '$avatar' , '$checkhotTour') 
";
$result = mysqli_query($con, $sql);
if ($result) {
  echo "ok";
} else {
  echo "lỗi";
}

//Create  File  upload file document
// if (!file_exists("../../static/assets/tours/$id/")) {
//   mkdir("../../static/assets/tours/$id/", 0700);
//   mkdir("../../static/assets/tours/$id/document/", 0700);
//   mkdir("../../static/assets/tours/$id/avatar/", 0700);
// }

// $uploadDir =  "../../static/assets/tours/$id/document/'";
// // Allowed file types 
// $allowTypes = array('pdf', 'doc', 'docx', 'xlsx', 'xls', 'pptx');


// $uploadedFile = '';
// if (!empty($_FILES["fileToUploadDoccument"]["name"])) {
//   // File path config 
//   $fileName = basename($_FILES["fileToUploadDoccument"]["name"]);
//   $targetFilePath = $uploadDir . $fileName;
//   $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

//   // Allow certain file formats to upload 
//   if (in_array($fileType, $allowTypes)) {
//     // Upload file to the server 
//     if (move_uploaded_file($_FILES["fileToUploadDoccument"]["tmp_name"], $targetFilePath)) {
//       $uploadedFile = $fileName;
//     } else {
//       echo 'Sorry, there was an error uploading your file.';
//     }
//   } else {
//     echo 'Sorry, only ' . implode('/', $allowTypes) . ' files are allowed to upload.';
//   }
// }

// $uploadDir =  "../../static/assets/tours/$id/avatar/'";
// // Allowed file types 
// $allowTypes = array('jpg', 'png', 'jpeg', 'JPG', 'PNG', 'JPEG');

// $uploadedFile = '';
// if (!empty($_FILES["fileToUpload"]["name"])) {
//   // File path config 
//   $fileName = basename($_FILES["fileToUpload"]["name"]);
//   $targetFilePath = $uploadDir . $fileName;
//   $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

//   // Allow certain file formats to upload 
//   if (in_array($fileType, $allowTypes)) {
//     // Upload file to the server 
//     if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFilePath)) {
//       $uploadedFile = $fileName;
//       echo 'Success';
//     } else {
//       echo 'Sorry, there was an error uploading your file.';
//     }
//   } else {
//     echo 'Sorry, only ' . implode('/', $allowTypes) . ' files are allowed to upload.';
//   }
// }
