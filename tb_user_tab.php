<?php
	if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
	$cAPP_CODE = $_SESSION['data_FILTER_CODE']; 
	$cUSERCODE = $_SESSION['gUSERCODE'];
	// $cUSER_CODE = strtoupper($_SESSION['gUSERCODE']);

	$view_PRS_SCOPE=TRUST($cUSERCODE, 'PREVILLEGE_SCOPE_VW');	
	$upd_PRS_SCOPE=TRUST($cUSERCODE, 'PREVILLEGE_SCOPE_UPD');
	$view_USR_DTL=TRUST($cUSERCODE, 'PREVILLEGE_6VW_DTL');	
	$upd_USR_DTL=TRUST($cUSERCODE, 'PREVILLEGE_7UPD_DTL');
	$view_PREVLLG=TRUST($cUSERCODE, 'PREVILLEGE_VIEW');	
	$upd_PREVLLGL=TRUST($cUSERCODE, 'PREVILLEGE_UPDATE');
	$view_FOTO=TRUST($cUSERCODE, 'PREVILLEGE_VFOTO');	
	$upd_FOTO=TRUST($cUSERCODE, 'PREVILLEGE_UFOTO');
	$qQUERY=OpenTable('UserDtl', "USER_CODE='$cUSER_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
	$REC_DT_USER=SYS_FETCH($qQUERY);
	$cFILE_FOTO_USER = S_PARA('FTP_USER_FOLDER', '/home/riza/www/images/admin/').$cUSER_CODE.'.jpg';
	// print_r2($cFILE_FOTO_USER);
	if(!file_exists($cFILE_FOTO_USER))	{
		$cFILE_FOTO_USER = "data/images/no.jpg";
	}
	echo '<br>';
		TDIV();
				$aTAB=$aICON=$aCAPTION=[];
				if($view_PREVLLG or $upd_PREVLLGL) {
					array_push($aTAB, 'Previllage');
					array_push($aICON, 'fa-user');
					array_push($aCAPTION, S_MSG('TU16','User Privileges'));
				}
				if($view_FOTO or $upd_FOTO) {
					array_push($aTAB, 'User_Foto');
					array_push($aICON, 'fa-user');
					array_push($aCAPTION, S_MSG('TU14','User Foto'));
				}
				if($view_USR_DTL or $upd_USR_DTL) {
					array_push($aTAB, 'User_Detail');
					array_push($aICON, 'fa-user');
					array_push($aCAPTION, S_MSG('F182','User Detail'));
				}
				if($view_PRS_SCOPE or $upd_PRS_SCOPE) {
					array_push($aTAB, 'User_Scope');
					array_push($aICON, 'fa-user');
					array_push($aCAPTION, S_MSG('TU37','User Scope'));
				}
				TAB($aTAB, $aICON, $aCAPTION);
		eTDIV();
	echo '<br>';
		?>

		<div class="tab-content primary">
			<div class="tab-pane fade in active" id="Previllage">
				<div class="panel-group transparent" id="accordion-3" role="tablist" aria-multiselectable="true">
					<?php
						$qMAIN_MENU=OpenTable('MainMenu', "A.JOB_CODE>'' and A.APP_CODE='$cAPP_CODE' and upper(B.USER_CODE) = '$cUSERCODE' and B.TRUSTEE_CODE is not null and A.order>0", "A.parent", "`order`");
						$HeadIndex=1;
						while($aMAIN_MENU=SYS_FETCH($qMAIN_MENU)){
					?>
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="heading<?php echo $HeadIndex?>">
							<h4 class="panel-title">
								<a class="collapsed" data-toggle="collapse" data-parent="#accordion-3" href="#collapseOne-<?php echo $HeadIndex?>" aria-expanded="false" aria-controls="collapseOne-3">
									<i class='fa fa-check'></i> <?php echo $aMAIN_MENU['parent'] ?>
								</a>
							</h4>
						</div>
						<div id="collapseOne-<?php echo $HeadIndex?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading<?php echo $HeadIndex?>">
							<div class="panel-body">

								<div class="col-lg-12 col-md-12 col-sm-12">
									<div class="wid-all-tasks">
										<ul class="list-unstyled">
										<?php
											TDIV();
												$qSUB_MENU=OpenTable('SubMenu', "A.APP_CODE='$cAPP_CODE' and A.parent='$aMAIN_MENU[parent]' and `order`>0 and sort>0", "A.JOB_CODE", "`order`, `sort`");
												$IDX=1;
												while($aSUB_MENU=SYS_FETCH($qSUB_MENU)){
													$c_USER = $aSUB_MENU['USER_CODE'];
													$q_TRUS2=OpenTable('SysPrevMenu', "A.APP_CODE='$cAPP_CODE' and A.USER_CODE='$c_USER' and A.JOB_CODE='".$aSUB_MENU['JOB_CODE']. "' and A.DELETOR=''", '', 'A.TRUSTEE_CODE');
													while($aTRUSTEE_CODE=SYS_FETCH($q_TRUS2)) {
														$cCHECK = '';
														$q_TRUSTGT=OpenTable('SysPrevMenu', "A.APP_CODE='$cAPP_CODE' and A.USER_CODE='$REC_TB_USER[USER_CODE]' and A.JOB_CODE='".$aSUB_MENU['JOB_CODE']. "' and A.TRUSTEE_CODE='".$aTRUSTEE_CODE['TRUSTEE_CODE']."' and A.DELETOR=''");
														if(SYS_ROWS($q_TRUSTGT)>0)	$cCHECK = 'checked';
														
														echo '<li class="">
															<input name="'.$aTRUSTEE_CODE['TRUSTEE_CODE'].'" type="checkbox" class="icheck-minimal-white todo-task col-lg-1"'.$cCHECK.'>';
															echo '<label style="color:#333333; min-width:500px;" class="col-lg-5">'. $aSUB_MENU['name'] .' - '.$aTRUSTEE_CODE['SYS_MS'] .'</label>';
															echo '<div class="clearfix"></div>';
														echo '</li>';
														$IDX++;
													}
												}
											eTDIV();
										?>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php	$HeadIndex++;	}	?>
				</div>
			</div>
			<div class="tab-pane fade in" id="User_Foto">
				<label class="form-label">User Image</label>
				<img class="img-responsive" src="<?php echo $cFILE_FOTO_USER?>" alt="" style="max-width:220px;">
				<input name="FOTO_ADMIN" type="file" class="form-control">
			</div>
<?php
			echo '<div class="tab-pane fade in" id="User_Detail">';
				LABEL([3,3,3,6], '700', $cAL_USR);
				INPUT('text', [7,7,7,6], '900', 'EDIT_USER_ADDR1', ($REC_DT_USER ? $REC_DT_USER['USER_ADDR'] : ''), '', '', '', 0, '', 'fix', $cTTIP_ALAMAT);
				LABEL([3,3,3,6], '700', $cTELPON);
				INPUT('text', [7,7,7,6], '900', 'EDIT_USER_PHON', ($REC_DT_USER ? $REC_DT_USER['USER_PHON'] : ''), '', '', '', 0, '', 'fix');
				LABEL([3,3,3,6], '700', $cEMAIL);
				INPUT('text', [7,7,7,6], '900', 'EDIT_USER_EMAIL', ($REC_DT_USER ? $REC_DT_USER['USER_EMAIL'] : ''), '', '', '', 0, '', 'fix');
			eTDIV();
			if ($view_PRS_SCOPE==1 || $upd_PRS_SCOPE==1) {
				include "tb_user_scope.php";
			}
		eTDIV();
?>
