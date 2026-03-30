<?php 
	include "sysfunction.php";

	$CRUD=$_GET['action'];
//	echo $_POST['KODE_PROV'];
//	exit();


if($CRUD=='create' ){
	if($_POST['KODE_PROV']=='') {
		echo "<tr>
		<td colspan='2'>**Kode provinsi tidak boleh kosong**</td>
		</tr>";
	}
	
	$cQUERY="select * from provinsi where id_prov='$_POST[KODE_PROV]'";
	$cCEK_KODE=mysql_query($cQUERY);
	if(mysql_num_rows($cCEK_KODE)==0){
		mysql_query("insert into provinsi set id_prov='$_POST[KODE_PROV]', nama='$_POST[NAMA_PROV]'");
		header('location:tb_provinsi.php');
	} else {
		echo "<tr>
		<td colspan='2'>**Kode provinsi sudah ada**</td>
		</tr>";
	}
}

elseif($CRUD=='update' ){
	mysql_query("update provinsi set nama='$_POST[NAMA_PROV]' where id_prov='$_POST[KODE_PROV]'");
	header('location:tb_provinsi.php');
}

elseif($CRUD=='delete' ){
	mysql_query("delete from provinsi where id_prov='$_GET[KODE_PROV]'");
	header('location:tb_provinsi.php');
}


?>