<?php
//	tb_user_scope.php
if (!isset($_SESSION['KD_USER'])) session_start();
$cUSR_SC = $_SESSION['KD_USER'];

    echo '<div class="tab-pane fade in" id="User_Scope">';
        TABLE('myTable', '', 0, 'NoPage');
            THEAD([S_MSG('RS04','Customer'), S_MSG('PF16','Lokasi'), S_MSG('PF13','Jabatan')], '', [], '*', [4,4,4]);
            echo '<tbody>';
                $qSCOPE=OpenTable('User_Scope', "A.APP_CODE='$cAPP_CODE' and A.DELETOR='' and A.USER_CODE='$cUSR_SC'");
                while($aSCOPE=SYS_FETCH($qSCOPE)) {
                    $cHREFF="<a href='tb_user.php?_a=".md5('updateScope')."&_r=".$aSCOPE['REC_NO']."'>";
                    TDETAIL([$aSCOPE['CUST_NAME'], $aSCOPE['LOKS_NAME'], $aSCOPE['JOB_NAME']], [], '*', [$cHREFF, '', '']);
                }
                echo '<tr>';
                    echo '<td>';
                        SELECT([12,12,12,12], 'NEW_CUSTOMER', '', '', 'select2');
                            echo '<option></<option>';
                            $qCUSTOMER=OpenTable('TbCustomer', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'CUST_NAME');
                            while($aCUSTOMER=SYS_FETCH($qCUSTOMER)){
                                echo "<option value='$aCUSTOMER[CUST_CODE]'  >".DECODE($aCUSTOMER['CUST_NAME'])."</option>";
                            }
                        echo '</select>';
                    echo '</td>';
                    echo '<td>';
                        SELECT([12,12,12,12], 'NEW_LOCATION', '', '', 'select2');
                            echo '<option></<option>';
                            $qLOKASI=OpenTable('PrsLocation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'LOKS_NAME');
                            while($aLOKASI=SYS_FETCH($qLOKASI)){
                                    echo "<option value='$aLOKASI[LOKS_CODE]'  >".DECODE($aLOKASI['LOKS_NAME'])."</option>";
                            }
                        echo '</select>';
                    echo '</td>';
                    echo '<td>';
                        SELECT([12,12,12,12], 'NEW_JOB', '', '', 'select2');
                            echo '<option></<option>';
                            $qOCCUP=OpenTable('TbOccupation', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'JOB_NAME');
                            while($aJOB=SYS_FETCH($qOCCUP)){
                                echo "<option value='$aJOB[JOB_CODE]'  >".DECODE($aJOB['JOB_NAME'])."</option>";
                            }
                        echo '</select>';
                    echo '</td>';
                echo '</tr>';
            echo '</tbody>';
        eTABLE();
    eTDIV();
?>
