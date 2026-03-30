<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

	$CONFIG_INI = parse_ini_file("Rainbow.ini");

	$server 	= $CONFIG_INI['DBSERVER'];
	$database 	= $CONFIG_INI['DBPAYROLL'];
	$username 	= $CONFIG_INI['DBUSER'];
	$password 	= $CONFIG_INI['DBPASS'];
	$DB1=mysql_connect($server,$username,$password) or die("Can not open DB2");
	mysql_select_db('sys_data', $DB1) or die("Can not open DB1");
//	$DB2=mysql_connect($server,$username,$password) or die("Can not open DB2");
//	mysql_select_db($database, $DB2) or die("Can not open DB2");

	$cAPI=$_GET['_api'];
	
function loadDB($user_code=NULL) {
	if($user_code=NULL) {
		return 'false';
	} else {
		$q_user = "select * from tb_user where USER_CODE='".$user_code;
		if(mysql_num_rows($q_user) == 0) {
			return false;
		} else {
			return true; // connetion to db 2
		}
	}
}
		
switch($cAPI){
case 'login' :
	if(!isset($_GET['Nomor_Hp']) {
		//return false
	} else {
		$sql="select * from tb_user where 
	}
	break;

}
?>
