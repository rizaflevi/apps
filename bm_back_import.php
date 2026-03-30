<?php
// bm_back_import.php
// import data pelanggan dari csv file ( background process )

	include "sysfunction.php";
	$cFILTER_CODE = 'YAZA';

	$filename='F:\Doc\Sari\JANUARI.csv';
	$cRUN_TRIGGER = S_PARA('IMPORT_RUN_TRIGGER',' ');
	$cRUN_STATUS = S_PARA('IMPORT_RUN_STATUS',' ');

		if($cRUN_TRIGGER = '1' and $cRUN_STATUS != '1')
		{
			$cRUN_STATUS = S_REPL('IMPORT_RUN_STATUS','1');
			$qQ_DEL = SYS_QUERY("delete from bm_tb_pel");
			$file = fopen($filename, "r");
			$count = 0;
			while (($emapData = fgetcsv($file, 50000000, ",")) !== FALSE)
			{
				//print_r($emapData);
				//exit();
				$count++;

				if($count>1){
					$sql = "INSERT into bm_tb_pel set IDPEL='$emapData[0]', NAMA_PEL='$emapData[1]', ALAMAT='$emapData[2]', UNITUP='$emapData[3]', KODE_TARIF='$emapData[4]', DAYA='$emapData[5]', NOMOR_KWH='$emapData[6]', MERK_KWH='$emapData[7]', APP_CODE='$cFILTER_CODE'";
					mysql_query($sql);

					$cQUERY="select * from bm_tb_plg where IDPEL='$emapData[0]' and APP_CODE='$cFILTER_CODE' and DELETOR=''";
					$qQUERY=SYS_QUERY($cQUERY);
					if(SYS_ROWS($qQUERY)==0) {
						$NOW = date("Y-m-d H:i:s");
						$cINSERT = "insert into bm_tb_plg set IDPEL='$emapData[0]', APP_CODE='$cFILTER_CODE', ENTRY='$_SESSION[gUSERCODE]', DATE_ENTRY='$NOW'";
						$qINSERT=SYS_QUERY($cINSERT);
					}

				}
			}
			fclose($file);
			$cRUN_STATUS = S_REPL('IMPORT_RUN_STATUS',' ');
		}
?>

