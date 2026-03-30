<?php
	$qQUERY=OpenTable('PERSON1', "A.APP_CODE='$cAPP_CODE' and A.DELETOR='' and A.PRSON_SLRY<2 and 
		A.PRSON_CODE not in ( select PRSON_CODE from prs_resign where APP_CODE='$cAPP_CODE' and DELETOR='')".UserScope($cUSERCODE), '', 
		'A.DATE_ENTRY desc');
	DEF_WINDOW($cHEADER);
		$aACT = ($can_CREATE==1 ? ['<a href="?_a='. md5('CR34T3_PEG'). '"><i class="fa fa-plus-square"></i>'. S_MSG('KA11','Add new').'</a>'] : []);
		TFORM($cHEADER, '', $aACT, $cHELP_FILE);
			TDIV();
				TABLE('example');
					$aHEAD = [$cKODE, $cNAMA, $cALAMAT];
					if($ada_OUTSOURCING) {
						array_push($aHEAD, $cCUSTOMER);
			//						array_push($aHEAD, $cLOKASI);
					}
			//						array_push($aHEAD, S_MSG('PA43','Jabatan'));
					THEAD($aHEAD);
					echo '<tbody>';
						while($aREC_DISP=SYS_FETCH($qQUERY)) {
							$cICON = ($aREC_DISP['PRSON_GEND'] == 2 ? 'fa-female' : 'fa-male');
							TDETAIL([$aREC_DISP['MASTER_CODE'], DECODE($aREC_DISP['PRSON_NAME']), DECODE($aREC_DISP['PEOPLE_ADDRESS']), ($ada_OUTSOURCING ? $aREC_DISP['CUST_NAME'] : null)], [], $cICON, 
								["<a href='?_a=".md5('UP_DATE_PEG')."&_c=".md5($aREC_DISP['MASTER_CODE'])."'>", "<a href='?_a=".md5('UP_DATE_PEG')."&_c=".md5($aREC_DISP['MASTER_CODE'])."'>", '', '']);
						}
					echo '</tbody>';
				eTABLE();
			eTDIV();
		eTFORM('*');
	END_WINDOW()
?>
