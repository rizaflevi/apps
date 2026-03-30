<?php
// bm_imphr.php
// import data petugas dari csv file

	include "sysfunction.php";
	$cFILTER_CODE = 'YAZA';

		$filename='F:\Doc\Sari\JANUARI.csv';
			$cQ_DEL = "delete from bm_hari";
			$qQ_DEL = SYS_QUERY($cQ_DEL);
			$file = fopen($filename, "r");
			$count = 0;
			while (($emapData = fgetcsv($file, 50000000, ",")) !== FALSE)
			{
//				print_r($emapData[3]);
//				exit();
				$count++;

				if($count>1){
					$cQUERY="select * from bm_tb_catter1 where NAMA_CATTER='$emapData[2]' and APP_CODE='$cFILTER_CODE' and DELETOR=''";
					$qQUERY=SYS_QUERY($cQUERY);
					if(SYS_ROWS($qQUERY)==0) {
						die ('P : '.$emapData[3]);
					}

					$fPTGS = SYS_FETCH($qQUERY);
					$cPTGS = $fPTGS['KODE_CATTER'];
					$sql = "INSERT into bm_hari set HARI ='$emapData[0]', IDPEL ='$emapData[1]', NO_METER ='$emapData[2]', PETUGAS='$cPTGS', APP_CODE='$cFILTER_CODE'";
					mysql_query($sql);

				}
			}
			fclose($file);
			echo 'CSV File has been successfully Imported';
			header('Location: bm_pelanggan.php');

?>

