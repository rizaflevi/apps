<?php
//	rep_sales.php //

include "sysfunction.php";
if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
$cFILTER_CODE 	= $_SESSION['data_FILTER_CODE'];
$cUSERCODE 		= $_SESSION['gUSERCODE'];
$cHELP_FILE     = 'Doc/Laporan - Penjualan.pdf';
$cHEADER 		= S_MSG('RB22','Laporan Penjualan');
$ADD_LOG		= APP_LOG_ADD($cHEADER);

$cNO_INVOICE 	= S_MSG('RS01','Faktur');
$cTANGGAL 		= S_MSG('RS02','Tanggal');
$cNIL_TRN		= S_MSG('RS05','Penjualan');
$cJUMLAH	 	= S_MSG('RS08','Jumlah');
$cTGL1			= S_MSG('RS02','Tanggal');
$cTGL2			= S_MSG('RS14','s/d');

$dPERIOD1=date("d/m/Y");
$dPERIOD2=date("d/m/Y");
$cACTION 		= (isset($_GET['_a']) ? $_GET['_a'] : '');
$REP_TYPE 		= (isset($_GET['_t']) ? $_GET['_t'] : '1');

if (isset($_GET['DATE1'])) $dPERIOD1=$_GET['DATE1'];
if (isset($_GET['DATE2'])) $dPERIOD2=$_GET['DATE2'];
$GROUP_DATA = 'A.TGL_JUAL';
$cFORM_TGL = 'd-M';
switch($REP_TYPE){
    case '2':
        $GROUP_DATA = 'left(A.TGL_JUAL,7)';
        $cFORM_TGL = 'M-Y';
        break;
    case '3':
        $GROUP_DATA = 'left(A.TGL_JUAL,4)';
        $cFORM_TGL = 'Y';
        break;
}
if (IS_LOCALHOST())		UPDATE_DATE();

$q_SALESMAN=OpenTable('TbSalesman');
$multi_SLSMAN = SYS_ROWS($q_SALESMAN)>0;

$cCOND = "A.APP_CODE='$cFILTER_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete ) and A.TGL_JUAL>='".DMY_YMD($dPERIOD1)."' and A.TGL_JUAL<='".DMY_YMD($dPERIOD2)."'";

$qQUERY=OpenTable('RepSalesDay', $cCOND, $GROUP_DATA, 'A.TGL_JUAL');
$lPRINT=TRUST($cUSERCODE, 'RP_SALES_DAY_PRINT');
$aACT = ($lPRINT ? ['<a href="rep_sales.php?_d1='.$dPERIOD1.'&_d2='.$dPERIOD2.'"><i class="fa fa-solid fa-print"></i>Print</a>'] : []);
DEF_WINDOW($cHEADER, 'collapse');
    TFORM($cHEADER, "?_t=".$REP_TYPE, $aACT, $cHELP_FILE, '*');
        LABEL([1,1,1,4], '700', $cTGL1, '', 'right');
        INP_DATE([2,2,3,8], '900', '', $dPERIOD1, '', '', '', '', '', "select_DATA(this.value, '$dPERIOD2', $REP_TYPE)");
        LABEL([1,1,1,4], '700', $cTGL2, '', 'right');
        INP_DATE([2,2,3,8], '900', '', $dPERIOD2, '', '', '', '', '', "select_DATA('$dPERIOD1', this.value, $REP_TYPE)");
        echo '<span class="col-lg-1 col-sm-1"></span>';
        RADIO('REPORT_TYPE', [1,2,3], [$REP_TYPE=='1', $REP_TYPE=='2', $REP_TYPE=='3'], ['Harian', 'Bulanan', 'Tahunan'], _cON_CHANGE: "select_DATA('$dPERIOD1', '$dPERIOD2', this.value)");
        TDIV();
            TABLE('example', '', 10, '*', '*', '*');
                THEAD([$cTANGGAL, $cNO_INVOICE, $cNIL_TRN], '', [0,1,1]);
                echo '<tbody>';
                    $nT_JUAL = 0;	$nT_INV = 0;
                    $tanggal=$jumlah = $penjualan = [];
                    while($aREC_JUAL1=SYS_FETCH($qQUERY)) {
                        $nJUMLAH = $aREC_JUAL1['SALES_DAY'];
                        $tanggal[] = date($cFORM_TGL, strtotime($aREC_JUAL1['TGL_JUAL']));
                        echo '<tr>';
                            $cICON = 'fa fa-money';
                            echo '<td class=""><div class="star"><i class="fa '.$cICON.' icon-xs icon-default"></i></div></td>';
                            echo '<td><span>'.date($cFORM_TGL, strtotime($aREC_JUAL1['TGL_JUAL'])).'</span></td>';
                            echo '<td align="right"><span>'.CVR($aREC_JUAL1['INVOICE']).'</span></td>';
                            echo '<td align="right"><span>'.CVR($aREC_JUAL1['SALES_DAY']).'</span></td>';
                            $nT_JUAL += $aREC_JUAL1['SALES_DAY'];
                            $nT_INV += $aREC_JUAL1['INVOICE'];
                            $jumlah[] = $aREC_JUAL1['INVOICE'];

                            $penjualan[] = $aREC_JUAL1['SALES_DAY'];
                        echo '</tr>';
                    }
                echo '</tbody>';
                TTOTAL(['Total', CVR($nT_INV), CVR($nT_JUAL)], [0,1,1]);
            eTABLE();
        eTDIV();
?>
    <canvas id="grafikFaktur" width="600" height="400"></canvas>

    <script>
    const ctx = document.getElementById('grafikFaktur').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($tanggal); ?>,
            datasets: [
                {
                    label: 'Total Penjualan (Rp)',
                    data: <?php echo json_encode($penjualan); ?>,
                    backgroundColor: '#003366'
                }

            ]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
    </script>
<?php
		eTFORM('*');
	END_WINDOW();
	SYS_DB_CLOSE($DB2);	
?>

<script>

function select_DATA(_TGL_1, _TGL_2, _RTYPE) {
	window.location.assign("rep_sales.php?DATE1="+_TGL_1+"&DATE2="+_TGL_2+"&_t="+_RTYPE);
}

</script>

