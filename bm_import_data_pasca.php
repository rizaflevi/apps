<?php
// bm_import_data_pasca.php
// import data petugas dari csv file

	include "sysfunction.php";
	$cFILTER_CODE = 'YAZA';
	$nTHN=2017;
	$nBLN=5;

		$filename='F:\Doc\Sari\PascaBayar\pasca.csv';
			$file = fopen($filename, "r");
			$count = 0;
			$n_PLG	= 0;
			$cQ_TB_PLG = "INSERT into bm_plg_pasca ( `IDPEL`, `LEMBAR`, `TAGIHAN`, `TAHUN`, `BULAN`, `RPBK`, `APP_CODE` ) values ";
			while (($emapData = fgetcsv($file, 5000000, ",")) !== FALSE)
			{
				$count++;

				if($count>1 and $emapData[0]!=''){

					$cID_PEL = $emapData[1];
					$cNO_RBM = $emapData[2];
					$cKODE_AREA 	= substr($cNO_RBM, 0, 3);
					$cKODE_PETUGAS	= substr($cNO_RBM, 3, 3);
					$cKODE_RBM		= substr($cNO_RBM, 7, 1);
					$cNMR_URUT		= substr($cNO_RBM, 8, 3);
					$cNAMA = $emapData[3];
					$cNAMA = str_replace( "'" , "", $cNAMA );
					$cALM = $emapData[4];
					$cDAYA = $emapData[5];
					$nDAYA = str_replace( "," , "", $cDAYA );
					$nLBR = $emapData[6];
					$cTGHN = $emapData[7];
					$nTGHN = str_replace( "," , "", $cTGHN );
					$cRPBK = $emapData[8];
					$nRPBK = str_replace( "," , "", $cRPBK );
					
					$qQUERY=SYS_QUERY("select * from bm_pel_pasca where IDPEL='$cID_PEL' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
					if(SYS_ROWS($qQUERY)==0) {
						$cQUERY = "INSERT into bm_pel_pasca ( `IDPEL`, `NAMA_PEL`, `ALAMAT`, `KODE_RBM` , `DAYA`, `APP_CODE` ) values ";
						$cQUERY .= "('". $cID_PEL . "', '" . $cNAMA . "', '" . $cALM . "', '" . $cNO_RBM . "', " . $nDAYA . ", '" . $cFILTER_CODE . "') ";
					} else {
						$cQUERY="update bm_pel_pasca set NAMA_PEL='$cNAMA', ALAMAT='$cALM', KODE_RBM='$cNO_RBM', DAYA='$nDAYA' where IDPEL='$cID_PEL' and APP_CODE='$cFILTER_CODE'";
					}
					SYS_QUERY($cQUERY);

					$cQUERY="select IDPEL from bm_plg_pasca where IDPEL='$cID_PEL' and TAHUN='$nTHN' and BULAN='$nBLN' and APP_CODE='$cFILTER_CODE' and DELETOR=''";
					$qQUERY=SYS_QUERY($cQUERY);
					if(SYS_ROWS($qQUERY)==0) {
						$cQUERY = "INSERT into bm_plg_pasca ( `IDPEL`, `LEMBAR`, `TAGIHAN`, `RPBK` , `TAHUN`, `BULAN`, `APP_CODE` ) values ";
						$cQUERY .= "('". $cID_PEL . "', " . $nLBR . ", " . $nTGHN . ", " . $nRPBK . ", " . $nTHN . ", " . $nBLN . ", '" . $cFILTER_CODE . "') ";
					} else {
						$cQUERY="update bm_plg_pasca set IDPEL='$cID_PEL', LEMBAR=".$nLBR. ", TAGIHAN=".$nTGHN.", RPBK='$nRPBK', TAHUN='$nTHN', BULAN='$nBLN' where IDPEL='$cID_PEL' and APP_CODE='$cFILTER_CODE'";
					}
					$q_UPDP=SYS_QUERY($cQUERY);
				}
			}

			fclose($file);
			echo 'CSV File has been successfully Imported';
//			header('Location: bm_pelanggan.php');

?>

