<html>
<head><title>Koneksi Mesin Absensi</title> </head>
<body bgcolor="#caffcb">

<H3>Download Log Data</H3>

<?php
include "prs_fp_db_connect.php";
// $IP="192.168.1.202";		// 207530/206110
// $IP="36.69.107.131";		/* Kuningan lt. 2	*/ 206125
// $IP="36.78.167.249";		/* Thamcit lt. D	*/ rec double 2815

ini_set('max_execution_time', 0);

$Key="0";
$id="202";
$thn =  date("Y");
$bln =  date("n");

function Parse_Data($data,$p1,$p2){
	$data=" ".$data;
	$hasil="";
	$awal=strpos($data,$p1);
	if($awal!=""){
		$akhir=strpos(strstr($data,$p1),$p2);
		if($akhir!=""){
			$hasil=substr($data,$awal+strlen($p1),$akhir-strlen($p1));
		}
	}
	return $hasil;	
}

$cQUERY="SELECT * FROM branch where IP_FINGER!=''";
$FPBRANCH=mysql_query($cQUERY, $DB1);
// $IP = 'test';	105197, 104194
while($aFPBRANCH=mysql_fetch_array($FPBRANCH)) {
	echo "<H2>".$aFPBRANCH['BRCH_NAME']."</H2>"
	
	?>
	<table cellspacing="2" cellpadding="2" border="1">
	<tr align="center">
	    <td><B>UserID</B></td>
	    <td width="200"><B>Tanggal & Jam</B></td>
	    <td><B>Verifikasi</B></td>
	    <td><B>Status</B></td>
	    <td><B>Ip</B></td>
	</tr>

	<?php
	$IP=$aFPBRANCH['IP_FINGER']; 
	$Connect = fsockopen($IP, "80", $errno, $errstr, 1); 
	
	if($Connect){
		$soap_request="<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">".$Key."</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetAttLog>";
		$newLine="\r\n";
		fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
		fputs($Connect, "Content-Type: text/xml".$newLine);
		fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
		fputs($Connect, $soap_request.$newLine);
		$buffer="";
		while($Response=fgets($Connect, 1024)){
			$buffer=$buffer.$Response; 
		}

	//	include("parse.php");
		$buffer=Parse_Data($buffer,"<GetAttLogResponse>","</GetAttLogResponse>");
		$buffer=explode("\r\n",$buffer); 
		$nBUFFER = count($buffer);
		//var_dump($nBUFFER);
		$nINSERT = 0;
		for($a=0;$a<count($buffer);$a++){
			$data=Parse_Data($buffer[$a],"<Row>","</Row>");
			$PIN=Parse_Data($data,"<PIN>","</PIN>");
			$DateTime=Parse_Data($data,"<DateTime>","</DateTime>");
			$Verified=Parse_Data($data,"<Verified>","</Verified>");
			$Status=Parse_Data($data,"<Status>","</Status>");
			$CHECKTIME=Substr($DateTime, 11, 8);
			$CHECKDATE=Substr($DateTime, 0, 10);
			
			$LOGS_THN=Substr($DateTime, 0, 4);
			$LOGS_BLN=Substr($DateTime, 5, 2);
			if(empty($CHECKTIME)) {
			} else {
				$cIN_OUT="I";
				if(substr($CHECKTIME,0,2)>=13) {
					$cIN_OUT="O";
				}
				$cQ_DBL="SELECT *, COUNT(*) c FROM prs_fpio GROUP BY USERID, CHECKDATE, CHECKTIME, SENSORID, PRSON_CODE HAVING c > 1";
				$REC_DBL =mysql_query($cQ_DBL);
				$aREC_DOUBLE =mysql_fetch_array($REC_DBL);
				if(mysql_num_rows($REC_DBL)>0){
					$nREC_DOUBLE=$aREC_DOUBLE['FP_REC'];
					$NOW = date("Y-m-d H:i:s");
					$cQUERY="update prs_fpio set USERID='$PIN', CHECKDATE='$DateTime', CHECKTIME='$CHECKTIME', CHECKTYPE='$cIN_OUT', SENSORID='$Status', SN='$IP', PRSON_CODE='$PIN', UPD_DATE='$NOW' where FP_REC=". $nREC_DOUBLE;
				} else {
					$cQUERY="insert into prs_fpio set USERID='$PIN', CHECKDATE='$DateTime', CHECKTIME='$CHECKTIME', CHECKTYPE='$cIN_OUT', SENSORID='$Status', SN='$IP', PRSON_CODE='$PIN'";
				}
//				mysql_query($cQUERY) or die ('Error in query.' .mysql_error(). $cQUERY);
//				$nINSERT += 1;
				if (mysql_query($cQUERY)) { $nINSERT += 1; }
				else { die ('Error in query.' .mysql_error(). $cQUERY); }
//					$cQUERY="UPDATE rainbow set KEY_CONTEN='".$IP.":".$PIN.":".$DateTime.":".$CHECKTIME."' where KEY_FIELD='SCRN_SVR'";
//					mysql_query($cQUERY, $DB1);
			}
			?>
			<tr align="center">
				 <td><?php echo $PIN?></td>
				 <td><?php echo $DateTime?></td>
				 <td><?php echo $Verified?></td>
				 <td><?php echo $Status?></td>
				 <td><?php echo $IP?></td>
			</tr>
			<?php }?>
			</table>
		<?php 

		$nBUFFER = count($buffer)-2;
//		echo ">". $nINSERT . "  -  " . $nBUFFER;
/*  ---------- hapus logs ------------------------	
		if ($nINSERT == $nBUFFER) {
			$Connect = fsockopen($IP, "80", $errno, $errstr, 1);
			if($Connect){
				$soap_request="<ClearData><ArgComKey xsi:type=\"xsd:integer\">".$Key."</ArgComKey><Arg><Value xsi:type=\"xsd:integer\">3</Value></Arg></ClearData>";
				$newLine="\r\n";
				fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
				 fputs($Connect, "Content-Type: text/xml".$newLine);
				 fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
				 fputs($Connect, $soap_request.$newLine);
				$buffer="";
				while($Response=fgets($Connect, 1024)){
					$buffer=$buffer.$Response;
				}
			} else echo "Koneksi ke mesin absen Gagal";
			include("parse.php");	
			$buffer=Parse_Data($buffer,"<Information>","</Information>");
			echo "<B>Result:</B><BR>";
			echo $buffer;
		}
//			*/
// ---------- end of hapus logs -------------------- 207519/172591 rec */

	} else { echo "Koneksi Gagal";}
}
		

	$cQUERY="UPDATE rainbow set KEY_CONTEN=' ' where KEY_FIELD='SCRN_SVR'";
	mysql_query($cQUERY, $DB1);

?>

</body>
</html>
