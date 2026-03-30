<?php
//	tb_group_customer.php
//	sch : school

	include "sysfunction.php";
    if (!isset($_SESSION['data_FILTER_CODE']))  session_start();

	$cAPP_CODE = $_SESSION['data_FILTER_CODE']; 
	$cUSERCODE = $_SESSION['gUSERCODE'];
	$cHELP_FILE = 'Doc/Tabel - Customer Group.pdf';

	$qQUERY=OpenTable('TbCustGroup', "APP_CODE='$cAPP_CODE' and DELETOR=''");

	$cHEADER 	= S_MSG('F161','Tabel kelompok');
	$KD_KLMP	= S_MSG('F162','Kode Kelompok');
	$cNM_KLPK	= S_MSG('F163','Nama Kelompok');
	$cDAFTAR	= S_MSG('F168','Daftar Kelompok');
	
	$cACTION=(isset($_GET['_a']) ? $_GET['_a'] : '');

switch($cACTION){
	default:
		$can_CREATE = TRUST($cUSERCODE, 'TB_CUST_GROUP_1ADD');
		$ADD_LOG	= APP_LOG_ADD('View', $cHEADER);
		DEF_WINDOW($cHEADER);
			$aACT = ($can_CREATE ? ['<a href="?_a='. md5('create_GROUP'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
			TFORM($cHEADER, '', $aACT, $cHELP_FILE);
				TDIV();
					TABLE('example');
						THEAD([$KD_KLMP, $cNM_KLPK]);
						echo '<tbody>';
							while($aREC_DISP=SYS_FETCH($qQUERY)) {
								$cHREFF="<a href='?_a=".md5('up__date')."&_g=".md5($aREC_DISP['KODE_GRP'])."'>";
								$aCOL = [$aREC_DISP['KODE_GRP'], DECODE($aREC_DISP['NAMA_GRP'])];
								TDETAIL($aCOL, [], '', [$cHREFF, $cHREFF]);
							}
						echo '</tbody>';
					eTABLE();
				eTDIV();
			eTFORM('*');
		END_WINDOW();
	break;

	case md5('create_GROUP'):
		$cADD_KLPK	= S_MSG('F170','Tambah Klpk');
		DEF_WINDOW($cHEADER);
			TFORM($cADD_KLPK, '?_a=tambah');
				TDIV();
					LABEL([3,3,3,6], '700', $KD_KLMP);
					INPUT('text', [2,2,2,6], '900', 'KODE_GROUP', '', '', '', '', 10, '', 'fix');
					LABEL([3,3,3,6], '700', $cNM_KLPK);
					INPUT('text', [6,6,6,6], '900', 'NAMA_GRUP', '', '', '', '', 0, '', 'fix');
					SAVE(S_MSG('F301','Save'));
				eTDIV();
			eTFORM();
		END_WINDOW();
	break;

	case md5('up__date'):
		$cEDIT_TBL	= S_MSG('F169','Edit Kelommpok Customer');
		$can_UPDATE = TRUST($cUSERCODE, 'TB_CUST_GROUP_2UPD');	$can_DELETE = TRUST($cUSERCODE, 'TB_CUST_GROUP_3DEL');
		$can_VIEW_AL = TRUST($cUSERCODE, 'TB_CUST_GRP_TNJ_VIEW');	$can_UPD_AL = TRUST($cUSERCODE, 'TB_CUST_GRP_TNJ_UPD');
		$cTAB_ALLOW		= S_MSG('PA44','Tunjangan');
		
		$cKODE_GRUP= $_GET['_g'];
		$view_TAB_TUNJ=TRUST($cUSERCODE, 'TB_CUST_GRP_TNJ_VIEW'); $upd_TAB_TUNJ=TRUST($cUSERCODE, 'TB_CUST_GRP_TNJ_UPD');
		
		$qQUERY=OpenTable('TbCustGroup', "APP_CODE='$cAPP_CODE' and DELETOR='' and md5(KODE_GRP)='$cKODE_GRUP'");
		$REC_GRUP=SYS_FETCH($qQUERY);
		DEF_WINDOW($cEDIT_TBL);
			$aACT = ($can_DELETE==1 ? ['<a href="?_a='.md5('DEL_GC').'&_id='. md5($REC_GRUP['KODE_GRP']). '" onClick="return confirm('. "'". S_MSG('F021','Benar data ini mau di hapus ?'). "'". ')"><i class="glyphicon glyphicon-minus-sign"></i>Delete</a>'] : []);
			TFORM($cEDIT_TBL, '?_a='.md5('SA_VE').'&_g='.md5($REC_GRUP['KODE_GRP']), $aACT, $cHELP_FILE);
				TDIV();
					LABEL([3,3,3,6], '700', $KD_KLMP);
					INPUT('text', [2,2,2,6], '900', 'KODE_GROUP', $REC_GRUP['KODE_GRP'], '', '', '', 0, 'disabled', 'fix');
					LABEL([3,3,3,6], '700', $cNM_KLPK);
					INPUT('text', [5,5,5,6], '900', 'NM_GRUP', $REC_GRUP['NAMA_GRP'], '', '', '', 0, '', 'fix');
					echo '<h4> </br></h4>';
					if($can_VIEW_AL || $can_UPD_AL)	{
						TAB(['Allowance'], ['fa-user'], [$cTAB_ALLOW], ['Tunjangan-tunjangan yang di dapat untuk setiap kelompok customer, di definisi kan disini.']);
						echo '<div class="tab-content primary">';
							echo '<div class="tab-pane fade in active" id="Allowance">';
								TDIV();
									TABLE('myTable');
										THEAD(['Tunjangan', 'Nama Pendek'], '', [], '*');
											echo '<tbody>';
													$qALOW_NM=OpenTable('TbCustGrpAlName', "md5(A.GCUST_CODE)='$_GET[_g]' and A.APP_CODE='$cAPP_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete)");
													while($rALOW_NM=SYS_FETCH($qALOW_NM)) {
														echo '<tr>';
															echo "<td><span><a href='?_a=".md5('upd_dtl_allowance')."&_r=$rALOW_NM[REC_ID]'>". $rALOW_NM['TNJ_NAME'].'</a></span></td>';
															echo '<td>'.$rALOW_NM['TNJ_SNAME'].'</td>';
														echo '</tr>';
													}
													echo '<tr>';
													echo '<td>';
														SELECT([12,12,12,12], 'NEW_AllOWN', '', '', 'select2');
															echo '<option></<option>';
															$REC_DATA=OpenTable('TbAllowance', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
															while($aREC_DETAIL=SYS_FETCH($REC_DATA)){
																echo "<option value='$aREC_DETAIL[TNJ_CODE]'  >".DECODE($aREC_DETAIL['TNJ_NAME'])."</option>";
															}
														echo '</select>';
													echo '</td>';
												echo '<tr>';
											echo '</tbody>';
									echo '</table>';
								eTDIV();
								CLEAR_FIX();
							eTDIV();
						eTDIV();
					}
					SAVE(($can_UPDATE ? S_MSG('F301','Save') : ''));
				eTDIV();
			eTFORM();
		END_WINDOW();
	break;
	case md5('upd_dtl_allowance'):
	// TODO finish it
	break;

	case "AddAllow":
		$xGROUP=$_GET['_e'];
		$cGROUP='';
		$pKODE_ALLW=$_POST['ADD_ALLOW_GR'];
		$qQUERY=OpenTable('TbCustGrpAllow', "APP_CODE='$cAPP_CODE' and DELETOR='' and md5(GCUST_CODE)='$xGROUP'");
		if($aGROUP=SYS_FETCH($qQUERY)) {
			$cGROUP = $aGROUP['GCUST_CODE'];
		}
		$qQUERY=OpenTable('TbCustGrpAllow', "APP_CODE='$cAPP_CODE' and DELETOR='' and md5(GCUST_CODE)='$xGROUP' and GCUST_ALLOW='$pKODE_ALLW'");
		if($aGROUP=SYS_FETCH($qQUERY)) {
			MSG_INFO('Kode tunjangan sudah ada');
		} else {
			RecCreate('TbCustGrpAllow', ['REC_ID', 'GCUST_CODE', 'GCUST_ALLOW', 'APP_CODE', 'ENTRY', 'DATE_ENTRY'], 
				[NowMSecs(), $cGROUP, $pKODE_ALLW, $cAPP_CODE, $_SESSION['gUSERCODE'], date("Y-m-d H:i:s")]);
	//		header('location:tb_group_customer.php');
		}
		echo "<script> window.history.back();	</script>";
	break;
	case "tambah":
		$pKODE_GRUP=$_POST['KODE_GROUP'];
		if($pKODE_GRUP=='') {
			MSG_INFO(S_MSG('PN19','Kode kelompok customer tidak boleh koson'));
			return;
		}
		$qQUERY=OpenTable('TbCustGroup', "APP_CODE='$cAPP_CODE' and DELETOR='' and KODE_GRP='$pKODE_GRUP'");
		if(SYS_ROWS($qQUERY)==0){
			RecCreate('TbCustGroup', ['KODE_GRP', 'NAMA_GRP', 'APP_CODE', 'ENTRY', 'DATE_ENTRY'], [$pKODE_GRUP, ENCODE($_POST['NAMA_GRUP']), $cAPP_CODE, $_SESSION['gUSERCODE'], date("Y-m-d H:i:s")]);
			header('location:tb_group_customer.php');
		} else {
			MSG_INFO(S_MSG('H362','Kode kelompok customer sudah ada'));
			return;
		}
	break;

	case md5('SA_VE'):
		$KODE_CRUD=$_GET['_g'];
		RecUpdate('TbCustGroup', ['NAMA_GRP', 'UP_DATE', 'UPD_DATE'], [ENCODE($_POST['NM_GRUP']), $cUSERCODE, date("Y-m-d H:i:s")], "APP_CODE='$cAPP_CODE' and md5(KODE_GRP)='".$KODE_CRUD."'");
		header('location:tb_group_customer.php');
	break;

	case md5('DEL_GC'):
		$KODE_CRUD=$_GET['_id'];
		RecUpdate('TbCustGroup', ['DELETOR', 'DEL_DATE'], [$cUSERCODE, date("Y-m-d H:i:s")], "APP_CODE='$cAPP_CODE' and md5(KODE_GRP)='".$KODE_CRUD."'");
		header('location:tb_group_customer.php');
	break;
}
?>

