<?php
//	prs_app.php //
//  TODO : upload foto, tmk

function UpLoad_File($_dir, $_file) {
    if($_SERVER['REQUEST_METHOD']=='POST' && isset($_FILES['image'])) {
        $target_dir = $_dir; // direktori tujuan untuk menyimpan gambar
        $target_file = $_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
        // periksa apakah file gambar benar-benar gambar atau bukan
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File bukan gambar.";
            $uploadOk = 0;
        }
    
        // periksa ukuran file
        if ($_FILES["image"]["size"] > 500000) {
            echo "Maaf, file terlalu besar.";
            $uploadOk = 0;
        }
    
        // Izinkan hanya format gambar tertentu
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            echo "Maaf, hanya format JPG, JPEG dan PNG yang bisa diteima.";
            $uploadOk = 0;
        }
    
        // cek apakah terdapat kesalahan
        if ($uploadOk == 0) {
            echo "Maaf, file gagal diunggah.";
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) echo "File ". htmlspecialchars( basename( $_FILES["image"]["name"])). " telah diunggah.";
            else echo "Maaf, terjadi kesalahan saat mengunggah file.";
        }
    }
}

$_SESSION['gUSERCODE'] 	= 'self';
$_SESSION['gSYS_PARA'] 	= 'JNS_PRSHN';
$_SESSION['sLANG']		= '1';
$_SESSION['gSYS_NAME']     = 'Rainbow Sys';
$_SESSION['gSCR_HEADER']   = 'DASHBOARD';

include_once "sys_function.php";
include_once "sys_connect.php";

$cAPP_CODE='YAZA';
if (isset($_GET['_c'])) $cAPP_CODE=$_GET['_c'];
$_SESSION['data_FILTER_CODE'] = $cAPP_CODE;
$cUSERCODE = $_SESSION['gUSERCODE'];

$cHEADER 		= 'Form Lamaran v2';

$aGOL_DAR = array(1=> 'A ', 'B ', 'AB', 'O');
$aSTATUS  = array(1=> S_MSG('PB58','Lajang'), S_MSG('PB59','Kawin'), S_MSG('PB60','Cerai'));

$cNAMA='Nama lengkap ( sesuai KTP) *';
$cGENDER		= 'Gender *';
$cALAMAT 		= 'Alamat KTP *';
$cKOTA			= S_MSG('NL54','Kota');
$cERTE			= S_MSG('PA36','RT');
$cERWE			= S_MSG('PA37','RW');
$cKELURAHAN		= S_MSG('PA38','Kelurahan');
$cKECAMATAN		= S_MSG('PA39','Kec');
$cPROPINSI		= S_MSG('CB81','Propinsi *');
$cKODE_POS		= S_MSG('H650','Kode Pos');
$cNO_TELP		= S_MSG('PA33','No. Telpon');
$cHOMEPHN		= S_MSG('F006','Nomor Telpon');
$cNO_HAPE		= S_MSG('PA34','Nomor HP');
$cTMP_LAHIR		= S_MSG('PA05','Tempat Lahir');
$cTGL_LAHIR		= S_MSG('PA06','Tanggal Lahir');
$cNO_KTP		= S_MSG('PA40','No. KTP');
$cLBL_PRIA		= S_MSG('PD12','Pria');
$cLBL_WANITA	= S_MSG('PD13','Wanita');
$cEMAIL_ADR		= S_MSG('F105','Email Address');
$cNO_REK		= S_MSG('PA07','No. Rekening');
$cNAMA_BANK		= S_MSG('PA08','Nama Bank');

$cNPWP		    = S_MSG('PG83','No. NPWP');
$cBPJS_TK		= S_MSG('PG84','No. BPJS TK');
$cBPJS_KES		= S_MSG('PG85','No. BPJS KES');

$cAGAMA			= S_MSG('PB50','Agama');
$cSTATUS		= S_MSG('PB57','Status');
$cJML_ANAK		= S_MSG('PB61','Jml. Anak');
$cPASANGAN		= S_MSG('PB62','Nama Pasangan');
$cPERUSAHAAN	= S_MSG('PA50','Perusahaan Tempat Bekerja');
$cDEPARTEMEN	= S_MSG('PA49','Departemen / Bagian');
$cJABATAN		= S_MSG('PA43','Jabatan');
$cALMT_KTR		= S_MSG('PA52','Alamat Kantor');

$cNM_PENDIDIKAN	= S_MSG('PA95','Nama Pendidikan');
$cJRSN_PNDDKKAN	= S_MSG('PA9A','Jurusan');
$cT1_PENDIDIKAN	= S_MSG('PA96','Tahun masuk');
$cT2_PENDIDIKAN	= S_MSG('PA97','Tahun keluar');
$cKT_PENDIDIKAN	= S_MSG('PA98','Keterangan');
$cNO_KKELUARGA 	= S_MSG('PG03','Nomor kartu keluarga');
$cNM_KKELUARGA 	= S_MSG('PG05','Nama Kepala Kel');
$cFULL_NAME 	= S_MSG('PG11','Nama Lengkap');
$cNIK_NIKS		= S_MSG('PG12','NIK/NIKS');
$cPENDIDIKAN	= S_MSG('PA91','Pendidikan');
$cPEKERJAAN		= S_MSG('PA41','Pekerjaan');
$cSTATUS		= S_MSG('PB57','Status');
$cKWARGA		= S_MSG('PG16','Kewarganegaraan');
$cSTA_TUS		= 'Status dalam keluarga';
$cPASSPORT		= S_MSG('PA8B','No. Passport');
$cKITAS			= S_MSG('PA8C','No.KITAS/KITAP');
$cAYAH			= S_MSG('PA8D','Ayah');
$cIBU			= S_MSG('PA8E','Ibu');
$cPENDDK		= S_MSG('PA94','Pendidian');

$cNAMA_SKILL	= S_MSG('PG53','Nama Keahlian');
$cKET_SKILL		= S_MSG('PA98','Keterangan');
$cTHN_SKILL		= S_MSG('PG67','Tahun');
$cSERT_SKILL	= S_MSG('PG68','No. Sertifikat');
$cREG_SKILL		= S_MSG('PG69','No. Registrasi');
$cNOTE_SKILL	= S_MSG('F019','Catatan');

$cFILE_FOTO = '';


$cACTION='';
if (isset($_GET['_a'])) $cACTION=$_GET['_a'];
$nRec_id = date_create()->format('Uv');
$cPERSON_CODE = (string)$nRec_id;

switch($cACTION){
	default:
        // DEF_WINDOW($cHEADER, '', '', 'fe_topbar.php', '*');
        // TDIV();
?>
	<!DOCTYPE html>
	<html>
    <head>
	    <!-- <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta charset="utf-8" /> -->
        <title>Rainbow Sys : <?php echo $cHEADER?></title>
        <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <meta http-equiv='cache-control' content='no-cache'>
        <meta http-equiv='expires' content='0'>
        <meta http-equiv='pragma' content='no-cache'> -->

        <!-- <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon"/>    <?php /* <!-- Favicon -->	*/ ?>
        <link rel="apple-touch-icon-precomposed" href="assets/images/apple-touch-icon-57-precomposed.png">	<?php /* <!-- For iPhone --> */ ?>
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/images/apple-touch-icon-114-precomposed.png">    <?php /* <!-- For iPhone 4 Retina display -->	*/ ?>
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/apple-touch-icon-72-precomposed.png">    <?php /* <!-- For iPad -->	*/ ?>
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/apple-touch-icon-144-precomposed.png">    <?php /* For iPad Retina display	*/ ?> -->

        <!-- CORE CSS FRAMEWORK -  -->
        <!-- <link href="assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen"/> -->
        <!-- <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/> -->
        <!-- <link href="assets/plugins/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/> -->
        <!-- <link href="assets/fonts/font-awesome/css/fontawesome.css" rel="stylesheet" type="text/css"/> -->

        <!-- Add the v6 core styles and then select the individual styles you need, like Solid and Brands -->
        <!-- <link href="assets/fonts/font-awesome/css/fontawesome.css" rel="stylesheet" />
        <link href="assets/fonts/font-awesome/css/brands.css" rel="stylesheet" />
        <link href="assets/fonts/font-awesome/css/solid.css" rel="stylesheet" /> -->

        <!-- support v4 icon references/syntax -->
        <!-- <link href="assets/fonts/font-awesome/css/v4-font-face.css" rel="stylesheet" /> -->

        <!-- <link href="assets/css/animate.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/plugins/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" type="text/css"/> -->

        <!-- <link href="assets/plugins/datatables/css/jquery.dataTables.css" rel="stylesheet" type="text/css" media="screen"/>
        <link href="assets/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet" type="text/css" media="screen"/>
        <link href="assets/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet" type="text/css" media="screen"/>
        <link href="assets/plugins/datatables/extensions/Responsive/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet" type="text/css" media="screen"/> -->
        <!-- <link href="assets/plugins/tabulator/css/tabulator_bootstrap3.css" rel="stylesheet"> -->
        <link href="assets/plugins/select2/select2.css" rel="stylesheet">
        <link href="assets/css/style.css" rel="stylesheet" type="text/css"/>

        <!--
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js" type="text/javascript"></script>
        -->
        <script src="assets/js/jquery-3.7.0.min.js" type="text/javascript"></script>
    </head>

    <?php
// TOPBAR
	$cFE_PERSON='';
	$cWIDTH_LOGO='375px';
	if (isset($_GET['_fe'])) {
		$cFE_PERSON=$_GET['_fe'];
		$cWIDTH_LOGO='100%';
	}
    $cAPP_CODE 		= $_SESSION['data_FILTER_CODE'];
	$cFILE_USER = 'data/images_user/'. $cFE_PERSON.'.jpg';
	if(file_exists($cFILE_USER)==0)	$cFILE_USER = "data/images/LOGO_CIRCLE_".$cAPP_CODE.".jpg";

	$COMPANY = 'Rainbow1';
	$LOGO = 'data/images/'.$COMPANY.'.jpg';
	if ($cAPP_CODE!='') {
		$LOGO = 'data/images/'.$cAPP_CODE.'_MOBILE.jpg';
	}
?>

	<!-- <div class="pace-activity"></div>
        <div class='page-topbar'>
            <div class='logo-area'>

            </div>
 		</div>
	</div> -->

	<div class="page-topbar">
		<img class="col-xs-12" src="<?php echo $LOGO ?>" style="height: 60px;">
	</div>
    
    <style>    td,th {overflow:hidden;white-space:nowrap}    </style>
		<body class=" ">
			<div class="page-container row-fluid">
                <section class="wrapper main-wrapper" style=''>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<section class="box ">
							<div class="pull-right hidden-xs">	</div>
                            <h2 class="title pull-left"><?php echo $cHEADER?></h2>
                            <div class="clearfix"></div>
							<div class="content-body">
								<div class="row" style="margin-left: 0px; margin-right: 0px">
									<form class="form-horizontal" action ="?_a=addNew&_c=<?php echo $cAPP_CODE?>" method="post">
                                        <?php include_once 'wilayah.php'; ?>
                                        <label class="col-lg-3 col-sm-4 form-label-700" for="field-2"><?php echo $cNAMA?></label>
                                        <input type="text" required class="col-sm-6 col-xs-12 form-label-900" name='ADD_PRSON_NAME' id="field-2">
                                        <div class="clearfix">aaaaaaaaa</div> aaaaaaaa

                                        <label class="col-lg-3 col-sm-4 col-xs-6 form-label-700" for="field-2">Nama Panggilan</label>
                                        <input type="text" required class="col-lg-2 col-sm-4 col-xs-6 form-label-900" name='ADD_NICK_NAME'>
                                        <div class="clearfix"></div><br>

                                        <label class="col-lg-3 ccol-sm-4 col-xs-6 form-label-700" for="field-3"><?php echo $cGENDER?></label>
                                        <input type="radio" required name="ADD_PRSON_GEND" value=1 "checked"> <?php echo $cLBL_PRIA?>
                                        <input type="radio" required name="ADD_PRSON_GEND" value=2> <?php echo $cLBL_WANITA?>
                                        <div class="clearfix"></div><br>

                                        <label class="col-sm-3 col-xs-6 form-label-700" for="field-4"><?php echo $cALAMAT?></label>
                                        <div class="controls">
                                                <textarea name='ADD_ADDRESS' class="col-sm-3 col-xs-6 form-label-500 form-control autogrow" id="field-7" placeholder="Alamat ........"></textarea>
                                        </div><br>
                                        <div class="clearfix"></div>

                                        <label class="col-sm-3 col-xs-3 form-label-700" for="field-5"><?php echo $cERTE?></label>
                                        <input type="number" class="col-sm-2 col-xs-3 form-label-900" name='ADD_RT' data-mask="999">
                                        <span class="desc"></span>																	

                                        <label class="col-sm-1 col-xs-3 form-label-700" style="text-align:right;"><?php echo $cERWE?></label>
                                        <input type="number" class="col-sm-2 col-xs-3 form-label-900" name='ADD_RW' data-mask="999">
                                        <span class="desc"></span>																	

                                        <label class="col-lg-1 col-sm-2 col-xs-3 form-label-700" ><?php echo $cKODE_POS?></label>
                                        <input type="number" class="col-sm-2 col-xs-3 form-label-900" name='ADD_PRS_ZIP' data-mask="99999">
                                        <div class="clearfix"></div><br>

                                        
                                        <!-- <div class="form-group"> -->
                                            <!-- <label class="col-lg-3 col-sm-3 form-label-700" for="prov_s2">< ?php echo $cPROPINSI?></label>
                                            <div class="col-sm-9 col-md-9">
                                                <select name="ADD_PROPINSI" id="prov_s2">
                                                    < ?php
                                                    $qPROV=OpenTable('TbProvince', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'nama');
                                                    while($aPROV=SYS_FETCH($qPROV)) print "<option value='$aPROV[id_prov]'  >$aPROV[nama]</option>";
                                                    ?>
                                                </select>
                                            </div> -->
                                            
                                        <!-- </div> -->

                                        <!-- <div class="form-group">
                                            <label class="col-lg-3 col-sm-3 col-xs-3 form-label-700" for="kab_s2">< ?php echo $cKOTA?></label>
                                            <div class="col-sm-9"><input type="hidden" id="kab_s2" name="ADD_KAB_KOTA"></div>
                                        </div> -->

                                        <!-- <div class="form-group">
                                            <label class="col-lg-3 col-sm-3 col-xs-3 form-label-700" for="kec_s2">< ?php echo $cKECAMATAN?></label>
                                            <div class="col-sm-9"><input type="hidden" id="kec_s2" name="ADD_PRSN_KEC"></div>
                                        </div> -->

                                        <!-- <div class="form-group">
                                            <label class="col-lg-3 col-sm-3 col-xs-3 form-label-700" for="kel_s2">< ?php echo $cKELURAHAN?></label>
                                            <div class="col-sm-9"><input type="hidden" id="kel_s2" name="ADD_PRSN_KEL"></div>
                                        </div><br> -->

                                        <label class="col-lg-3 col-sm-3 col-xs-12 form-label-700" for="field-4">Alamat Domisili ( jika beda dgn KTP )</label>
                                        <div class="controls">
                                                <textarea name='ADD_DOMISILI' class="col-sm-3 col-xs-12 form-label-500 form-control autogrow" id="field-7" placeholder="Diisi hanya kalau beda dengan alamat KTP......."></textarea>
                                        </div>
                                        <div class="clearfix"></div><br>

                                        <label class="col-lg-3 col-sm-3 col-xs-5 form-label-700" for="field-25"><?php echo $cNO_KTP?></label>
                                        <input type="number" class="col-lg-2 col-sm-4 col-xs-7 form-label-900" name='ADD_PRS_KTP' data-mask="9999999999999999">
                                        <div class="col-lg-1 value=''"></div>

                                        <label class="col-lg-2 col-sm-3 col-xs-5 form-label-700" for="field-31"><?php echo $cNO_TELP?></label>
                                        <input type="number" class="col-lg-2 col-sm-3 col-xs-7 form-label-900" name='ADD_HAND_PHN' id="field-31">
                                        <div class="clearfix"></div>

                                        <label class="col-lg-3 col-sm-3 col-xs-5 form-label-700" for="field-31">No. Telegram</label>
                                        <input type="number" class="col-lg-2 col-sm-3 col-xs-7 form-label-900" name='ADD_TELEGRAM' id="field-31">
                                        <div class="col-lg-1 value=''"></div>

                                        <label class="col-lg-3 col-sm-3 col-xs-5 form-label-700" for="field-32"><?php echo $cHOMEPHN?></label>
                                        <input type="number" class="col-lg-2 col-sm-3 col-xs-7 form-label-900" name='ADD_HOMEPHN' id="field-31">
                                        <div class="clearfix"></div>

                                        <label class="col-lg-3 col-sm-3 col-xs-5 form-label-700" for="field-7"><?php echo $cTMP_LAHIR?></label>
                                        <input type="text" required class="col-sm-3 col-xs-7 form-label-900" name='ADD_BIRTH_PLC' id="field-3">

                                        <label class="col-lg-2 col-sm-3 col-xs-5 form-label-700" for="field-8"><?php echo $cTGL_LAHIR?></label>
                                        <input type="date" name='ADD_BIRTH_DATE' class="col-sm-3 col-xs-7 form-label-900" data-mask="date" value=<?php echo date("d-m-Y")?>>

                                        <label class="col-lg-3 col-sm-3 col-xs-5 form-label-700" for="field-31"><?php echo $cEMAIL_ADR?></label>
                                        <input type="email" class="col-lg-3 col-sm-9 col-xs-7 form-label-900" name='ADD_PRS_EMAIL' id="field-31">
                                        <label class="col-lg-2 col-sm-3 col-xs-5 form-label-700" for="field-31"><?php echo $cNPWP?></label>
                                        <input type="text" required class="col-lg-3 col-sm-9 col-xs-7 form-label-900" name='ADD_PRS_NPWP' id="field-31">
                                        <div class="clearfix"></div>
                                                        
                                        <label class="col-sm-3 col-xs-5 form-label-700" for="field-15"><?php echo $cNO_REK?></label>
                                        <input type="text" required class="col-sm-3 col-xs-7 form-label-900" name='ADD_PRSON_ACCN' id="field-6">
                                        <label class="col-lg-2 col-sm-2 col-xs-5 form-label-700" for="field-16"><?php echo $cNAMA_BANK?></label>
                                        <input type="text" required class="col-lg-3 col-sm-4 col-xs-7 form-label-900" name='ADD_PRSON_BANK' id="field-6">
                                        <div class="clearfix"></div>

                                        <label class="col-sm-3 col-xs-5 form-label-700" for="field-15"><?php echo $cBPJS_TK?></label>
                                        <input type="text" required class="col-sm-3 col-xs-7 form-label-900" name='ADD_BPJS_TK'>
                                        <label class="col-lg-2 col-sm-2 col-xs-5 form-label-700" for="field-16"><?php echo $cBPJS_KES?></label>
                                        <input type="text" required class="col-lg-3 col-sm-4 col-xs-7 form-label-900" name='ADD_BPJS_KES'>
                                        <div class="clearfix"></div>

                                        <label class="col-sm-3 col-xs-5 form-label-700" for="field-15">No. KTA Satpam</label>
                                        <input type="text" required class="col-sm-3 col-xs-7 form-label-900" name='ADD_NO_KTA'>
                                        <label class="col-lg-2 col-sm-3 col-xs-5 form-label-700" for="field-8">Berlaku s/d</label>
                                        <input type="date" name='ADD_TGL_KTA' class="col-sm-3 col-xs-7 form-label-900" data-mask="date" value=<?php echo date("d-m-Y")?>>

                                        <?php
                                            echo '<div class="controls">';
                                            echo '<label class="col-lg-3 col-sm-3 col-xs-5 form-label-700" for="field-9">'.$cAGAMA.'</label>';
                                            echo '<select name="ADD_PRSON_RELG" class="col-lg-2 col-sm-3 col-xs-5 form-label-900 m-bot15">';
                                            $qQUERY=OpenTable('TbReligion', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
                                            while($aREC_AG_DATA=SYS_FETCH($qQUERY)){
                                                echo "<option class='col-sm-7 form-label-900' value='$aREC_AG_DATA[KODE]'  >$aREC_AG_DATA[NAMA]</option>";
                                            }
                                            echo '</select>';
                                            echo '</div><div class="clearfix"></div>';
                                        ?>
                                        <div class="form-group_5">
                                            <label class="col-sm-3 col-xs-5 form-label-700" for="field-13"><?php echo $cSTATUS?></label>
                                            <select name='ADD_MARRIAGE' class="col-sm-3 col-xs-5 form-label-900">
                                            <?php
                                                for ($S=1; $S<=2; $S++) {
                                                    echo "<option value=$S>$aSTATUS[$S]</option>";
                                                }
                                            ?>
                                            </select>
                                        </div>

                                        <div class="form-group_5">
                                            <label class="col-lg-2 col-sm-3 col-xs-5 form-label-700" for="field-10">Gol. Darah</label>
                                            <select name='ADD_BLOOD_GRUP' class="col-lg-1 col-sm-3 col-xs-5 form-label-900">
                                                <?php 
                                                    for ($I=1; $I<=4; $I++) {
                                                        echo "<option value=$aGOL_DAR[$I]>$aGOL_DAR[$I]</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="clearfix"></div>

                                        <label class="col-lg-3 col-sm-3 col-xs-5 form-label-700" for="field-14"><?php echo $cJML_ANAK?></label>
                                        <input type="number" class="col-lg-1 col-sm-3 col-xs-5 form-label-900" name='ADD_CHILD_HAVE'  data-mask="99">

                                        <label class="col-lg-2 col-sm-3 col-xs-5 form-label-700" for="field-14"><?php echo $cPASANGAN?></label>
                                        <input type="text" required class="col-sm-3 col-xs-7 form-label-900" name='ADD_SPOUSE' id="field-6"><br>
                                        <div class="clearfix"></div><br>

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label class="col-lg-3 col-sm-3 col-xs-5 form-label-500" for="field-14"><?php echo $cPENDIDIKAN?></label>
                                            <table cellspacing="0" id="example" class="table table-small-font table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th style="background-color:LightGray;">Tingkat Pendidikan'</th>
                                                        <th style="background-color:LightGray;"><?php echo $cNM_PENDIDIKAN?></th>
                                                        <th style="background-color:LightGray;"><?php echo $cJRSN_PNDDKKAN?></th>
                                                        <th style="background-color:LightGray;"><?php echo $cT1_PENDIDIKAN?></th>
                                                        <th style="background-color:LightGray;"><?php echo $cT2_PENDIDIKAN?></th>
                                                        <th style="background-color:LightGray;"><?php echo $cKT_PENDIDIKAN?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <select name="ADD_EDU_CODE1" class="col-lg-12 col-xs-12 form-label-900">
                                                            <option></option>
                                                            <?php
                                                                $aREC_PEND1=OpenTable('TbEducation');
                                                                while($aREC_PNDDKN1=SYS_FETCH($aREC_PEND1)){
                                                                    echo "<option value='$aREC_PNDDKN1[EDU_CODE]'  >$aREC_PNDDKN1[EDU_NAME]</option>";
                                                                }
                                                            ?>
                                                            </select><br>
                                                        </td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-3 col-xs-12 form-label-900" name='ADD_EDU_DESC1'></td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-8 col-xs-12 form-label-900" name='ADD_JURUSAN1'><br></td>
                                                        <td><input type="number" class="col-lg-6 col-sm-2 col-sm-3 col-xs-12 form-label-900" name='ADD_YEAR_IN1' maxlength="4" size="4"></td>
                                                        <td><input type="number" class="col-lg-6 col-sm-2 col-sm-3 col-xs-12 form-label-900" name='ADD_YEAR_OUT1' maxlength="4" size="4"></td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-8 col-xs-12 form-label-900" name='ADD_EDU_NOTE1' size="40"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <select name="ADD_EDU_CODE2" class="col-lg-12 col-sm-5 col-xs-12 form-label-900">
                                                            <option></option>
                                                            <?php
                                                                $aREC_PEND2=OpenTable('TbEducation');
                                                                while($aREC_PNDDKN2=SYS_FETCH($aREC_PEND2)){
                                                                    echo "<option value='$aREC_PNDDKN2[EDU_CODE]'  >$aREC_PNDDKN2[EDU_NAME]</option>";
                                                                }
                                                            ?>
                                                            </select><br>
                                                        </td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-3 col-xs-12 form-label-900" name='ADD_EDU_DESC2'></td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-8 col-xs-12 form-label-900" name='ADD_JURUSAN2'><br></td>
                                                        <td><input type="number" class="col-lg-6 col-sm-2 col-sm-3 col-xs-12 form-label-900" name='ADD_YEAR_IN2' maxlength="4" size="4"></td>
                                                        <td><input type="number" class="col-lg-6 col-sm-2 col-sm-3 col-xs-12 form-label-900" name='ADD_YEAR_OUT2' maxlength="4" size="4"></td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-8 col-xs-12 form-label-900" name='ADD_EDU_NOTE2'></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <select name="ADD_EDU_CODE3" class="col-lg-12 col-sm-5 col-xs-12 form-label-900">
                                                            <option></option>
                                                            <?php
                                                                $aREC_PEND3=OpenTable('TbEducation');
                                                                while($aREC_PNDDKN2=SYS_FETCH($aREC_PEND3)){
                                                                    echo "<option value='$aREC_PNDDKN2[EDU_CODE]'  >$aREC_PNDDKN2[EDU_NAME]</option>";
                                                                }
                                                            ?>
                                                            </select><br>
                                                        </td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-3 col-xs-12 form-label-900" name='ADD_EDU_DESC3'></td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-8 col-xs-12 form-label-900" name='ADD_JURUSAN3'></td>
                                                        <td><input type="number" class="col-lg-6 col-sm-2 col-sm-3 col-xs-12 form-label-900" name='ADD_YEAR_IN3' maxlength="4" size="4"></td>
                                                        <td><input type="number" class="col-lg-6 col-sm-2 col-sm-3 col-xs-12 form-label-900" name='ADD_YEAR_OUT3' maxlength="4" size="4"></td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-8 col-xs-12 form-label-900" name='ADD_EDU_NOTE3'></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <select name="ADD_EDU_CODE4" class="col-lg-12 col-sm-5 col-xs-12 form-label-900">
                                                            <option></option>
                                                            <?php
                                                                $aREC_PEND4=OpenTable('TbEducation');
                                                                while($aREC_PNDDKN2=SYS_FETCH($aREC_PEND4)){
                                                                    echo "<option value='$aREC_PNDDKN2[EDU_CODE]'  >$aREC_PNDDKN2[EDU_NAME]</option>";
                                                                }
                                                            ?>
                                                            </select><br>
                                                        </td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-3 col-xs-12 form-label-900" name='ADD_EDU_DESC4'></td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-8 col-xs-12 form-label-900" name='ADD_JURUSAN4'></td>
                                                        <td><input type="number" class="col-lg-6 col-sm-2 col-sm-3 col-xs-12 form-label-900" name='ADD_YEAR_IN4' data-mask="9999"></td>
                                                        <td><input type="number" class="col-lg-6 col-sm-2 col-sm-3 col-xs-12 form-label-900" name='ADD_YEAR_OUT4' data-mask="9999"></td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-8 col-xs-12 form-label-900" name='ADD_EDU_NOTE4'></td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <label class="col-lg-3 col-sm-3 col-xs-12 form-label-500" for="field-14">Pengalaman Kerja</label>
                                            <table cellspacing="0" id="example" class="table table-small-font table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th style="background-color:LightGray;">Nama Perusahaan'</th>
                                                        <th style="background-color:LightGray;">Bidang usaha</th>
                                                        <th style="background-color:LightGray;">Jabatan</th>
                                                        <th style="background-color:LightGray;">Tahun masuk</th>
                                                        <th style="background-color:LightGray;">Tahun keluar</th>
                                                        <th style="background-color:LightGray;">Alasan Keluar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><input type="text" required class="col-lg-12 col-sm-3 col-xs-12 form-label-900" name='ADD_PK_PRSHN1'></td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-3 col-xs-12 form-label-900" name='ADD_PK_USAHA1'></td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-8 col-xs-12 form-label-900" name='ADD_PK_JAB1'><br></td>
                                                        <td><input type="number" class="col-lg-6 col-sm-2 col-sm-3 col-xs-12 form-label-900" name='ADD_PK_MSK1' data-mask="9999"></td>
                                                        <td><input type="number" class="col-lg-6 col-sm-2 col-sm-3 col-xs-12 form-label-900" name='ADD_PK_KLR1' data-mask="9999"></td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-8 col-xs-12 form-label-900" name='ADD_PK_ALASAN1'></td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="text" required class="col-lg-12 col-sm-3 col-xs-12 form-label-900" name='ADD_PK_PRSHN2'></td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-3 col-xs-12 form-label-900" name='ADD_PK_USAHA2'></td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-8 col-xs-12 form-label-900" name='ADD_PK_JAB2'><br></td>
                                                        <td><input type="number" class="col-lg-6 col-sm-2 col-sm-3 col-xs-12 form-label-900" name='ADD_PK_MSK2' data-mask="9999"></td>
                                                        <td><input type="number" class="col-lg-6 col-sm-2 col-sm-3 col-xs-12 form-label-900" name='ADD_PK_KLR2' data-mask="9999"></td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-8 col-xs-12 form-label-900" name='ADD_PK_ALASAN2'></td>
                                                    </tr>
                                                </tbody>
                                            </table><br>

                                            <label class="col-lg-3 col-sm-3 col-xs-12 form-label-500" for="field-14">Pendidikan Non Formal</label>
                                            <input type="text" required class="col-lg-5 col-sm-4 col-xs-12 form-label-900" name="ADD_NONFML" placeholder="Kursus, pelatihan dll .......">
                                            <div class="clearfix"></div><br>
                                            <label class="col-sm-3 col-xs-9 form-label-700">Jabatan yang dilamar </label>
                                            <select name="ADD_JOB" class="col-sm-8 col-xs-9 form-label-900 select2-container" id="s2example-1">
                                                <?php
                                                    $qQUERY=OpenTable('TbOccupation', "APP_CODE='$cAPP_CODE' and DELETOR='' and PUBLIC=1", '', 'JOB_NAME');
                                                    while($aREC_JOB=SYS_FETCH($qQUERY)){
                                                        echo "<option value='$aREC_JOB[JOB_CODE]'  >$aREC_JOB[JOB_NAME]</option>";
                                                    }
                                                ?>
                                            </select>
                                            <div class="clearfix"></div>
                                            <br>

                                            <label class="col-lg-3 col-sm-3 col-xs-9 form-label-700">Tinggi Badan (cm)</label>
                                            <input type="number" class="col-lg-1 col-sm-4 col-xs-3 form-label-900" name="ADD_HEIGHT" id="field-6" data-mask="999" >
                                            <label class="col-lg-3 col-sm-3 col-xs-9 form-label-700">Berat Badan (kg)</label>
                                            <input type="number" class="col-lg-1 col-sm-4 col-xs-3 form-label-900" name="ADD_WEIGHT" id="field-6" data-mask="999" >
                                            <div class="clearfix"></div>
                                            <label class="col-lg-3 col-sm-3 col-xs-9 form-label-700">Ukuran Baju</label>
                                            <select name="ADD_UK_BAJU" class="col-lg-1 col-sm-8 col-xs-3 form-label-900">
                                                <option value='S'  >S</option>
                                                <option value='M'  >M</option>
                                                <option value='L'  >L</option>
                                                <option value='XL'  >XL</option>
                                                <option value='XXL'  >XXL</option>
                                                <option value='3XL'  >3XL</option>
                                                <option value='4XL'  >4XL</option>
                                            </select>

                                            <label class="col-lg-3 col-sm-3 col-xs-9 form-label-700">Ukurn Celana</label>
                                            <input type="number" class="col-lg-1 col-sm-4 col-xs-3 form-label-900" name="ADD_CELANA" id="field-6" data-mask="99" >
                                            <div class="clearfix"></div>

                                            <label class="col-lg-3 col-sm-3 col-xs-9 form-label-700">Ukuran Sepatu</label>
                                            <input type="number" class="col-lg-1 col-sm-4 col-xs-3 form-label-900" name="ADD_SEPATU" id="field-6" data-mask="99" >
                                            <div class="clearfix"></div>

                                            <label class="col-lg-3 col-sm-4 col-xs-6 form-label-700" for="field-21"><?php echo $cNO_KKELUARGA?></label>
                                            <input type="number" class="col-lg-2 col-xs-6 form-label-900" name='ADD_NO_KKLRG' data-mask="9999999999999999">
                                            <label class="col-lg-2 col-sm-4 col-xs-6 form-label-700" for="field-8"><?php echo $cNM_KKELUARGA?></label>
                                            <input type="text" required name='ADD_NM_KKLRG' class="col-lg-3 col-sm-4 col-xs-6 form-label-900">
                                            <div class="clearfix"></div><br>

                                            <label class="col-lg-3 col-sm-4 col-xs-6 form-label-700" for="field-8">Kartu Keluarga</label>
                                            <table id="example" class="display table table-hover table-condensed" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th style="background-color:LightGray;"><?php echo $cFULL_NAME?></th>
                                                        <th style="background-color:LightGray;">N I K / N I K S</th>
                                                        <th style="background-color:LightGray;"><?php echo $cGENDER?></th>
                                                        <th style="background-color:LightGray;"><?php echo $cTMP_LAHIR?></th>
                                                        <th style="background-color:LightGray;"><?php echo $cTGL_LAHIR?></th>
                                                        <th style="background-color:LightGray;">A g a m a</th>
                                                        <th style="background-color:LightGray;">P e n d i d i k a n</th>
                                                        <th style="background-color:LightGray;"><?php echo $cPEKERJAAN?></th>
                                                        <th style="background-color:LightGray;">Status Perkawinan</th>
                                                        <th style="background-color:LightGray;"><?php echo $cSTA_TUS?></th>
                                                        <th style="background-color:LightGray;"><?php echo $cKWARGA?></th>
                                                        <th style="background-color:LightGray;">Nama Ayah</th>
                                                        <th style="background-color:LightGray;">Nama Ibu</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><input type="text" required class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_FULL_NAME1' style="width:200px;"></td>
														<td><input type="number" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_KK_N_I_K1' data-mask="9999999999999999" style="width:200px;"></td>
														<td>
                                                            <input type="radio" name="ADD_KK_GENDER1" value=1 "checked" /> <?php echo $cLBL_PRIA?>
															<input type="radio" name="ADD_KK_GENDER1" value=2 /> <?php echo $cLBL_WANITA?>
                                                        </td>
														<td><input type="text" required class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_KK_BIRTH_PLCE1' style="width:200px;"></td>
														<td><input type="date" name='ADD_KK_BIRTH_DATE1' class="col-lg-12 col-sm-12 col-xs-12 form-label-900" data-mask="date" value=<?php echo date('d/m/Y')?>></td>
                                                        <td><div class="form-group">
															<select name='ADD_KK_RELI_GION1' class="col-lg-12 col-sm-12 col-xs-12 form-label-900 m-bot15" style="width:200px;">
																<?php 
																$qQUERY=OpenTable('TbReligion', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
																	echo "<option value=' '  > </option>";
																	while($aREC_PRS_RLGN=SYS_FETCH($qQUERY)){
																		echo "<option class='form-label-900' value='$aREC_PRS_RLGN[KODE]'  >$aREC_PRS_RLGN[NAMA]</option>";
																	}
																?>
															</select>
														</div></td>
														<td>
                                                            <div class="controls">
																<select name="ADD_KK_EDUCATE1" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" style="width:200px;">
																<?php 
																$qQUERY=OpenTable('TbEducation', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
																	while($aREC_PNDDKN=SYS_FETCH($qQUERY)){
																		echo "<option value='$aREC_PNDDKN[EDU_CODE]'  >$aREC_PNDDKN[EDU_NAME]</option>";
																	}
																?>
																</select><br>
															</div>
                                                        </td>
														<td><input type="text" required name='ADD_KK_JOB1' class="col-lg-12 col-sm-12 col-xs-12 form-label-900" style="width:200px;"></td>

														<td>
															<select name='ADD_KK_MAR_STATUS1' class="col-lg-12 col-sm-12 col-xs-12 form-label-900 m-bot15">
															<?php
																$qSTATUS=OpenTable('TbStatus', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
																echo "<option value=' '  > </option>";
																while($aREC_STATUS=SYS_FETCH($qSTATUS)){
																	echo "<option value='$aREC_STATUS[KODE]'  >$aREC_STATUS[NAMA]</option>";
																}
															?>
															</select>
														</td>
														<td><input type="text" required name='ADD_KK_STA_TUS1' class="col-lg-12 col-sm-12 col-xs-12 form-label-900"></td>
														<td><input type="text" required name='ADD_KK_CITI_ZEN1' class="col-lg-12 col-sm-12 col-xs-12 form-label-900"></td>
														<td><input type="text" required name='ADD_FATH_NAME1' class="col-lg-12 col-sm-12 col-xs-12 form-label-900" style="width:200px;"></td>
														<td><input type="text" required name='ADD_MOTH_NAME1' class="col-lg-12 col-sm-12 col-xs-12 form-label-900" style="width:200px;"></td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="text" required class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_FULL_NAME2'></td>
														<td><input type="number" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_KK_N_I_K2' data-mask="9999999999999999"></td>
														<td>
                                                            <input type="radio" name="ADD_KK_GENDER2" value=1 "checked" /> <?php echo $cLBL_PRIA?>
															<input type="radio" name="ADD_KK_GENDER2" value=2 /> <?php echo $cLBL_WANITA?>
                                                        </td>
														<td><input type="text" required class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_KK_BIRTH_PLCE2'></td>
														<td><input type="date" name='ADD_KK_BIRTH_DATE2' class="col-lg-12 col-sm-12 col-xs-12 form-label-900" data-mask="date" value=<?php echo date('d/m/Y')?>></td>
                                                        <td><div class="form-group">
															<select name='ADD_KK_RELI_GION2' class="col-lg-12 col-sm-12 col-xs-12 form-label-900 m-bot15">
																<?php 
																$qQUERY=OpenTable('TbReligion', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
																	echo "<option value=' '  > </option>";
																	while($aREC_PRS_RLGN=SYS_FETCH($qQUERY)){
																		echo "<option class='form-label-900' value='$aREC_PRS_RLGN[KODE]'  >$aREC_PRS_RLGN[NAMA]</option>";
																	}
																?>
															</select>
														</div></td>
														<td>
                                                            <div class="controls">
																<select name="ADD_KK_EDUCATE2" class="col-lg-12 col-sm-12 col-xs-12 form-label-900">
																<?php 
																$qQUERY=OpenTable('TbEducation', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
																	while($aREC_PNDDKN=SYS_FETCH($qQUERY)){
																		echo "<option value='$aREC_PNDDKN[EDU_CODE]'  >$aREC_PNDDKN[EDU_NAME]</option>";
																	}
																?>
																</select><br>
															</div>
                                                        </td>
														<td><input type="text" required name='ADD_KK_JOB2' class="col-lg-12 col-sm-12 col-xs-12 form-label-900"></td>

														<td>
															<select name='ADD_KK_MAR_STATUS2' class="col-lg-12 col-sm-12 col-xs-12 form-label-900 m-bot15">
															<?php
																$qSTATUS=OpenTable('TbStatus', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
																echo "<option value=' '  > </option>";
																while($aREC_STATUS=SYS_FETCH($qSTATUS)){
																	echo "<option value='$aREC_STATUS[KODE]'  >$aREC_STATUS[NAMA]</option>";
																}
															?>
															</select>
														</td>
														<td><input type="text" required name='ADD_KK_STA_TUS2' class="col-lg-12 col-sm-12 col-xs-12 form-label-900"></td>
														<td><input type="text" required name='ADD_KK_CITI_ZEN2' class="col-lg-12 col-sm-12 col-xs-12 form-label-900"></td>
														<td><input type="text" required name='ADD_FATH_NAME2' class="col-lg-12 col-sm-12 col-xs-12 form-label-900"></td>
														<td><input type="text" required name='ADD_MOTH_NAME2' class="col-lg-12 col-sm-12 col-xs-12 form-label-900"></td>
                                                    </tr>

                                                    <tr>
                                                        <td><input type="text" required class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_FULL_NAME3'></td>
														<td><input type="number" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_KK_N_I_K3' data-mask="9999999999999999"></td>
														<td>
                                                            <input type="radio" name="ADD_KK_GENDER3" value=1 "checked" /> <?php echo $cLBL_PRIA?>
															<input type="radio" name="ADD_KK_GENDER3" value=2 /> <?php echo $cLBL_WANITA?>
                                                        </td>
														<td><input type="text" required class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_KK_BIRTH_PLCE3'></td>
														<td><input type="date" name='ADD_KK_BIRTH_DATE3' class="col-lg-12 col-sm-12 col-xs-12 form-label-900" data-mask="date" value=<?php echo date('d/m/Y')?>></td>
                                                        <td><div class="form-group">
															<select name='ADD_KK_RELI_GION3' class="col-lg-12 col-sm-12 col-xs-12 form-label-900 m-bot15">
																<?php 
																$qQUERY=OpenTable('TbReligion', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
																	echo "<option value=' '  > </option>";
																	while($aREC_PRS_RLGN=SYS_FETCH($qQUERY)){
																		echo "<option class='form-label-900' value='$aREC_PRS_RLGN[KODE]'  >$aREC_PRS_RLGN[NAMA]</option>";
																	}
																?>
															</select>
														</div></td>
														<td>
                                                            <div class="controls">
																<select name="ADD_KK_EDUCATE3" class="col-lg-12 col-sm-12 col-xs-12 form-label-900">
																<?php 
																$qQUERY=OpenTable('TbEducation', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
																	while($aREC_PNDDKN=SYS_FETCH($qQUERY)){
																		echo "<option value='$aREC_PNDDKN[EDU_CODE]'  >$aREC_PNDDKN[EDU_NAME]</option>";
																	}
																?>
																</select><br>
															</div>
                                                        </td>
														<td><input type="text" required name='ADD_KK_JOB3' class="col-lg-12 col-sm-12 col-xs-12 form-label-900"></td>

														<td>
															<select name='ADD_KK_MAR_STATUS3' class="col-lg-12 col-sm-12 col-xs-12 form-label-900 m-bot15">
															<?php
																$qSTATUS=OpenTable('TbStatus', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
																echo "<option value=' '  > </option>";
																while($aREC_STATUS=SYS_FETCH($qSTATUS)){
																	echo "<option value='$aREC_STATUS[KODE]'  >$aREC_STATUS[NAMA]</option>";
																}
															?>
															</select>
														</td>
														<td><input type="text" required name='ADD_KK_STA_TUS3' class="col-lg-12 col-sm-12 col-xs-12 form-label-900"></td>
														<td><input type="text" required name='ADD_KK_CITI_ZEN3' class="col-lg-12 col-sm-12 col-xs-12 form-label-900"></td>
														<td><input type="text" required name='ADD_FATH_NAME3' class="col-lg-12 col-sm-12 col-xs-12 form-label-900"></td>
														<td><input type="text" required name='ADD_MOTH_NAME3' class="col-lg-12 col-sm-12 col-xs-12 form-label-900"></td>
                                                    </tr>

                                                    <tr>
                                                        <td><input type="text" required class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_FULL_NAME4'></td>
														<td><input type="number" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_KK_N_I_K4' data-mask="9999999999999999"></td>
														<td>
                                                            <input type="radio" name="ADD_KK_GENDER4" value=1 "checked" /> <?php echo $cLBL_PRIA?>
															<input type="radio" name="ADD_KK_GENDER4" value=2 /> <?php echo $cLBL_WANITA?>
                                                        </td>
														<td><input type="text" required class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_KK_BIRTH_PLCE4'></td>
														<td><input type="date" name='ADD_KK_BIRTH_DATE4' class="col-lg-12 col-sm-12 col-xs-12 form-label-900" data-mask="date" value=<?php echo date('d/m/Y')?>></td><br>
                                                        <td><div class="form-group">
															<select name='ADD_KK_RELI_GION4' class="col-lg-12 col-sm-12 col-xs-12 form-label-900 m-bot15">
																<?php 
																$qQUERY=OpenTable('TbReligion', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
																	echo "<option value=' '  > </option>";
																	while($aREC_PRS_RLGN=SYS_FETCH($qQUERY)){
																		echo "<option class='form-label-900' value='$aREC_PRS_RLGN[KODE]'  >$aREC_PRS_RLGN[NAMA]</option>";
																	}
																?>
															</select>
														</div></td>
														<td>
                                                            <div class="controls">
																<select name="ADD_KK_EDUCATE4" class="col-lg-12 col-sm-12 col-xs-12 form-label-900">
																<?php 
																$qQUERY=OpenTable('TbEducation', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
																	while($aREC_PNDDKN=SYS_FETCH($qQUERY)){
																		echo "<option value='$aREC_PNDDKN[EDU_CODE]'  >$aREC_PNDDKN[EDU_NAME]</option>";
																	}
																?>
																</select><br>
															</div>
                                                        </td>
														<td><input type="text" required name='ADD_KK_JOB4' class="col-lg-12 col-sm-12 col-xs-12 form-label-900"></td>

														<td>
															<select name='ADD_KK_MAR_STATUS4' class="col-lg-12 col-sm-12 col-xs-12 form-label-900 m-bot15">
															<?php
																$qSTATUS=OpenTable('TbStatus', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
																echo "<option value=' '  > </option>";
																while($aREC_STATUS=SYS_FETCH($qSTATUS)){
																	echo "<option value='$aREC_STATUS[KODE]'  >$aREC_STATUS[NAMA]</option>";
																}
															?>
															</select>
														</td>
														<td><input type="text" required name='ADD_KK_STA_TUS4' class="col-lg-12 col-sm-12 col-xs-12 form-label-900"></td>
														<td><input type="text" required name='ADD_KK_CITI_ZEN4' class="col-lg-12 col-sm-12 col-xs-12 form-label-900"></td>
														<td><input type="text" required name='ADD_FATH_NAME4' class="col-lg-12 col-sm-12 col-xs-12 form-label-900"></td>
														<td><input type="text" required name='ADD_MOTH_NAME4' class="col-lg-12 col-sm-12 col-xs-12 form-label-900"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
											<div class="clearfix"></div>

                                            <label class="col-lg-3 col-sm-3 col-xs-5 form-label-500" for="field-14">Keahlian</label>
                                            <table cellspacing="0" id="tech-companies-1" class="table table-small-font table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th style="background-color:LightGray;"><?php echo $cNAMA_SKILL?></th>
                                                        <th style="background-color:LightGray;"><?php echo $cKET_SKILL?></th>
                                                        <th style="background-color:LightGray;"><?php echo $cTHN_SKILL?></th>
                                                        <th style="background-color:LightGray;"><?php echo $cSERT_SKILL?></th>
                                                        <th style="background-color:LightGray;"><?php echo $cNOTE_SKILL?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <select id="SelectSkill" name="ADD_SKILL_CODE" class="col-lg-12 col-sm-12 col-xs-12 form-label-900">
                                                            <option></option>
                                                            <?php 
                                                                $REC_PEND=OpenTable('TbSkill');
                                                                while($aREC_SKILL=SYS_FETCH($REC_PEND)){
                                                                    echo "<option value='$aREC_SKILL[SKILL_CODE]'  >$aREC_SKILL[SKILL_NAME]</option>";
                                                                }
                                                            ?>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_SKILL_DESC'></td>
                                                        <td><input type="number" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_YEAR_SKILL'></td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_SKILL_SERT'></td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_SKILL_NOTE'></td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <label class="col-lg-3 col-sm-3 col-xs-12 form-label-500" for="field-14">Data Orang Tua</label>
                                            <table cellspacing="0" id="tech-companies-1" class="table table-small-font table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th style="background-color:LightGray;">*</th>
                                                        <th style="background-color:LightGray;">Nama Orang Tua</th>
                                                        <th style="background-color:LightGray;">Tempat Lahir</th>
                                                        <th style="background-color:LightGray;">Tanggal Lahir</th>
                                                        <th style="background-color:LightGray;">Pekerjaan</th>
                                                        <th style="background-color:LightGray;">Alamat</th>
                                                        <th style="background-color:LightGray;">Telpon</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><label class="col-lg-3 col-sm-3 col-xs-12 form-label-500">Bapak</label></td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_NAMA_BPK' style="width:200px;"></td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_TMP_LAHIR_BPK' style="width:200px;"></td>
                                                        <td><input type="date" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_TGL_LAHIR_BPK'></td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_PEKERJAAN_BPK' style="width:200px;"></td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_ALAMAT_BPK' style="width:200px;"></td>
                                                        <td><input type="number" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_TELPON_BPK' style="width:200px;"></td>
                                                    </tr>
                                                    <tr>
                                                        <td><label class="col-lg-3 col-sm-3 col-xs-12 form-label-500">Ibu</label></td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_NAMA_IBU' style="width:200px;"></td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_TMP_LAHIR_IBU' style="width:200px;"></td>
                                                        <td><input type="date" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_TGL_LAHIR_IBU'></td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_PEKERJAAN_IBU' style="width:200px;"></td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_ALAMAT_IBU' style="width:200px;"></td>
                                                        <td><input type="number" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_TELPON_IBU' style="width:200px;"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
										    <div class="clearfix"></div><br>
                                            <label class="col-lg-6 col-sm-6 col-xs-12 form-label-500" for="field-14"><?php echo S_MSG('PH72','Data kontak darurat yang bisa dihubungi')?></label>
                                            <table cellspacing="0" id="tech-companies-1" class="table table-small-font table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th style="background-color:LightGray;"><?php echo S_MSG('PH73','Nama kontak')?></th>
                                                        <th style="background-color:LightGray;"><?php echo S_MSG('PH74','No. Telpon')?></th>
                                                        <th style="background-color:LightGray;"><?php echo S_MSG('PH75','No. HP')?></th>
                                                        <th style="background-color:LightGray;"><?php echo S_MSG('PH76','No. Telp. Ktr.')?></th>
                                                        <th style="background-color:LightGray;"><?php echo S_MSG('PH77','Hubungan')?></th>
                                                        <th style="background-color:LightGray;"><?php echo S_MSG('F019','Catatan')?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><input type="text" required class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_NAMA_KONTAK1' style="width:200px;"></td>
                                                        <td><input type="number" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_TELP_KONTAK1' style="width:200px;"></td>
                                                        <td><input type="number" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_HP_KONTAK1' style="width:200px;"></td>
                                                        <td><input type="number" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_KTR_KONTAK1' style="width:200px;"></td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_HUB_KONTAK1' style="width:200px;"></td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_NOTE_KONTAK1' style="width:200px;"></td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="text" required class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_NAMA_KONTAK2'></td>
                                                        <td><input type="number" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_TELP_KONTAK2'></td>
                                                        <td><input type="number" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_HP_KONTAK2'></td>
                                                        <td><input type="number" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_KTR_KONTAK2'></td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_HUB_KONTAK2'></td>
                                                        <td><input type="text" required class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_NOTE_KONTAK2'></td>
                                                    </tr>
                                                </tbody>
                                            </table>

										    <br><div class="clearfix"></div>
                                        </div>
                                        <label class="col-lg-3 col-sm-3 col-xs-12 form-label-500" for="field-14">Penyakit yang pernah diderita</label>
                                        <input type="text" required class="col-lg-5 col-sm-4 col-xs-12 form-label-900" name="ADD_SICK">
                                        <div class="clearfix"></div><br>
                                        <label class="col-lg-3 col-sm-3 col-xs-9 form-label-500" for="field-14">Pernah di rawat di rumah sakit ( hari )</label>
                                        <input type="number" class="col-lg-1 col-sm-4 col-xs-3 form-label-900" name="ADD_RAWAT" data-mask="999">
                                        <div class="clearfix"></div><br>
                                        <div class="form-group">
                                            <label class="form-label" for="field-1">Foto setengah badan</label>
                                            <span class="desc"></span>																	
                                            <img class="img-responsive" src="<?php echo $cFILE_FOTO?>" alt="" style="max-width:220px;">
                                            <div class="controls">
                                                <input name="upload_image" type="file" class="form-control">
                                            </div>
                                        </div>
                                        <div class="text-left">
                                            <input type="submit" class="btn btn-primary" value=Submit>
                                            <input type="button" class="btn" value=Cancel onclick=self.history.back()>
                                        </div>
									</form>
								</div>
							</div>
						</section>
					</div>

				</section>
 			</div>
             <?php	require_once("js_framework.php");	?>
			<script src="assets/plugins/datepicker/js/datepicker.js" type="text/javascript"></script> 
			<script src="assets/plugins/autosize/autosize.min.js" type="text/javascript"></script>
			<script src="assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
			<script src="assets/plugins/select2/select2.min.js" type="text/javascript"></script>
			<link href="assets/plugins/select2/select2.css" rel="stylesheet" type="text/css" media="screen"/>
			<link href="assets/plugins/typeahead/css/typeahead.css" rel="stylesheet" type="text/css" media="screen"/>
            <script src="assets/js/scripts.js" type="text/javascript"></script>
            <script src="sys_js.js" type="text/javascript"></script>
        </body>
	</html>
<?php
	break;

    case 'addNew':
        if($_POST['ADD_PRSON_NAME']==''){
            echo "<script> alert('Nama tidak boleh kosong');	window.history.back();	</script>";
            return;
        }
        if($_POST['ADD_PRSON_GEND']==0){
            echo "<script> alert('Gender tidak boleh kosong');	window.history.back();	</script>";
            return;
        }
/*
        if($_POST['ADD_ADDRESS']==''){
            $cMSG = 'Alamat tidak boleh kosong';
            echo "<script> alert('$cMSG');	window.history.back();	</script>";
            return;
        }
        if($_POST['ADD_PRSN_KEL']==''){
            $cMSG = 'Alamat belum diisi lengkap';
            echo "<script> alert('$cMSG');	window.history.back();	</script>";
            return;
        }
*/
        if($_POST['ADD_BIRTH_DATE']==''){
            $cMSG = 'Tanggal lahir belum diisi';
            echo "<script> alert('$cMSG');	window.history.back();	</script>";
            return;
        }
        $nAGAMA		= 1;
        if ($_POST['ADD_PRSON_RELG']!='') $nAGAMA	= $_POST['ADD_PRSON_RELG'];

        $nRec_id = date_create()->format('Uv');
        $cPERSON_CODE = (string)$nRec_id;

        RecCreate('People', ['PEOPLE_CODE', 'PEOPLE_NAME', 'ENTRY', 'DATE_ENTRY', 'APP_CODE'],
            [$cPERSON_CODE, ENCODE($_POST['ADD_PRSON_NAME']), $cUSERCODE, date("Y-m-d H:i:s"), $cAPP_CODE]);

        if($_POST['ADD_NICK_NAME']!=''){
            RecCreate('PeopleNickName', ['PRSON_CODE', 'NICK_NAME', 'ENTRY', 'APP_CODE', 'REC_ID'], [$cPERSON_CODE, ENCODE($_POST['ADD_NICK_NAME']), $cUSERCODE, $cAPP_CODE, NowMSecs()]);
        }
        RecCreate('PersonMain', ['PRSON_CODE', 'PRSON_GEND', 'PRSN_RT', 'PRSN_RW', 'PRS_PHN','PRS_KTP', 'BIRTH_PLC', 'BIRTH_DATE', 'PRSON_ACCN', 'PRSON_BANK', 'PRSON_RELG', 'MARRIAGE', 'PRSON_SLRY', 'ENTRY', 'DATE_ENTRY', 'APP_CODE'],
            [$cPERSON_CODE, $_POST['ADD_PRSON_GEND'], $_POST['ADD_RT'], $_POST['ADD_RW'], $_POST['ADD_HAND_PHN'], $_POST['ADD_PRS_KTP'], ENCODE($_POST['ADD_BIRTH_PLC']), $_POST['ADD_BIRTH_DATE'], $_POST['ADD_PRSON_ACCN'], $_POST['ADD_PRSON_BANK'], $nAGAMA, $_POST['ADD_MARRIAGE'], 2, $cUSERCODE, date("Y-m-d H:i:s"), $cAPP_CODE]);

        $cJABAT_AN=$_POST['ADD_JOB'];
        if ($cJABAT_AN!=''){
            RecCreate('PrsOccuption', ['PRSON_CODE', 'JOB_CODE', 'ENTRY', 'APP_CODE', 'DATE_ENTRY'], [$cPERSON_CODE, $cJABAT_AN, $cUSERCODE, $cAPP_CODE, date("Y-m-d H:i:s")]);
        }

        $cTELEGRAM=$_POST['ADD_TELEGRAM'];
        if ($cTELEGRAM!=''){
            RecCreate('PeopleTelegram', ['PEOPLE_CODE', 'PEOPLE_TELEGRAM', 'ENTRY', 'APP_CODE', 'REC_ID'], [$cPERSON_CODE, $cTELEGRAM, $cUSERCODE, $cAPP_CODE, NowMSecs()]);
        }

        RecCreate('PeopleAddress', ['PEOPLE_CODE', 'PEOPLE_ADDRESS', 'AREA_CODE', 'PEOPLE_ZIP', 'APP_CODE', 'ENTRY', 'REC_ID'],
            [$cPERSON_CODE, $_POST['ADD_ADDRESS'], $_POST['ADD_PRSN_KEL'], $_POST['ADD_PRS_ZIP'], $cAPP_CODE, $cUSERCODE, NowMSecs()]);

        $cHOME_PHONE=$_POST['ADD_HOMEPHN'];
        if ($cHOME_PHONE!=''){
            RecCreate('PeopleHomePhone', ['PEOPLE_CODE', 'HOME_PHONE', 'ENTRY', 'APP_CODE', 'REC_ID'], [$cPERSON_CODE, $cHOME_PHONE, $cUSERCODE, $cAPP_CODE, NowMSecs()]);
        }
        $cEMAIL= ( $_POST['ADD_PRS_EMAIL']== '' ? '' : $_POST['ADD_PRS_EMAIL']);
        if ($cEMAIL!=''){
            RecCreate('PeopleEMail', ['PEOPLE_CODE', 'PPL_WEB', 'ENTRY', 'APP_CODE', 'REC_ID'], [$cPERSON_CODE, $cEMAIL, $cUSERCODE, $cAPP_CODE, NowMSecs()]);
        }
        $cBLOOD=$_POST['ADD_BLOOD_GRUP'];
        if ($cBLOOD!=''){
            RecCreate('PeopleBlood', ['PEOPLE_CODE', 'PEOPLE_BLOOD_GROUP', 'ENTRY', 'APP_CODE', 'REC_ID'], [$cPERSON_CODE, $cBLOOD, $cUSERCODE, $cAPP_CODE, NowMSecs()]);
        }
        $nJML_ANAK 	= 0;
        if ($_POST['ADD_CHILD_HAVE']!='') $nJML_ANAK	= $_POST['ADD_CHILD_HAVE'];
        $cSPOUSE=ENCODE($_POST['ADD_SPOUSE']);
        if ($cSPOUSE!=''){
            RecCreate('PrsSpouse', ['PRSON_CODE', 'SPOUSE_NAME', 'CHILD_HAVE', 'ENTRY', 'APP_CODE', 'REC_ID'], [$cPERSON_CODE, $cSPOUSE, $nJML_ANAK, $cUSERCODE, $cAPP_CODE, NowMSecs()]);
        }

        $cNPWP=($_POST['ADD_PRS_NPWP'] == '' ? '' : $_POST['ADD_PRS_NPWP']);
        $cBPJS_TK=$_POST['ADD_BPJS_TK'];
        $cBPJS_KES=$_POST['ADD_BPJS_KES'];
        if ($cNPWP!='' || $cBPJS_TK!='' || $cBPJS_KES!=''){
            RecCreate('PrsNumber', ['PRSON_CODE', 'NO_NPWP', 'NO_BPJS_TK', 'ENTRY', 'APP_CODE', 'REC_ID'], [$cPERSON_CODE, $cNPWP, $cBPJS_TK, $cUSERCODE, $cAPP_CODE, NowMSecs()]);
        }
        
        $cKTA=($_POST['ADD_NO_KTA'] == '' ? '' : $_POST['ADD_NO_KTA']);
        if ($cKTA!=''){
            RecCreate('PrsMemberCard', ['PERSON_CODE', 'CARD_NUMBER', 'VALID_UNTIL', 'ENTRY', 'APP_CODE', 'REC_ID'], [$cPERSON_CODE, $cKTA, $_POST['ADD_TGL_KTA'], $cUSERCODE, $cAPP_CODE, NowMSecs()]);
        }

		for($I=1; $I<=4; $I++):
            $J=(string)$I;
            $K='ADD_EDU_CODE'.$J;
            $cEDUCAT=$_POST[$K];
            if ($cEDUCAT!=''){
                $cREC_ID=NowMSecs();
                $nREC_ID=intval($cREC_ID)+$I;
                $cREC_ID=(string)$nREC_ID;
                RecCreate('PrsEducation', ['PRSON_CODE', 'EDU_CODE', 'EDU_DESC', 'EDU_JRSN', 'YEAR_IN', 'YEAR_OUT', 'EDU_NOTE', 'ENTRY', 'APP_CODE', 'REC_ID'],
                    [$cPERSON_CODE, $cEDUCAT, ENCODE($_POST['ADD_EDU_DESC'.$J]), ENCODE($_POST['ADD_JURUSAN'.$J]), $_POST['ADD_YEAR_IN'.$J], $_POST['ADD_YEAR_OUT'.$J], ENCODE($_POST['ADD_EDU_NOTE'.$J]), $cUSERCODE, $cAPP_CODE, $cREC_ID]);
            }
        endfor;
        $cEDU_NF=ENCODE($_POST['ADD_NONFML']);
        if ($cEDU_NF!=''){
            RecCreate('PrsEdNonFormal', ['PRSON_CODE', 'EDU_INFORM', 'EDU_LOC', 'EDU_YEAR', 'EDU_JURUSAN', 'NOTE', 'ENTRY', 'APP_CODE', 'REC_ID'],
                [$cPERSON_CODE, $cEDU_NF, '', 0,'', '', $cUSERCODE, $cAPP_CODE, NowMSecs()]);
        }
        $cPENGALAMAN1=ENCODE($_POST['ADD_PK_PRSHN1']);
        if ($cPENGALAMAN1!=''){
            $nRec_id = date_create()->format('Uv');
            $cREC_ID = (string)$nRec_id+1;
               RecCreate('PrsExperience', ['PRSON_CODE', 'COMPANY', 'BUSINESS', 'OCCUPATION', 'START_YEAR', 'FINISH_YEAR', 'OUT_REASON', 'ENTRY', 'APP_CODE', 'REC_ID'],
                [$cPERSON_CODE, $cPENGALAMAN1, $_POST['ADD_PK_USAHA1'], $_POST['ADD_PK_JAB1'], $_POST['ADD_PK_MSK1'], $_POST['ADD_PK_KLR1'], $_POST['ADD_PK_ALASAN1'], $cUSERCODE, $cAPP_CODE, $cREC_ID]);
        }
        $cPENGALAMAN2=ENCODE($_POST['ADD_PK_PRSHN2']);
        if ($cPENGALAMAN2!=''){
            $nRec_id = date_create()->format('Uv');
            $cREC_ID = (string)$nRec_id+2;
            RecCreate('PrsExperience', ['PRSON_CODE', 'COMPANY', 'BUSINESS', 'OCCUPATION', 'START_YEAR', 'FINISH_YEAR', 'OUT_REASON', 'ENTRY', 'APP_CODE', 'REC_ID'],
                [$cPERSON_CODE, $cPENGALAMAN2, ENCODE($_POST['ADD_PK_USAHA2']), ENCODE($_POST['ADD_PK_JAB2']), $_POST['ADD_PK_MSK2'], $_POST['ADD_PK_KLR2'], ENCODE($_POST['ADD_PK_ALASAN1']), $cUSERCODE, $cAPP_CODE, $cREC_ID]);
        }

        $cSKILL=$_POST['ADD_SKILL_CODE'];
        if ($cSKILL!=''){
            RecCreate('PrsSkill', ['PRSON_CODE', 'SKILL_CODE', 'SKILL_DESC', 'YEAR_SKILL', 'SKILL_SERT', 'SKILL_NOTE', 'ENTRY', 'APP_CODE', 'REC_ID'],
                [$cPERSON_CODE, $cSKILL, $_POST['ADD_SKILL_DESC'], $_POST['ADD_YEAR_SKILL'], $_POST['ADD_SKILL_SERT'], ENCODE($_POST['ADD_SKILL_NOTE']), $cUSERCODE, $cAPP_CODE, NowMSecs()]);
        }

        $cKONTAK=$_POST['ADD_NAMA_KONTAK1'];
        if ($cKONTAK!='') {
            RecCreate('PeopleContact', ['PEOPLE_CODE', 'FAMILY_NAME', 'FAMILY_PHONE', 'FAMILY_HP', 'FAMILY_OFFICE_PHN', 'FAMILY_RELATIONSHIP', 'FAMILY_NOTE', 'ENTRY', 'APP_CODE', 'REC_ID'],
                [$cPERSON_CODE, $cKONTAK, $_POST['ADD_TELP_KONTAK1'], $_POST['ADD_HP_KONTAK1'], $_POST['ADD_KTR_KONTAK1'], $_POST['ADD_HUB_KONTAK1'], $_POST['ADD_NOTE_KONTAK1'], $cUSERCODE, $cAPP_CODE, NowMSecs()]);
        }
        $cKONTAK=$_POST['ADD_NAMA_KONTAK2'];
        if ($cKONTAK!='') {
            $nRec_id = date_create()->format('Uv');
            $cREC_ID = (string)$nRec_id+2;
            RecCreate('PeopleContact', ['PEOPLE_CODE', 'FAMILY_NAME', 'FAMILY_PHONE', 'FAMILY_HP', 'FAMILY_OFFICE_PHN', 'FAMILY_RELATIONSHIP', 'FAMILY_NOTE', 'ENTRY', 'APP_CODE', 'REC_ID'],
                [$cPERSON_CODE, $cKONTAK, $_POST['ADD_TELP_KONTAK2'], $_POST['ADD_HP_KONTAK2'], $_POST['ADD_KTR_KONTAK2'], $_POST['ADD_HUB_KONTAK2'], $_POST['ADD_NOTE_KONTAK2'], $cUSERCODE, $cAPP_CODE, $cREC_ID]);
        }
        $cHEIGHT=$_POST['ADD_HEIGHT'];
        if ($cHEIGHT!='') {
            RecCreate('PrsSize', ['PRSON_CODE', 'PRS_HEIGHT', 'PRS_WEIGHT', 'PRS_SHIRT', 'PRS_SHOE', 'PRS_PDH', 'ENTRY', 'APP_CODE', 'REC_ID'],
                [$cPERSON_CODE, $cHEIGHT, $_POST['ADD_WEIGHT'], $_POST['ADD_UK_BAJU'], $_POST['ADD_SEPATU'], $_POST['ADD_CELANA'], $cUSERCODE, $cAPP_CODE, NowMSecs()]);
        }

        $cFILE_IMAGE = $cAPP_CODE.'FTP_PERSON_IMG'.$cPERSON_CODE.'.jpg';
        $cFOLDER_IMG = S_PARA('FTP_PERSON_IMG', '../../home/riza/www/images/person/');
        $cFILE_FOTO = $cFOLDER_IMG.$cFILE_IMAGE;

        $cFILE_FOLDER   = $_FILES['upload_image']['tmp_name'];
        if (!empty($cFILE_FOTO)){ 
            UploadFile($cFILE_FOLDER, $cFILE_FOTO);
        }
    
        $cNO_KK=$_POST['ADD_NO_KKLRG'];
        if ($cNO_KK!=''){
            RecCreate('PrsFamCardHdr', ['PRSON_CODE', 'NO_KKLRG', 'NM_KKLRG', 'ENTRY', 'APP_CODE', 'REC_ID'],
                [$cPERSON_CODE, $cNO_KK, ENCODE($_POST['ADD_NM_KKLRG']), $cUSERCODE, $cAPP_CODE, NowMSecs()]);
        }
        $nLOOP=0;
        for ($I = 0; $I < 5; $I++) {
            $J=(string)$I;
            $cKK=$_POST['ADD_FULL_NAME'.$J];
            if ($cKK!='') {
                $cB_PLACE='';
                $nGENDER = ( $_POST['ADD_KK_GENDER'.$J]!='' ? $_POST['ADD_KK_GENDER'.$J] : 1);
                $cB_PLACE= ( $_POST['ADD_KK_BIRTH_PLCE'.$J]=='' ? '' : ENCODE($_POST['ADD_KK_BIRTH_PLCE'.$J]));
                while($nLOOP<10) {
                    $nLOOP++;
                    $nRec_id = date_create()->format('Uv')+$nLOOP;
                    $cREC_ID = (string)$nRec_id;
                    $aFLD = ['PRSON_CODE', 'FULL_NAME', 'N_I_K', 'GENDER', 'BIRTH_PLCE', 'BIRTH_DATE', 'RELI_GION', 'EDUCATE', 'J_O_B', 'STATUS_MAR', 'STA_TUS', 'CITI_ZEN', 'FATH_NAME', 'MOTH_NAME', 'ENTRY', 'APP_CODE', 'REC_ID'];
                    $lCREATE=RecCreate('PrsFamCardDtl', $aFLD,
                        [$cPERSON_CODE, ENCODE($cKK), $_POST['ADD_KK_N_I_K'.$J], $nGENDER, $cB_PLACE, $_POST['ADD_KK_BIRTH_DATE'.$J], 
                        $_POST['ADD_KK_RELI_GION'.$J], $_POST['ADD_KK_EDUCATE'.$J], $_POST['ADD_KK_JOB'.$J], 
                        $_POST['ADD_KK_MAR_STATUS'.$J], $_POST['ADD_KK_STA_TUS'.$J], $_POST['ADD_KK_CITI_ZEN'.$J], 
                        ENCODE($_POST['ADD_FATH_NAME'.$J]), ENCODE($_POST['ADD_MOTH_NAME'.$J]), $cUSERCODE, $cAPP_CODE, $cREC_ID]);
                    if($lCREATE=='1')    $nLOOP+=10;
                }
            }
        }
    
        echo "<script> alert('Terimakasih. Data Anda sudah di rekam di sistem kami.');	</script>";
        header('location:web_index.php');
        SYS_DB_CLOSE($DB2);
        break;

}
