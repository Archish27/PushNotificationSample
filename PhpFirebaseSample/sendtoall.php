<?php
date_default_timezone_set("Asia/Kolkata");

include_once 'DbOperation.php';
require_once 'Firebase.php';
require_once 'PushData.php';
$db = new DbOperation();


if (isset($_POST["regId"]) && isset($_POST["m_message"])) {
    $regId = $_POST["regId"];
    $title = $_POST['m_title'];
    $message = $_POST["m_message"];


    $date = date('d/m/Y, g:i A');
    $success = $db->insertInDB($message, $title, $image, $f_images, $f_videos, $f_audios, $date);
 
	$res2 = $db->getLastSMS();    
	$row2 = mysqli_fetch_array($res2);
	
    
    $status = 1;
    $push = new PushData(
        $row2['id'],
        $row2['title'],
        $row2['message'],
        $row2['date'],
        $row2['status'],
        $row2['imagelist'],
        $row2['audio'],
        $row2['video']

    );    
    $mPushNotification = $push->getPushData();
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




}
?>		
