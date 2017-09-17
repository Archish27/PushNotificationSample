<?php
date_default_timezone_set("Asia/Kolkata");

include_once 'DbOperation.php';
require_once 'Firebase.php';
require_once 'PushSatsang.php';
$db = new DbOperation();

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$target_dir = "../../upload/";
//$target_file = $target_dir . basename($_FILES["pic"]["name"]);
//$uploadOk = 1;
//$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
//// Check if image file is a actual image or fake image
//if (isset($_POST["submit"])) {
//    $check = getimagesize($_FILES["pic"]["tmp_name"]);
//    if ($check !== false) {
//        //echo "File is an image - " . $check["mime"] . ".";
//        $uploadOk = 1;
//    } else {
//        //echo "File is not an image.";
//        $uploadOk = 0;
//    }
//}
//// Check if file already exists
//if (file_exists($target_file)) {
//    //echo "Sorry, file already exists.";
//    $uploadOk = 0;
//}
//// Check file size
//if ($_FILES["pic"]["size"] > 500000) {
//    //echo "Sorry, your file is too large.";
//    $uploadOk = 0;
//}
//// Allow certain file formats
//if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
//    && $imageFileType != "gif"
//) {
//    //echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
//    $uploadOk = 0;
//}
//// Check if $uploadOk is set to 0 by an error
//if ($uploadOk == 0) {
//    //echo "Sorry, your file was not uploaded.";
//// if everything is ok, try to upload file
//} else {
//    if (move_uploaded_file($_FILES["pic"]["tmp_name"], $target_file)) {
//        //echo "The file ". basename( $_FILES["pic"]["name"]). " has been uploaded.";
//    } else {
//        //echo "Sorry, there was an error uploading your file.";
//    }
//}

$multipleimages = null;


$fimages = "";
$fvideos = "";
$faudios = "";


if (isset($_POST['fsubmit'])) {
    $j = 0; //Variable for indexing uploaded image 

    $path = "../../upload/"; //Declaring Path for uploaded images
    for ($i = 0; $i < count($_FILES['file']['name']); $i++) {//loop to get individual element from the array

        usleep(500000);
        $validextensions = array("jpeg", "jpg", "png", "mp4", "avi", "flv", "mkv", "wav", "mp3", "3gp", "ogg", "mpeg", "aac", "gif");  //Extensions which are allowed
        $ext = explode('.', basename($_FILES['file']['name'][$i]));//explode file name from dot(.) 
        $file_extension = end($ext); //store extensions in the variable
        // echo $file_extension."<br>  ";
        $target_path = $path . $_FILES['file']['name'][$i];//set the target path with a new name of image
        $j = $j + 1;//increment the number of uploaded images according to the files in array       

        if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $target_path)) {//if file moved to uploads folder
            //echo $j. ').<span id="noerror">Image uploaded successfully!.</span><br/><br/>';
        } else {//if file was not moved.
     		echo "<script type='text/javascript'>alert('Upload failed! Try Again');window.location = '../../satsang_messages.php';</script>";

        }

        if ($file_extension == "jpeg" || $file_extension == "jpg" || $file_extension == "png" || $file_extension == "gif") {
            $a = basename($_FILES["file"]["name"][$i]);
            $time = microtime(true) * 10000;
            $b = "IMG-SM" . $time . "." . $file_extension;
            rename($target_dir . $a, $target_dir . $b);
            $fimages = $b . "," . $fimages;
        } else if ($file_extension == "mp4" || $file_extension == "avi" || $file_extension == "mpeg" || $file_extension == "flv" || $file_extension == "3gp" || $file_extension == "mkv") {
            $time1 = microtime(true) * 10000;
            $a = basename($_FILES["file"]["name"][$i]);
            $b = "VID-SM" . $time1 . "." . $file_extension;
            rename($target_dir . $a, $target_dir . $b);
            $fvideos = $b . "," . $fvideos;
        } else if ($file_extension == "wav" || $file_extension == "mp3" || $file_extension == "ogg" || $file_extension == "aac") {
            $time2 = microtime(true) * 10000;
            $a = basename($_FILES["file"]["name"][$i]);
            $b = "AUD-SM" . $time2 . "." . $file_extension;
            rename($target_dir . $a, $target_dir . $b);
            $faudios = $b . "," . $faudios;

        }
    }
}


$f_images = rtrim($fimages, ",");
$f_videos = rtrim($fvideos, ",");
$f_audios = rtrim($faudios, ",");


if (isset($_POST["regId"]) && isset($_POST["m_message"])) {
    $regId = $_POST["regId"];
    $title = $_POST['m_title'];
    $message = $_POST["m_message"];

    $image = "null";

    $date = date('d/m/Y, g:i A');
    $success = $db->insertInDB($message, $title, $image, $f_images, $f_videos, $f_audios, $date);
    //echo $message . $title . $image . $f_images . $f_videos . $f_audios . $date;

	$res2 = $db->getLastSMS();    
	$row2 = mysqli_fetch_array($res2);
	
    
    $status = 1;
    $push = new PushSatsang(
        $row2['id'],
        $row2['title'],
        $row2['message'],
        $row2['date'],
        $row2['status'],
        $row2['imagelist'],
        $row2['audio'],
        $row2['video']

    );    
    $mPushNotification = $push->getPushSatsang();
    $devicetoken = $db->getAllTokens();
    $firebase = new Firebase();

    $total = count($devicetoken);
        
    $groups = ceil($total/800);
        $currentval=ceil($total/$groups);
        $firebase = new Firebase(); 
        for ($i=0; $i <$groups; $i++) { 
               
            $val=($i*$currentval)+1;
            $total = ($i+1)*$currentval;
            $resToken = $db->getSpecificToken($val,$total);
            $result1 = $firebase->send($resToken, $mPushNotification);
        
         //   echo $result1;       
        }

//
//    include_once './GCM.php';
//
//    $gcm = new GCM();
//
//
//    $registatoin_ids = array($regId);
//    $message = array("price" => $message, "title" => $title, "date" => $date, "status" => $status);
//    foreach ($registatoin_ids as $reg)
//        $result = $gcm->send_notification($reg, $message);
    echo "<script type='text/javascript'>alert('Satsang Message Successfully Sent.');window.location = '../../satsang_messages.php';</script>";


}
?>		