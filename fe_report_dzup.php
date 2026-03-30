<?php
// ob_start();
// error_reporting(E_ALL & ~E_NOTICE);

error_reporting(E_ALL & ~E_NOTICE);
session_start();
$dest_folder = '../www/images/report/';
//   var_dump($_FILES);
// var_dump($_POST);
// session_start();
// echo '<pre>';
// var_dump($_SESSION['cHOST_DB2']);
// if (!isset($_SESSION['cHOST_DB2'])) 	session_start();
// $_SESSION['cHOST_DB2'] = 'riza_db';
//   var_dump($_POST);
//   var_dump($filename);
// echo '</pre>';
// $_SESSION['cHOST_DB2'] = 'riza_db';
include "sysfunction.php";

$cDEVICE = $_POST['F_DEVICE'];
$cPRS_CODE = $_POST['F_PRSON_CODE'];
$cDATE = $_POST['F_REP_TIME'];
$cREPORT = ENCODE($_POST['F_REP_CONTENT']);
$cAPP_CODE = $_POST['F_APP_CODE'];

//cek laporannya dulu apakah masih kosong, kalau kosong tidak akan upload

if(trim($cREPORT) == ""){
    // var_dump($_POST);
    // var_dump($_SESSION['DB2']);
    // echo '<br><br><br><br><br><br><br><br><br>';
    // var_dump($_POST['F_REP_CONTENT']);
    // echo '<br><br><br><br><br><br><br><br><br>';
    // var_dump($_POST);
    // MSG_INFO('Laporan masih kosong');
    // var_dump($cDEVICE);
    // echo "<script>window.location.replace('https://staff.fahlevi.co/rainbow_ext.php?q=$cDEVICE');</script>";
    header('location:rainbow_ext.php?q='.$cDEVICE);
} else {
    RecCreate('PrsReport', ['PRSON_CODE', 'REP_TIME', 'REP_CONTENT', 'ENTRY', 'APP_CODE'], [$cPRS_CODE, $cDATE, $cREPORT, $cPRS_CODE, $cAPP_CODE]);

    if (!empty($_FILES)) {  
        // $tmpFile = $_FILES['images']['tmp_name'];  
        // $filename = '../www/images/report/'.time().'-'. $_FILES['images']['name'];  
        // move_uploaded_file($tmpFile,$filename);  
        // echo 'test';
    
        // $file = fopen("test.html", 'w') or die("can't open file");
        // fclose($file);
    
    
        var_dump($_FILES["file"]["error"]);
        //looping upload setiap file
        // foreach($_FILES['file']['tmp_name'] as $key => $value) {
        //     $tempFile = $_FILES['file']['tmp_name'][$key];
        //     $targetFile =  '../'.time().'-'. $_FILES['file']['name'][$key];
        //     move_uploaded_file($tempFile,$targetFile);
        // }

        /**
        *	Response 
        *	return json response to the dropzone
        *	@var data array
        */
        // $data = [
        //     "file" => $_POST["file"],
        //     "dropzone" => $_POST["dropzone"],
        // ];
        // header('Content-type: application/json');
        // echo json_encode($data);

        // exit();
        
    }  
}

// var_dump($cREPORT);



// echo '<script>alert("'.($_FILES['image']['error']).'")</script>';
// header('location:rainbow_ext.php?q='.$cDEVICE);

// echo "<script> window.history.back();	</script>";


// $cAPP_CODE  = $_SESSION['data_FILTER_CODE'] = $_GET['_app'];

// $cACTION    = (isset($_GET['_a']) ? $cACTION=$_GET['_a'] : '');
// $cDEVICE    = (isset($_GET['_dev']) ? $_GET['_dev'] : '');
// $cPRS_CODE  = (isset($_GET['_prs']) ? $cPRS_CODE=$_GET['_prs'] : '');
// $cAPP_CODE  = $_GET['_app'];


// $cPRS_CODE = $_GET['_prs'];


// $storeFolder = 'villas-images';
// $encpt_data = rand(1000,5000);

// $datetime = new DateTime($aREC_PERSON['REP_TIME']);
// $date = $datetime->format('_Y-m-d H_i');

// //relative path untuk mengecek file_exists di server
// $img_path1 = '../www/images/report/'
// .$cAPP_CODE.'_ACTIVITY_'
// .$aREC_PERSON['PRSON_CODE']


// echo '<pre>';
//   var_dump($_POST);
//   var_dump($_FILES);
//   var_dump($filename);
// echo '</pre>';
// phpinfo();
//prs_report table


?>