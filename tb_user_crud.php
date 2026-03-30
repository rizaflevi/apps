<?php
//  tb_user_crud.php

	include "sysfunction.php";
	if (!isset($_SESSION['data_FILTER_CODE'])) 	session_start();

	$cFILTER_CODE = $_SESSION['data_FILTER_CODE'];
	$CRUD=$_GET['action'];
	$NOW = date("Y-m-d H:i:s");

if($CRUD=='create' ){
}
elseif($CRUD=='update' ){
}

elseif($CRUD=='delete' ){
}

elseif($CRUD=='activate' ){
	$KODE_CRUD=$_GET['id'];
	header('location:user_activate.php');
}

function upload(){
	/*** check if a file was uploaded ***/
	if(is_uploaded_file($_FILES['upload_image']['tmp_name']) && getimagesize($_FILES['upload_image']['tmp_name']) != false) {
		 /*** get the image info. ***/
		 $size = getimagesize($_FILES['upload_image']['tmp_name']);
		 /*** assign our variables ***/
		 $type = $size['mime'];
		 $imgfp = fopen($_FILES['upload_image']['tmp_name'], 'rb');
		 $size = $size[3];
		 $name = $_FILES['upload_image']['name'];
		 $maxsize = 99999999;

		 /*** check the file is less than the maximum file size ***/
		 if($_FILES['upload_image']['size'] < $maxsize )	 {
			 /*** our sql query ***/
			 $stmt = "INSERT INTO ".$database1.".user_img (IMAGE_TYPE ,USER_IMAGE, UP_DATE, IMG_DESC) VALUES ($type ,$imgfp, $size, $name)";


			 /*** execute the query ***/
			 $XX=SYS_QUERY($stmt);
		 }	else	 {
			 // if the file is not less than the maximum allowed, print an error
			 throw new Exception("Unsize Image Format!");
		 }
	 }	else {
		 // if the file is not less than the maximum allowed, print an error
		 throw new Exception("Unsupported Image Format!");
	 }
}

?>
