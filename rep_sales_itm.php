<?php
//	rep_sales_itm.php //

include "sysfunction.php";
if (!isset($_SESSION['data_FILTER_CODE'])) session_start();
$cFILTER_CODE 	= $_SESSION['data_FILTER_CODE'];
$cUSERCODE 		= $_SESSION['gUSERCODE'];
$cHELP_FILE     = 'Doc/Laporan - Penjualan.pdf';
$cHEADER 		= S_MSG('RB42','Laporan Penjualan per Item');
$ADD_LOG		= APP_LOG_ADD($cHEADER);

$cTANGGAL 		= S_MSG('RS02','Tanggal');
$cNIL_TRN		= S_MSG('RS05','Penjualan');
$cGROUP	     	= S_MSG('F060','Kelompok');
$cINV_NAME		= S_MSG('RS02','Tanggal');
$cTGL2			= S_MSG('RS14','s/d');

$dPERIOD1=date("d/m/Y");
$dPERIOD2=date("d/m/Y");
$cACTION 		= (isset($_GET['_a']) ? $_GET['_a'] : '');
$ITM_GROUP 		= (isset($_GET['_g']) ? $_GET['_g'] : '');

if (isset($_GET['_d1'])) $dPERIOD1=$_GET['_d1'];
if (isset($_GET['_d2'])) $dPERIOD2=$_GET['_d2'];

$cCOND = "A.APP_CODE='$cFILTER_CODE' and A.REC_ID not in ( select DEL_ID from logs_delete ) and B.TGL_JUAL>='".DMY_YMD($dPERIOD1)."' and B.TGL_JUAL<='".DMY_YMD($dPERIOD2)."'";
if ($ITM_GROUP) $cCOND.=" and C.GROUP_INV='$ITM_GROUP'";

$qQUERY=OpenTable('TrSalesDtlHdr', $cCOND, 'A.KODE_BRG', 'B.TGL_JUAL');
$lPRINT=TRUST($cUSERCODE, 'RP_SALES_ITM_PRINT');
$aACT = ($lPRINT ? ['<a href="rep_sales_itm.php?_d1='.$dPERIOD1.'&_d2='.$dPERIOD2.'"><i class="fa fa-solid fa-print"></i>Print</a>'] : []);
DEF_WINDOW($cHEADER, 'collapse');
    TFORM($cHEADER, "?_g=".$ITM_GROUP, $aACT, $cHELP_FILE, '*');
        LABEL([1,1,1,4], '700', $cTANGGAL, '', 'right');
        INP_DATE([2,2,3,6], '900', '', $dPERIOD1, '', '', '', '', '', "select_DATA(this.value, '$dPERIOD2', '$ITM_GROUP')");
        LABEL([1,1,1,4], '700', $cTGL2, '', 'right');
        INP_DATE([2,2,3,6], '900', "", $dPERIOD2, '', '', '', '', '', "select_DATA('$dPERIOD1', this.value, '$ITM_GROUP')");
        echo '<span class="col-lg-1 col-sm-1"></span>';
        LABEL([1,1,2,6], '700', $cGROUP);
        TDIV(3,6,6,12);
            SELECT([8,8,8,6], 'GET_GROUP', "select_DATA('$dPERIOD1', '$dPERIOD2', this.value)", '');
                echo "<option value=''></option>";
                $qGROUP=OpenTable('TbInvGroup', '', '', 'NAMA_GRP');
                while($aGROUP=SYS_FETCH($qGROUP)){
                //     echo "<option value='$aGROUP[KODE_GRP]'  >$aGROUP[NAMA_GRP]</option>";
                // }
					if($aGROUP['KODE_GRP']==$ITM_GROUP){
						echo "<option value='".$aGROUP['KODE_GRP']. "' selected='$ITM_GROUP' >$aGROUP[NAMA_GRP]</option>";
					} else {
						echo "<option value='".$aGROUP['KODE_GRP']. "'  >$aGROUP[NAMA_GRP]</option>";
					}
                }
            echo '</select>';
        eTDIV();
        TDIV();
            TABLE('example');
                THEAD([S_MSG('H622','Nama Menu Item'), 'Qty', 'Invoice', $cNIL_TRN], '', [0,1,1,1]);
                echo '<tbody>';
                    $nT_JUAL = 0;	$nT_INV = 0;	
                    $ITEM=$jumlah = $penjualan = [];
                    while($aREC_JUAL1=SYS_FETCH($qQUERY)) {
                        $nJUMLAH = $aREC_JUAL1['SALES_AMT'];
                        echo '<tr>';
                            $cICON = 'fa fa-money';
                            echo '<td class=""><div class="star"><i class="fa '.$cICON.' icon-xs icon-default"></i></div></td>';
                            echo '<td>'.$aREC_JUAL1['NAMA_BRG'].'</td>';
                            echo '<td align="right">'.CVR($aREC_JUAL1['QTY']).'</td>';
                            echo '<td align="right">'.CVR($aREC_JUAL1['INVOICE']).'</td>';
                            echo '<td align="right">'.CVR($aREC_JUAL1['SALES_AMT']).'</td>';
                            $nT_JUAL += $aREC_JUAL1['SALES_AMT'];
                            $nT_INV += $aREC_JUAL1['INVOICE'];
                            $jumlah[] = $aREC_JUAL1['INVOICE'];

                            $ITEM[] = $aREC_JUAL1['KODE_BRG'];
                            $penjualan[] = $aREC_JUAL1['SALES_AMT'];
                        echo '</tr>';
                    }
                echo '</tbody>';
                TTOTAL(['Total', '', CVR($nT_INV), CVR($nT_JUAL)], [0,0,1,1]);
            eTABLE();
        eTDIV();
?>
    <canvas id="grafikFaktur" width="600" height="400"></canvas>

    <script>
    const ctx = document.getElementById('grafikFaktur').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($ITEM); ?>,
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

function select_DATA(_TGL_1, _TGL_2, _GROUP) {
	window.location.assign("rep_sales_itm.php?_d1="+_TGL_1+"&_d2="+_TGL_2+"&_g="+_GROUP);
}

</script>

