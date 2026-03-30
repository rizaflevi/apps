<?php
// bm_xls_bc_mtr.php

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
    $cFILTER_CODE 	= $_SESSION['data_FILTER_CODE'];
	$dPERIOD1=date("Y-m-d");
	$dPERIOD2=date("Y-m-d");

	if (isset($_GET['_t1'])) $dPERIOD1=$_GET['_t1'];
	if (isset($_GET['_t2'])) $dPERIOD2=$_GET['_t2'];

	$c_PTGS='';	$c_NAMA_PTGS='';    $cFILTER_PTGS='';
	if (isset($_GET['_c'])) {
		if($_GET['_c']=='') $cFILTER_PTGS='';
		else $cFILTER_PTGS=" and PETUGAS='".$_GET['_c']."'";
		$c_PTGS=$_GET['_c'];
	}

	$c_RBM='All';
	$cFILTER_RBM ='';
	if (isset($_GET['_r'])) {
		if($_GET['_r']=='') $cFILTER_RBM='';
		else	$cFILTER_RBM=" and bm_dt_baca.KODE_RBM='". $_GET['_r'] ."'";
		$c_RBM=$_GET['_r'];
	}
	if($c_PTGS!='') {
		$r_NM_PTGS	= SYS_FETCH(SYS_QUERY("select * from bm_tb_catter1 where KODE_CATTER='$c_PTGS' and APP_CODE='$cFILTER_CODE' and DELETOR=''"));
		$c_NAMA_PTGS=$r_NM_PTGS['NAMA_CATTER'];
	}

//	$cFILTER_DATA='';
//	if (isset($_GET['_q'])) {
//		$cFILTER_DATA=$_GET['_q'];
//	}

	$cFILTER_AREA='';
	if (isset($_GET['_a'])) {
		$cFILTER_AREA=$_GET['_a'];
		$cFILT_AREA = " and bm_tb_pel.UNITUP='$cFILTER_AREA'";
	}

	$cSQLCOMMAND= "SELECT bm_dt_baca.TGL_BACA, bm_dt_baca.IDPEL, bm_dt_baca.SISA_TOKEN, bm_dt_baca.LATTI, bm_dt_baca.LONGI, bm_dt_baca.PETUGAS, bm_dt_baca.KODE_RBM, bm_dt_baca.KONDISI_RMH, bm_dt_baca.SEGEL_TLG, bm_dt_baca.SEGEL_TER, bm_dt_baca.LAMPU_INDI, 
		B.TRF_TPSG, B.ALAMAT_PLG, C.IDPEL, C.UNITUP, C.NOMOR_KWH, C.NAMA_PEL, C.KODE_TARIF, C.DAYA, C.ALAMAT,
		bm_tb_catter1.NAMA_CATTER, bm_tb_kond.NAMA_KOND
		from bm_dt_baca
		inner join (select IDPEL, ALAMAT_PLG, TRF_TPSG, PETUGAS, KODE_RUTE, LAST_VISIT FROM bm_tb_plg where APP_CODE='$cFILTER_CODE' and DELETOR='' $cFILTER_PTGS) B ON B.IDPEL=bm_dt_baca.IDPEL 
		inner join (select UNITUP, IDPEL, NOMOR_KWH, NAMA_PEL, ALAMAT, KODE_TARIF, DAYA FROM bm_tb_pel where APP_CODE='$cFILTER_CODE' and DELETOR='' $cFILT_AREA) C ON C.IDPEL=bm_dt_baca.IDPEL 
		LEFT JOIN tb_area ON C.UNITUP=tb_area.KODE_AREA
		LEFT JOIN bm_tb_catter1 ON bm_dt_baca.PETUGAS=bm_tb_catter1.KODE_CATTER
		LEFT JOIN bm_tb_kond ON bm_dt_baca.KONDISI_RMH=bm_tb_kond.KODE_KOND
		where bm_dt_baca.APP_CODE='$cFILTER_CODE' and bm_dt_baca.DELETOR='' $cFILTER_RBM and
			left(bm_dt_baca.TGL_BACA,10)>='".$dPERIOD1."' and left(bm_dt_baca.TGL_BACA,10)<='".$dPERIOD2."'";

/*	$cSQLCOMMAND= "SELECT bbm_dt_baca.TGL_BACA, bm_dt_baca.IDPEL, bm_dt_baca.SISA_TOKEN, bm_dt_baca.LATTI, bm_dt_baca.LONGI, bm_dt_baca.PETUGAS, bm_dt_baca.KODE_RBM, bm_dt_baca.KONDISI_RMH, bm_dt_baca.SEGEL_TLG, bm_dt_baca.SEGEL_TER, bm_dt_baca.LAMPU_INDI, 
		bm_tb_pel.NAMA_PEL , bm_tb_pel.ALAMAT, bm_tb_pel.NOMOR_KWH, bm_tb_pel.KODE_TARIF, bm_tb_pel.DAYA, 
		bm_tb_plg.IDPEL, bm_tb_plg.TRF_TPSG, 
		tb_area.KODE_AREA , tb_area.NAMA_AREA,
		bm_tb_catter1.KODE_CATTER , bm_tb_catter1.NAMA_CATTER,
		bm_tb_kond.KODE_KOND , bm_tb_kond.NAMA_KOND 
		from bm_dt_baca
		LEFT JOIN bm_tb_pel ON bm_dt_baca.IDPEL=bm_tb_pel.IDPEL
		LEFT JOIN bm_tb_plg ON bm_tb_pel.IDPEL=bm_tb_plg.IDPEL
		LEFT JOIN tb_area ON bm_tb_pel.UNITUP=tb_area.KODE_AREA
		LEFT JOIN bm_tb_catter1 ON bm_dt_baca.PETUGAS=bm_tb_catter1.KODE_CATTER
		LEFT JOIN bm_tb_kond ON bm_dt_baca.KONDISI_RMH=bm_tb_kond.KODE_KOND
		where bm_dt_baca.APP_CODE='$cFILTER_CODE' and bm_dt_baca.DELETOR='' and
			left(bm_dt_baca.TGL_BACA,10)>='".$dPERIOD1."' and left(bm_dt_baca.TGL_BACA,10)<='".$dPERIOD2."'";
	if ($cFILTER_AREA!='') {
		$cSQLCOMMAND.= " and bm_tb_pel.UNITUP='$cFILTER_AREA'";
	}
*/
	if ($cFILTER_PTGS!='') 		$cSQLCOMMAND.= " and bm_dt_baca.PETUGAS='$c_PTGS'";
	$cSQLCOMMAND.= " group by NOMOR_KWH";
	$qBM_DT_BACA=SYS_QUERY($cSQLCOMMAND);
//		var_dump($cSQLCOMMAND);	exit();

	$cHEADER		= S_MSG('RQ01','Laporan Hasil Baca Meter');

	$cTGL1 			= S_MSG('RS02','Tanggal');
	$cTGL2 			= S_MSG('RS14','s/d');
	$cTANGGAL 		= S_MSG('RQ03','Tanggal');
	$cNAMA_TBL 		= S_MSG('PQ03','Nama Pelanggan');
	$cALAMAT 		= S_MSG('PQ04','Alamat');
	$cALM_PERSIL	= S_MSG('PQ11','Alamat Persil');
	$cNMR_METER 	= S_MSG('RQ04','Nomor Meter');
	$cSISA_TOKEN 	= S_MSG('RQ05','Sisa Token');
	$cLATTITUDE		= S_MSG('RQ06','Lattitude');
	$cLONGITUDE		= S_MSG('RQ07','Longitude');
	$cKONDISI_RMH	= S_MSG('RQ11','Kondisi Rumah');
	$cSEGEL_TEL		= S_MSG('RQ12','Segel Telinga');
	$cSEGEL_TERM	= S_MSG('RQ13','Segel Terminal');
	$cLAM_INDIKATOR	= S_MSG('RQ14','Lampu Indikator');
	$cRUTE_BACA		= S_MSG('RQ15','Kode RBM');
	$cID_PELANGGAN	= S_MSG('RQ16','IDPEL');
	$cTARIF			= S_MSG('RQ17','Tarif');
	$cDAYA			= S_MSG('PQ07','Daya');
	$cSTATUS		= S_MSG('RQ18','Status');
	$cKETERANGAN 	= S_MSG('PN10','Keterangan');
	$cPTGS			= S_MSG('RQ08','Petugas');

	$file="BacaMeter.xls";
	$test="<h2>".$cHEADER."</h2>";
	$test.="<table>";
	$test.="<tr><th style='text-align: left;'>".$cTGL1."</th>";
	$test.="<td>".$dPERIOD1."</td></tr>";
	$test.="<tr><th style='text-align: left;'>".$cTGL2."</th>"."<td>".$dPERIOD2."</td></tr>"."<th style='text-align: left;'>".$cPTGS."</th>"."</th><th>".($cFILTER_PTGS=='' ? 'All' : $c_NAMA_PTGS)."</th><tr><th style='text-align: left;'>".$cRUTE_BACA."</th><th>".($cFILTER_RBM=='' ? 'All' : $c_RBM)."</th></tr><tr></tr>";
	$test.="<tr><td>".$cTANGGAL."</td><td>".$cNMR_METER."</td><td>".$cSISA_TOKEN."</td><td>".$cLATTITUDE."</td><td>".$cLONGITUDE."</td><td>".$cPTGS."</td><td>".$cRUTE_BACA."</td><td>".$cID_PELANGGAN."</td><td>".$cNAMA_TBL."</td><td>".$cALAMAT."</td><td>".$cALM_PERSIL."</td><td>".$cTARIF."</td><td>".$cDAYA."</td><td>".$cKONDISI_RMH."</td><td>".$cSEGEL_TEL."</td><td>".$cSEGEL_TERM."</td><td>".$cLAM_INDIKATOR."</td></tr>";
		while ($row = mysql_fetch_assoc($qBM_DT_BACA)){
			$test.="<tr><td>".$row['TGL_BACA']."</td><td>".$row['NOMOR_KWH']."</td><td>".number_format($row['SISA_TOKEN'],2)."</td><td>".$row['LATTI']."</td><td>".$row['LONGI']."</td><td>".$row['NAMA_CATTER']."</td><td>".$row['KODE_RBM']."</td><td>".$row['IDPEL']."</td><td>".$row['NAMA_PEL']."</td><td>".$row['ALAMAT']."</td><td>".$row['ALAMAT_PLG']."</td><td>".$row['KODE_TARIF']."</td><td>".number_format($row['DAYA'])."</td><td>".$row['NAMA_KOND']."</td><td>".($row['SEGEL_TLG']==1 ? 'TIDAK LENGKAP' : 'Lengkap')."</td><td>".($row['LAMPU_INDI']==1 ? 'TIDAK LENGKAP' : 'Lengkap')."</td><td>".($row['SEGEL_TER']==1 ? 'TIDAK MENYALA' : 'Menyala')."</td></tr>";
		}
	$test.="</table>";
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$file");
	header('Cache-Control: max-age=0');
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); 
	header ('Cache-Control: cache, must-revalidate'); 
	header ('Pragma: public');
	echo $test;
?>