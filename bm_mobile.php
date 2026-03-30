<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

$config_path = dirname(__DIR__) . '/.rrrini/Rainbow.ini';
$CONFIG_INI = parse_ini_file($config_path);

//$CONFIG_INI = parse_ini_file("Rainbow.ini");

	$server 	= $CONFIG_INI['DBSERVER'];
	$database 	= $CONFIG_INI['DBPAYROLL'];
	$username 	= $CONFIG_INI['DBUSER'];
	$password 	= $CONFIG_INI['DBPASS'];
	$DB2=mysql_connect($server,$username,$password) or die("Can not open DB2");
	mysql_select_db($database, $DB2) or die("Can not open DB2");

	$cAPI='';
	if (isset($_GET['_api'])) {
		$cAPI=$_GET['_api'];
	}
	$cAPP_CODE = 'YAZA';
	if (isset($_GET['_APPCODE'])) {
		$cAPP_CODE = $_GET['_APPCODE'];
	}
		
switch($cAPI){

// update data dari mobile ke server, background process. 
// access by bm_mobile.php?_api=updata&_data=08215004003&_idpelanggan=14001380402&_rbm=A&_token=99.99&_rumah=1&_sgl1=0&_sgl2=0&_indikator=0&_waktu=5/2/2017%2013:00:00&_latt=-6.280000&_long=106.989000&_note=test&_alamat=&_APPCODE='YAZA'
case 'updata':
	if (isset($_GET['_data'])) {
		$cHP 	= $_GET['_data'];
		$cIDPEL = $_GET['_idpelanggan'];
		$cKDRBM = $_GET['_rbm'];
		$nTOKEN = $_GET['_token'];
		$nRUMAH = $_GET['_rumah'];
		$nSGL_1 = $_GET['_sgl1'];
		$nSGL_2 = $_GET['_sgl2'];
		$nINDKT = $_GET['_indikator'];
		$dWAKTU = $_GET['_waktu'];
		$nLATTI = $_GET['_latt'];
		$nLONGI = $_GET['_long'];
		$cNOTES = $_GET['_note'];
//		$cLOC_F = $_GET['_foto'];
		$cALAMT = $_GET['_alamat'];		// ada isi jika perubahan alamat dimasukkan
		$NOW = date("Y-m-d H:i:s");
		$cMCB = ' ';
		if (isset($_GET['_mcb'])) {
			$cMCB = $_GET['_mcb'];
		}
		$nSPASI = strpos($dWAKTU, ' ');
		$cWAKTU = substr($dWAKTU, 0, $nSPASI);
		$aWAKTU = explode("/", $cWAKTU);
		$cTANGGAL = $aWAKTU[1];
		if(strlen($cTANGGAL)<2) {
			$cTANGGAL = '0'.$cTANGGAL;
		}
		$cBULAN = $aWAKTU[0];
		if(strlen($cBULAN)<2) {
			$cBULAN = '0'.$cBULAN;
		}
		$xWAKTU = $aWAKTU[2].'-'.$cBULAN.'-'.$cTANGGAL.' '.substr($dWAKTU,-8);
		
		$a_TB_CATTER	= mysql_fetch_array(mysql_query("select NOMOR_HP, KODE_AREA, KODE_CATTER, USER_CODE, APP_CODE, LAST_LATT, LAST_LONG from bm_tb_catter1  where NOMOR_HP = '$cHP' and DELETOR=''"));
		$cAPP_CODE = $a_TB_CATTER['APP_CODE'];
		$nDPT_LOKASI = 1;

		if($nLATTI=='') {
			$a_DT_BACA	= mysql_fetch_array(mysql_query("select LATTI, LONGI from bm_dt_baca where IDPEL='$cIDPEL' and APP_CODE='$cAPP_CODE' and DELETOR='' order by TGL_BACA desc limit 1"));
			$nLATTI	= $a_DT_BACA['LATTI'];
			$nLONGI	= $a_DT_BACA['LONGI'];
			$nDPT_LOKASI = 0;
		}
		if($nLATTI=='') {
			$a_DT_BACA	= mysql_fetch_array(mysql_query("select LATTI, LONGI from bm_dt_baca where PETUGAS='$a_TB_CATTER[KODE_CATTER]' and APP_CODE='$cAPP_CODE' and DELETOR='' order by TGL_BACA desc limit 1"));
			$nLATTI	= $a_DT_BACA['LATTI'];
			$nLONGI	= $a_DT_BACA['LONGI'];
		}
		if($nLATTI=='') {
			$nLATTI	= $a_TB_CATTER['LAST_LATT'];
			$nLONGI	= $a_TB_CATTER['LAST_LONG'];
		}

		if(substr($cKDRBM,0,4)=='unlis') {
			$cKDRBM=' ';
		}
		if(substr($cKDRBM,0,3)=='null') {
			$cKDRBM=' ';
		}
		if($nDPT_LOKASI == 1) {
			$cTB_CATER	= "update bm_tb_catter1 set LAST_LATT='$nLATTI', LAST_LONG='$nLONGI'
				where APP_CODE='$a_TB_CATTER[APP_CODE]' and DELETOR='' and KODE_CATTER='$a_TB_CATTER[KODE_CATTER]'";
			mysql_query($cTB_CATER);
		}

// -----------------------------------------------------------------------------------------------------------------------
		$q_BM_TB_PEL	= mysql_query("select IDPEL from bm_tb_pel  where IDPEL = '$cIDPEL' and  APP_CODE='$cAPP_CODE' and DELETOR=''");
		$nREC = mysql_num_rows($q_BM_TB_PEL);
		if($nREC==0) {
			$m_BM_TB_PEL	= mysql_query("select IDPEL from bm_tb_pel  where NOMOR_KWH = '$cIDPEL' and  APP_CODE='$cAPP_CODE' and DELETOR=''");
			$mREC = mysql_num_rows($m_BM_TB_PEL);
			if($mREC==0) {
				$cQUERY="insert into bm_tb_pel set IDPEL='$cIDPEL', UNITUP='$a_TB_CATTER[KODE_AREA]', ALAMAT='$cALAMT', NOMOR_KWH='$cIDPEL', 
					ENTRY='$a_TB_CATTER[USER_CODE]', DATE_ENTRY='$NOW', APP_CODE='$a_TB_CATTER[APP_CODE]'";
				mysql_query($cQUERY);
			} else {
				$a_BM_TB_PEL = mysql_fetch_array($m_BM_TB_PEL);
				$cIDPEL = $a_BM_TB_PEL['IDPEL'];
			}
		}
		$q_BM_TB_PLG	= mysql_query("select IDPEL from bm_tb_plg  where IDPEL = '$cIDPEL' and  APP_CODE='$cAPP_CODE' and DELETOR=''");
		$nREC = mysql_num_rows($q_BM_TB_PLG);
		if($nREC==0) {
			$cQUERY="insert into bm_tb_plg set IDPEL='$cIDPEL', KODE_RUTE='$cKDRBM', ALAMAT_PLG='$cALAMT', 
				ENTRY='$a_TB_CATTER[USER_CODE]', DATE_ENTRY='$NOW', APP_CODE='$a_TB_CATTER[APP_CODE]'";
			mysql_query($cQUERY);
		}
// -----------------------------------------------------------------------------------------------------------------------

		$qBM_DT_BACA = false;
		$q_CEK_DOUBLE	= mysql_query("select IDPEL from bm_dt_baca  where IDPEL = '$cIDPEL' and PETUGAS='$a_TB_CATTER[KODE_CATTER]' and APP_CODE='$cAPP_CODE' and DELETOR=''");
		$nREC = mysql_num_rows($q_CEK_DOUBLE);
		if($nREC==0) {

			$cBM_DT_BACA = "insert into bm_dt_baca set TGL_BACA='$xWAKTU'";
			$cBM_DT_BACA.= ", APP_CODE='$a_TB_CATTER[APP_CODE]', IDPEL='$cIDPEL', KODE_RBM='$cKDRBM', SISA_TOKEN='$nTOKEN'";
			$cBM_DT_BACA.= ", PETUGAS='$a_TB_CATTER[KODE_CATTER]', KONDISI_RMH='$nRUMAH', SEGEL_TLG='$nSGL_1', SEGEL_TER='$nSGL_2'";
			$cBM_DT_BACA.= ", LAMPU_INDI='$nINDKT', LATTI='$nLATTI', LONGI='$nLONGI', NOTE='$cNOTES', MCB='$cMCB', ENTRY='$a_TB_CATTER[USER_CODE]', DATE_ENTRY='$NOW'";
			$qBM_DT_BACA = mysql_query($cBM_DT_BACA);
		} else {
			$qBM_DT_BACA = true;
		}

		$cTB_PLG	= "update bm_tb_plg set PETUGAS='$a_TB_CATTER[KODE_CATTER]', KODE_RUTE='$cKDRBM', LAST_VISIT='$xWAKTU', UP_DATE='$a_TB_CATTER[USER_CODE]', UPD_DATE='$NOW', ALAMAT_PLG='$cALAMT'
			where APP_CODE='$a_TB_CATTER[APP_CODE]' and DELETOR='' and IDPEL='$cIDPEL'";
		mysql_query($cTB_PLG);


		$json = array();
		if($qBM_DT_BACA) {
			$json[] = array($cHP => $cHP);		// return no. hp
		} else {
			$json[] = array('UPDATA' => 'insert failed');		// return ''
		}
	
		header('Access-Control-Allow-Origin: *');
		echo json_encode($json);
	}
	mysql_close($DB2);	break;
	
case 'addBaca':
	if (isset($_GET['_data'])) {
		$cHP 	= $_GET['_data'];
		$cIDPEL = $_GET['_idpelanggan'];
		$cKDRBM = $_GET['_rbm'];
		$nTOKEN = $_GET['_token'];
		$nRUMAH = $_GET['_rumah'];
		$nSGL_1 = $_GET['_sgl1'];
		$nSGL_2 = $_GET['_sgl2'];
		$nINDKT = $_GET['_indikator'];
		$xWAKTU = $_GET['_waktu'];
		$nLATTI = $_GET['_latt'];
		$nLONGI = $_GET['_long'];
		$cNOTES = $_GET['_note'];
//		$cLOC_F = $_GET['_foto'];
		$cALAMT = $_GET['_alamat'];		// ada isi jika perubahan alamat dimasukkan
		$NOW = date("Y-m-d H:i:s");
		$cMCB = ' ';
		if (isset($_GET['_mcb'])) {
			$cMCB = $_GET['_mcb'];
		}

		$a_TB_CATTER	= mysql_fetch_array(mysql_query("select NOMOR_HP, KODE_AREA, KODE_CATTER, USER_CODE, APP_CODE, LAST_LATT, LAST_LONG from bm_tb_catter1  where NOMOR_HP = '$cHP' and DELETOR=''"));
		$cAPP_CODE = $a_TB_CATTER['APP_CODE'];
		$nDPT_LOKASI = 1;
		//==============================================
		/*if($nLATTI=='') {
			$a_DT_BACA	= mysql_fetch_array(mysql_query("select LATTI, LONGI from bm_dt_baca where IDPEL='$cIDPEL' and APP_CODE='$cAPP_CODE' and DELETOR='' order by TGL_BACA desc limit 1"));
			if($a_DT_BACA['LATTI']=='') {
				$b_DT_BACA	= mysql_fetch_array(mysql_query("select LATTI, LONGI from bm_dt_baca where PETUGAS='$a_TB_CATTER[KODE_CATTER]' and APP_CODE='$cAPP_CODE' and DELETOR='' order by TGL_BACA desc limit 1"));
				if($b_DT_BACA['LATTI']=='') {
					$nLATTI	= $a_TB_CATTER['LAST_LATT'];
					$nLONGI	= $a_TB_CATTER['LAST_LONG'];
				} else {
					$nLATTI	= $b_DT_BACA['LATTI'];
					$nLONGI	= $b_DT_BACA['LONGI'];
					$nDPT_LOKASI == 0;
				}
			} else {
				$nLATTI	= $a_DT_BACA['LATTI'];
				$nLONGI	= $a_DT_BACA['LONGI'];
				$nDPT_LOKASI == 0;
			}
		}*/
		//==============================================
		if($nLATTI=='') {
			$a_DT_BACA	= mysql_fetch_array(mysql_query("select LATTI, LONGI from bm_dt_baca where IDPEL='$cIDPEL' and APP_CODE='$cAPP_CODE' and DELETOR='' order by TGL_BACA desc limit 1"));
			$nLATTI	= $a_DT_BACA['LATTI'];
			$nLONGI	= $a_DT_BACA['LONGI'];
			$nDPT_LOKASI = 0;
		}
		if($nLATTI=='') {
			$a_DT_BACA	= mysql_fetch_array(mysql_query("select LATTI, LONGI from bm_dt_baca where PETUGAS='$a_TB_CATTER[KODE_CATTER]' and APP_CODE='$cAPP_CODE' and DELETOR='' order by TGL_BACA desc limit 1"));
			$nLATTI	= $a_DT_BACA['LATTI'];
			$nLONGI	= $a_DT_BACA['LONGI'];
		}
		if($nLATTI=='') {
			$nLATTI	= $a_TB_CATTER['LAST_LATT'];
			$nLONGI	= $a_TB_CATTER['LAST_LONG'];
		}

		if(substr($cKDRBM,0,5)=='unlis') {
			$cKDRBM=' ';
		}
		if(substr($cKDRBM,0,4)=='null') {
			$cKDRBM=' ';
		}
		if($nDPT_LOKASI == 1) {
			$cTB_CATER	= "update bm_tb_catter1 set LAST_LATT='$nLATTI', LAST_LONG='$nLONGI'
				where APP_CODE='$a_TB_CATTER[APP_CODE]' and DELETOR='' and KODE_CATTER='$a_TB_CATTER[KODE_CATTER]'";
			mysql_query($cTB_CATER);
		}

// -----------------------------------------------------------------------------------------------------------------------
		$q_BM_TB_PEL	= mysql_query("select IDPEL from bm_tb_pel  where IDPEL = '$cIDPEL' and  APP_CODE='$cAPP_CODE' and DELETOR=''");
		$nREC = mysql_num_rows($q_BM_TB_PEL);
		if($nREC==0) {
			$m_BM_TB_PEL	= mysql_query("select IDPEL from bm_tb_pel  where NOMOR_KWH = '$cIDPEL' and  APP_CODE='$cAPP_CODE' and DELETOR=''");
			$mREC = mysql_num_rows($m_BM_TB_PEL);
			if($mREC==0) {
				$cQUERY="insert into bm_tb_pel set IDPEL='$cIDPEL', UNITUP='$a_TB_CATTER[KODE_AREA]', ALAMAT='$cALAMT', NOMOR_KWH='$cIDPEL', 
					ENTRY='$a_TB_CATTER[USER_CODE]', DATE_ENTRY='$NOW', APP_CODE='$a_TB_CATTER[APP_CODE]'";
				mysql_query($cQUERY);
			} else {
				$a_BM_TB_PEL = mysql_fetch_array($m_BM_TB_PEL);
				$cIDPEL = $a_BM_TB_PEL['IDPEL'];
			}
		}
		$q_BM_TB_PLG	= mysql_query("select IDPEL from bm_tb_plg  where IDPEL = '$cIDPEL' and  APP_CODE='$cAPP_CODE' and DELETOR=''");
		$nREC = mysql_num_rows($q_BM_TB_PLG);
		if($nREC==0) {
			$cQUERY="insert into bm_tb_plg set IDPEL='$cIDPEL', KODE_RUTE='$cKDRBM', ALAMAT_PLG='$cALAMT', 
				ENTRY='$a_TB_CATTER[USER_CODE]', DATE_ENTRY='$NOW', APP_CODE='$a_TB_CATTER[APP_CODE]'";
			mysql_query($cQUERY);
		}
// -----------------------------------------------------------------------------------------------------------------------

		$lCHECK = false;
		$q_CEK_DOUBLE	= mysql_query("select IDPEL from bm_dt_baca  where IDPEL = '$cIDPEL' and PETUGAS='$a_TB_CATTER[KODE_CATTER]' and APP_CODE='$cAPP_CODE' and TGL_BACA='$xWAKTU' and DELETOR=''");
		$nREC = mysql_num_rows($q_CEK_DOUBLE);
		if($nREC==0) {
			$cBM_DT_BACA = "insert into bm_dt_baca set TGL_BACA='$xWAKTU'";
			$cBM_DT_BACA.= ", APP_CODE='$a_TB_CATTER[APP_CODE]', IDPEL='$cIDPEL', KODE_RBM='$cKDRBM', SISA_TOKEN='$nTOKEN'";
			$cBM_DT_BACA.= ", PETUGAS='$a_TB_CATTER[KODE_CATTER]', KONDISI_RMH='$nRUMAH', SEGEL_TLG='$nSGL_1', SEGEL_TER='$nSGL_2'";
			$cBM_DT_BACA.= ", LAMPU_INDI='$nINDKT', LATTI='$nLATTI', LONGI='$nLONGI', NOTE='$cNOTES', MCB='$cMCB', ENTRY='$a_TB_CATTER[USER_CODE]', DATE_ENTRY='$NOW'";
			$qDT_BACA = mysql_query($cBM_DT_BACA);
			if($qDT_BACA) {
				$lCHECK = mysql_query("update bm_tb_plg set PETUGAS='$a_TB_CATTER[KODE_CATTER]', KODE_RUTE='$cKDRBM', LAST_VISIT='$xWAKTU', UP_DATE='$a_TB_CATTER[USER_CODE]', UPD_DATE='$NOW', ALAMAT_PLG='$cALAMT' where APP_CODE='$a_TB_CATTER[APP_CODE]' and DELETOR='' and IDPEL='$cIDPEL'");
				
			}
		} else {
			$lCHECK = true;
		}
		
		$json = array();
		if($lCHECK) {
			$json[] = array($cHP => $cHP);		// return no. hp
		} else {
			$json[] = array('UPDATA' => 'insert failed');
		}
	
		header('Access-Control-Allow-Origin: *');
		echo json_encode($json);
	}
	mysql_close($DB2);	break;
	
// retrive label and message access by bm_mobile.php?_api=msg&_data=0811526550
case 'msg':
	if (isset($_GET['_data'])) {
		$cHP = $_GET['_data'];
		$a_TB_CATTER	= mysql_fetch_array(mysql_query("select NOMOR_HP, KODE_AREA, KODE_CATTER, APP_CODE from bm_tb_catter1  where NOMOR_HP = '$cHP' and DELETOR=''"));
		$q_LABEL	= mysql_query("select MSG_CODE, SYS_MS from sys_msg where APP_CODE = '$cAPP_CODE'");
		$json = array();
		while ($rows = mysql_fetch_assoc($q_LABEL))
			$json[] = $rows;
		header('Access-Control-Allow-Origin: *');
		echo json_encode($json);
	}
	break;

// retrive data pelanggan by no_hp, kode rbm, access by bm_mobile.php?_api=pelanggan&_data=0811526550&_rbm=A
case 'pelanggan':
	if (isset($_GET['_data'])) {
		$cHP  = $_GET['_data'];
		$cRBM = '';
		if (isset($_GET['_rbm'])) {
			$cRBM = $_GET['_rbm'];
		}
		$a_TB_CATTER	= mysql_fetch_array(mysql_query("select NOMOR_HP, KODE_AREA, KODE_CATTER, APP_CODE from bm_tb_catter1  where NOMOR_HP = '$cHP' and  DELETOR=''"));
		$cAPP_CODE = $a_TB_CATTER['APP_CODE'];
		$q_SYS_VERS	= mysql_query("select * from rainbow where KEY_FIELD = 'BM_START' and  APP_CODE='$cAPP_CODE' and DELETOR=''");
		$a_SYS_VERS = mysql_fetch_array($q_SYS_VERS);
		$cSTART_DATE = $a_SYS_VERS['KEY_CONTEN'];
		$cBM_TB_PEL = "SELECT A.UNITUP,A.IDPEL, REPLACE(A.NAMA_PEL, '{#44}', '\`') as NAMA_PEL, REPLACE(A.ALAMAT, '{#44}', '\`') as ALAMAT, A.KODE_TARIF,A.DAYA,A.NOMOR_KWH,A.MERK_KWH,A.LAST_VISIT_DATE FROM bm_tb_pel A INNER JOIN (SELECT IDPEL FROM bm_tb_plg WHERE (PETUGAS='$a_TB_CATTER[KODE_CATTER]' OR PETUGAS='') AND (KODE_RUTE='$cRBM' OR KODE_RUTE='' OR KODE_RUTE='null' OR KODE_RUTE='unlis') AND LAST_VISIT<'$cSTART_DATE' AND APP_CODE='$cAPP_CODE') B ON B.IDPEL=A.IDPEL WHERE A.UNITUP='$a_TB_CATTER[KODE_AREA]' AND A.APP_CODE='$cAPP_CODE' AND A.DELETOR='' group by IDPEL limit 7500";
		$rBM_TB_PEL=mysql_query($cBM_TB_PEL);
		$json = array();
		$nREC_TB_PEL = mysql_num_rows($rBM_TB_PEL);
		if($nREC_TB_PEL>0) {
			while ($rows = mysql_fetch_assoc($rBM_TB_PEL)) {
				$json[] = $rows;
			}				
		} else {
			$json[] = array('NOMOR_HP' => 'Daftar pelanggan tidak ada.');
		}
		header('Access-Control-Allow-Origin: *');
		echo json_encode($json);
	}
	break;
	
// retrive data pelanggan pasca bayar / tagihan by no_hp, kode rbm, 
// access by http://fahlevi.co/bm_mobile.php?_api=pel_pasca&_data=081250902305&_rbm=A&_thn=2017&_bln=6
case 'pel_pasca':
	if (isset($_GET['_data'])) {
		$cHP  = $_GET['_data'];
		$cRBM = '';
		if (isset($_GET['_rbm'])) {
			$cRBM = $_GET['_rbm'];
		}
		$nTHN = $_GET['_thn'];
		$nBLN = $_GET['_bln'];
		$a_TB_CATTER	= mysql_fetch_array(mysql_query("select NOMOR_HP, KODE_AREA, KODE_CATTER, APP_CODE from bm_tb_catter1  where NOMOR_HP = '$cHP' and  DELETOR=''"));
		$cAPP_CODE = $a_TB_CATTER['APP_CODE'];
		$q_SYS_VERS	= mysql_query("select * from rainbow where KEY_FIELD = 'BM_START' and  APP_CODE='$cAPP_CODE' and DELETOR=''");
		$a_SYS_VERS = mysql_fetch_array($q_SYS_VERS);
		$cSTART_DATE = $a_SYS_VERS['KEY_CONTEN'];
		$cBM_TB_PEL = "select A.UNITUP,A.IDPEL, REPLACE(A.NAMA_PEL, '{#44}', '\`') as NAMA_PEL, REPLACE(A.ALAMAT, '{#44}', '\`') as ALAMAT, A.KODE_RBM, A.DAYA,B.LEMBAR,B.TAGIHAN, B.RPBK FROM bm_pel_pasca A 
			INNER JOIN (select IDPEL, LEMBAR, TAGIHAN, RPBK from bm_plg_pasca where TAHUN ='$nTHN' AND BULAN='$nBLN' AND APP_CODE='$cAPP_CODE') B ON B.IDPEL=A.IDPEL 
			where substr(A.KODE_RBM,4,3)='$a_TB_CATTER[KODE_CATTER]' AND substr(A.KODE_RBM,7,1)='$cRBM' AND A.APP_CODE='$cAPP_CODE' AND A.DELETOR='' group by A.IDPEL limit 7500";
//		var_dump($cBM_TB_PEL); exit;
		$rBM_TB_PEL=mysql_query($cBM_TB_PEL);
		$json = array();
		$nREC_TB_PEL = mysql_num_rows($rBM_TB_PEL);
		if($nREC_TB_PEL>0) {
			while ($rows = mysql_fetch_assoc($rBM_TB_PEL)) {
				$json[] = $rows;
			}				
		} else {
			$json[] = array('NOMOR_HP' => 'Daftar pelanggan tidak ada.');
		}
		header('Access-Control-Allow-Origin: *');
		echo json_encode($json);
	}
	break;
	
// import table RBM : http://localhost/rz/bm_mobile.php?_api=rbm

// login + version
case 'hp':
	$cTIPE_HP 	= '';
	$cOS_HP 	= '';
	$cMEM_HP 	= '';
	$cRAM_HP 	= '';
	if (isset($_GET['_tipehp'])) {
		$cTIPE_HP = $_GET['_tipehp'];
	}
	if (isset($_GET['_oshp'])) {
		$cOS_HP = $_GET['_oshp'];
	}
	if (isset($_GET['_memhp'])) {
		$cMEM_HP = $_GET['_memhp'];
	}
	if (isset($_GET['_ramhp'])) {
		$cRAM_HP = $_GET['_ramhp'];
	}
	if (isset($_GET['_data'])) {
		$cHP = $_GET['_data'];

		$q_TB_CATTER	= mysql_query("select NOMOR_HP, USER_ROLE, APP_CODE from bm_tb_catter1  where NOMOR_HP = '$cHP' and CATTER_STAT = 0 and DELETOR=''");
		$a_TB_CATTER=mysql_fetch_array($q_TB_CATTER);

		$c_SYS_PARA = "select * from rainbow WHERE KEY_FIELD='BM_VERSION' and APP_CODE='".$a_TB_CATTER['APP_CODE']."'";
		$q_SYS_PARA=mysql_query($c_SYS_PARA);
		$r_SYS_PARA=mysql_fetch_array($q_SYS_PARA);
		$c_VERSION = $r_SYS_PARA['KEY_CONTEN'];

		$q_UPDATE_CT	= mysql_query("update bm_tb_catter1 set TIPE_HP='$cTIPE_HP', OS_HP='$cOS_HP', MEM_HP='$cMEM_HP', RAM_HP='$cRAM_HP' where NOMOR_HP = '$cHP' and DELETOR=''");

		$q_TB_CATTER	= mysql_query("select KODE_CATTER, NAMA_CATTER, KODE_AREA, TARGET, NOMOR_HP, USER_ROLE, APP_CODE from bm_tb_catter1  where NOMOR_HP = '$cHP' and CATTER_STAT = 0 and DELETOR=''");
		$json = array();
		if(mysql_num_rows($q_TB_CATTER)>0) {
			$rows = mysql_fetch_assoc($q_TB_CATTER);
			$rows['VERSION'] = $c_VERSION;
			$json[] = $rows;
		} else {
			$json[] = array('NOMOR_HP' => 'Nomor HP yang digunakan tidak terdaftar, silahkan coba lagi atau hubungi Administrator.');
		}
		header('Access-Control-Allow-Origin: *');
		echo json_encode($json);
	}
	break;

// cek nomor meter http://localhost/rz/bm_mobile.php?_api=mtr&_data=98989899898 
case 'mtr':
	$rows[] = array('NOMOR_KWH' => 'Nomor meter tidak terdaftar, Lanjutkan ?.');
	if (isset($_GET['_data'])) {
		$cKWH = $_GET['_data'];
		$q_BM_TB_PEL	= mysql_query("select UNITUP, IDPEL, NAMA_PEL, ALAMAT, KODE_TARIF, DAYA, NOMOR_KWH, MERK_KWH, LAST_VISIT_DATE from bm_tb_pel  where NOMOR_KWH = '$cKWH' and  APP_CODE='$cAPP_CODE' and DELETOR=''");
		if(mysql_num_rows($q_BM_TB_PEL)>0){
			$a_BM_TB_PEL 	= mysql_fetch_assoc($q_BM_TB_PEL);
			$rows[] = $a_BM_TB_PEL;
		}
	}
	echo json_encode($rows);
	break;

// import table RBM : http://localhost/rz/bm_mobile.php?_api=rbm
case 'upload':
		header('Access-Control-Allow-Origin: *');
		$new_image_name = urldecode($_FILES["file"]["name"]);
		move_uploaded_file($_FILES["file"]["tmp_name"], "upload/".$new_image_name);
	break;

case 'uploadLog':
		header('Access-Control-Allow-Origin: *');
		if (isset($_GET['_data'])) {
			$cHP = $_GET['_data'];

			$q_TB_CATTER	= mysql_query("select NOMOR_HP, KODE_CATTER, NAMA_CATTER, NOMOR_HP from bm_tb_catter1  where NOMOR_HP = '$cHP' and DELETOR=''");
			$a_TB_CATTER=mysql_fetch_array($q_TB_CATTER);

			$cLOG_FILE = $a_TB_CATTER['KODE_CATTER'].'_'.$a_TB_CATTER['NAMA_CATTER'].'_'.$a_TB_CATTER['NOMOR_HP'];
			$cLOG_FILE.= '_'.urldecode($_FILES["file"]["name"]);
			move_uploaded_file($_FILES["file"]["tmp_name"], "log/".$cLOG_FILE);
		}
	break;

// import table RBM : http://localhost/rz/bm_mobile.php?_api=rbm
case 'mcb':
		$c_TBL_MCB	= "select * from bm_tb_mcb where APP_CODE='$cAPP_CODE'";
		$q_TBL_MCB	= mysql_query($c_TBL_MCB);
		$json = array();
		while ($rows = mysql_fetch_assoc($q_TBL_MCB)) {
			$json[] = $rows;
		}
		header('Access-Control-Allow-Origin: *');
		echo json_encode($json);
	break;

// import table RBM : http://localhost/rz/bm_mobile.php?_api=rbm
case 'rbm':
		$c_TBL_RBM	= "select KODE_RUTE from bm_tb_rute where APP_CODE='$cAPP_CODE' and DELETOR=''";
		$q_TBL_RBM	= mysql_query($c_TBL_RBM);
		$json = array();
		while ($rows = mysql_fetch_assoc($q_TBL_RBM)) {
			$json[] = $rows;
		}
		header('Access-Control-Allow-Origin: *');
		echo json_encode($json);
	break;

// get main menu http://localhost/rz/bm_mobile.php?_api=menu&_data=MOBILE&_appcode=YAZA&_hp=
case 'menu':
	$nMENU_GRUP = 1;
	$cFILTER_ROLE = '';
	if (isset($_GET['_hp'])) {
		$cHP  = $_GET['_hp'];
		$a_TB_CATTER	= mysql_fetch_array(mysql_query("select NOMOR_HP, USER_ROLE, APP_CODE from bm_tb_catter1  where NOMOR_HP = '$cHP' and  DELETOR=''"));
		$nMENU_GRUP = $a_TB_CATTER['USER_ROLE'];
	}
	if($nMENU_GRUP != 0) {
		$cFILTER_ROLE = ' and M_SPARATOR='.$nMENU_GRUP;
	}
	if (isset($_GET['_data'])) {
		

		$cM_CAPTION = $_GET['_data'];	// it must be MOBILE
		$c_MOBILE_MN	= "select ITEM as urut, LOK_ICON as icon, ENG_PROMPT as label, ENG_MSG as url, MENU_NOTE as block, M_SPARATOR as role from sys_menu  where MAIN = '$cM_CAPTION' and ITEM>0 and  APP_CODE='$a_TB_CATTER[APP_CODE]' $cFILTER_ROLE order by ITEM";
//		var_dump($c_MOBILE_MN); exit;
		$q_MOBILE_MN	= mysql_query($c_MOBILE_MN);

		$aX = array();
		while($a_MOBILE_MN=mysql_fetch_assoc($q_MOBILE_MN)) {
			array_push($aX, $a_MOBILE_MN);
		}
		$rows = $aX;
		echo json_encode($rows);
//		var_dump($aX); exit();
	}
	break;

// get system name /  description : http://localhost/rz/bm_mobile.php?_api=sysname
case 'sysname':
		$q_SYS_NAME	= mysql_query("select KEY_CONTEN from rainbow where KEY_FIELD = '_SYSNAME' and  APP_CODE='$cAPP_CODE' and DELETOR=''");
		$a_SYS_NAME = mysql_fetch_array($q_SYS_NAME);
		$rows[] = $a_SYS_NAME;
		echo json_encode($rows);
	break;
case 'version':
	$cHP 	= '';
	$cMEM_HP 	= '';
	$cRAM_HP 	= '';
	if (isset($_GET['_memhp'])) {
		$cMEM_HP = $_GET['_memhp'];
	}
	if (isset($_GET['_ramhp'])) {
		$cRAM_HP = $_GET['_ramhp'];
	}
	
	if (isset($_GET['_nohp'])) {
		$cHP = $_GET['_nohp'];
		$q_UPDATE_CT	= mysql_query("update bm_tb_catter1 set MEM_HP='$cMEM_HP', RAM_HP='$cRAM_HP' where NOMOR_HP = '$cHP' and APP_CODE='$cAPP_CODE' and DELETOR=''");
	}
	
	$q_SYS_VERS	= mysql_query("select KEY_CONTEN from rainbow where KEY_FIELD = 'BM_VERSION' and  APP_CODE='$cAPP_CODE' and DELETOR=''");
	$a_SYS_VERS = mysql_fetch_assoc($q_SYS_VERS);
	$rows[] = $a_SYS_VERS;
	echo json_encode($rows);
	break;
case 'label':
	if (isset($_GET['_data'])) {
		$cLBL = $_GET['_data'];
		$q_LABEL	= mysql_query("select MSG_CODE, SYS_MS from sys_msg where MSG_CODE = '$cLBL'");
		$a_LABEL 	= mysql_fetch_assoc($q_LABEL);
		$json = array();
		$rows[] = $a_LABEL;
		echo json_encode($rows);
	}
	break;
case 'mbl_label':
	$json[] = array('GATOT' => 'Message record belum tersedia.');
	if (isset($_GET['_APPCODE'])) {
		$q_MBL_MOBILE= mysql_query("select MSG_CODE, SYS_MS from sys_msg where left(MSG_CODE,2) = 'BM' and APP_CODE='$_GET[_APPCODE]' order by MSG_CODE");
		$json = array();
		if(mysql_num_rows($q_MBL_MOBILE)>0){
			while ($rows = mysql_fetch_assoc($q_MBL_MOBILE)) {
				$json[] = $rows;
			}
		} else {
			$json[] = array('GATOT' => 'Message record belum tersedia.');
		}
	}
	echo json_encode($json);
	break;
case 'login':
	if (isset($_GET['_data'])) {
		$cUSER = $_GET['_data'];
		$q_TB_CATTER	= mysql_query("select NOMOR_HP, USER_ROLE from bm_tb_catter1  where NOMOR_HP = '$cUSER' and  APP_CODE='$cAPP_CODE' and DELETOR=''");
		$json = array();
		if(mysql_num_rows($q_TB_CATTER)>0) {
			while ($rows = mysql_fetch_assoc($q_TB_CATTER))
				$json[] = $rows;
		} else {
			$json[] = array($cUSER => S_MSG('PN38','Nomor HP yang digunakan tidak terdaftar, silahkan coba lagi atau hubungi Administrator.'));
		}
		header('Access-Control-Allow-Origin: *');
		echo json_encode($json);
	}
	break;

case 'logon':
	$database1='sys_data';
	if (isset($_GET['_data'])) {
		$USERNAME = $_GET['_data'];
		$USERPASS = $_GET['_pass'];

		$qQUERY="SELECT * FROM ".$database1.".tb_user where USER_CODE='$USERNAME' and PASSWORD='".md5($USERPASS)."'";
		$cQUERY=SYS_QUERY($qQUERY);
		$aUSER=SYS_FETCH($cQUERY);

		$cFILTER_CODE = $aUSER['APP_CODE'];
		$ADD_LOG=	SYS_QUERY("insert into ".$database1.".user_log set USER_CODE='$USERNAME', USER_ACT='Login sucsess'");

		$q_CATR="SELECT * FROM bm_tb_catter1 where APP_CODE='$cFILTER_CODE'";
		$c_CATR=SYS_QUERY($q_CATR);
		$aCATR=mysql_fetch_assoc($c_CATR);

		$json = array();
		if(mysql_num_rows($c_CATR)>0) {
			$json[] = array("ucode" => $aCATR['USER_CODE'], "uname" => $aCATR['NAMA_CATTER']);
		} else {
			$json[] = array('nama' => 'test');
		}
		header('Access-Control-Allow-Origin: *');
		echo json_encode($json);
	}
	break;

}
?>
