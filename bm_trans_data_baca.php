<?php
// bm_trans_data_baca.php
// import data baca mtr dari csv file
// 12360 record(s) CSV File has been successfully Imported 

	include "sysfunction.php";
	$cFILTER_CODE = 'YAZA';

		$filename='F:\Doc\Yaza\Data_baca\Binuang2.csv';
		$cRAYON='22260';
			$cQ_DEL = "delete from bm_dt_temp where UNITUP='$cRAYON'";
			$qQ_DEL = SYS_QUERY($cQ_DEL);
			$file = fopen($filename, "r");
			$count = 0;
			$n_PLG	= 0;
			$cQUERYBACA = "INSERT into bm_dt_temp ( `UNITUP`, `TGL_BACA`, `NMR_METER`, `SISA_TOKEN`, `LATTI`, `LONGI` , `PETUGAS`, `KONDISI_RMH`, `SEGEL_TLG`, `SEGEL_TER`, `LAMPU_INDI`, `KODE_RBM`, `IDPEL`, `NAMA_PEL`, `ALM_PEL`, `KODE_TRF`, `DAYA`, `NOTE`, `LOC_FOTO`, `APP_CODE`, `ENTRY` ) values ";
			while (($emapData = fgetcsv($file, 5000000, ",")) !== FALSE)
			{
				$count++;

				if($count>1 and $emapData[0]!='' and $emapData[16]!='' and $emapData[16]!='#N/A'){

					$dWAKTU = $emapData[0];
					$nSPASI = strpos($dWAKTU, ' ');
					$cWAKTU = substr($dWAKTU, 0, $nSPASI);
					$cTGL_BACA= explode('/', $cWAKTU);
					$cTHN = substr($cTGL_BACA[2],0,4);
					$cBLN = $cTGL_BACA[0];
					if(strlen($cBLN)<2) {
						$cBLN = '0'.$cBLN;
					}
					$cTGL = $cTGL_BACA[1];
					if(strlen($cTGL)<2) {
						$cTGL = '0'.$cTGL;
					}
					$cTIME = substr($cTGL_BACA[2],5,4);
					$cDB_TGL = $cTHN.'-'.$cBLN.'-'.$cTGL.' '.substr($dWAKTU,-8);
//					var_dump($cDB_TGL); exit;
					
					$cNMTR = $emapData[1];
					$nNTKN = $emapData[2];
					$cNTKN = str_replace( ".." , ".", $nNTKN );
					if($cNTKN=='') {
						$cNTKN = '0';
					}
					$nTOKEN = floatval($cNTKN);

					$cLAST_VS = trim(strtoupper($emapData[6]));
					$cPETUGAS = '';
					$qQUERY=SYS_QUERY("select * from bm_tb_catter1 where upper(NAMA_CATTER) like '%$cLAST_VS%' and APP_CODE='$cFILTER_CODE' and DELETOR=''");
					if(SYS_ROWS($qQUERY)>0) {
						$PTG = SYS_FETCH($qQUERY);
						$cPETUGAS = $PTG['KODE_CATTER'];
					} else {
						echo $count.' : '.$cLAST_VS.'<br>';
					}
					
					$nKONDISI = 0;
					if($emapData[7]=='KOSONG') {
						$nKONDISI = 1;
					}

					$nSEGEL_TLG = 0;
					if($emapData[8]=='TIDAK LENGKAP') {
						$nSEGEL_TLG = 1;
					}

					$nSEGEL_TER = 0;
					if($emapData[9]=='TIDAK LENGKAP') {
						$nSEGEL_TER = 1;
					}

					$nLAMPU_INDI = 0;
					if($emapData[10]=='TIDAK MENYALA') {
						$nLAMPU_INDI = 1;
					}
					$mKD_RUT = $emapData[15];
					$cKD_RUT = substr($mKD_RUT,0,1);
					if(strlen($mKD_RUT)==7) {
						$cKD_RUT = substr($mKD_RUT,-1);
					}
					$cID_PEL = $emapData[16];
					$mNM_PEL = $emapData[17];
					$cNM_PEL = str_replace( "'" , "", $mNM_PEL );
					$cAL_PEL = str_replace( "'" , "", $emapData[18]);
					$cKD_TRF = $emapData[19];
					$nDAYA = $emapData[20];
					$cLOC_FOTO = str_replace( "'" , "", $emapData[3]);

					$cQUERYBACA .= "('". $cRAYON . "', '" . $cDB_TGL . "', '" . $cNMTR . "', " . $nTOKEN . ", " . $emapData[4]/100000000 . ", " . $emapData[5]/100000000 . ", '" . $cPETUGAS . "', " . $nKONDISI . ", " . $nSEGEL_TLG . ", " . $nSEGEL_TER . ", " . $nLAMPU_INDI . ", '" . $cKD_RUT . "', '" . $cID_PEL . "', '" . $cNM_PEL . "', '" . $cAL_PEL . "', '" . $cKD_TRF . "', " . $nDAYA . ", '" . $emapData[22] . "', '" . $cLOC_FOTO . "', '" . $cFILTER_CODE . "', 'Binuang'), ";

				}
			}
			$cQUERYBACA .= "; ";
            $cQUERYBACA = str_replace( ", ;" , ";", $cQUERYBACA );
			$qQUERYBACA = SYS_QUERY($cQUERYBACA);

			fclose($file);
			echo $count.' record(s) CSV File has been successfully Imported';
?>

