<?php
//	prs_app.php //
//  TODO : upload foto, tmk

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('upload_max_filesize', '20M');
ini_set('max_execution_time', 10); //10 seconds

// Start the session
session_start();

function upload_file($target_file = '', $file_field = 'FOTOIMG') {
    if(!isset($_FILES[$file_field])) {
        return [
            'success' => false,
            'message' => 'No file uploaded'
        ];
    }
    
    if($_FILES[$file_field]['error'] != UPLOAD_ERR_OK) {
        $error_messages = [
            UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form',
            UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload'
        ];
        
        $error_code = $_FILES[$file_field]['error'];
        $error_message = $error_messages[$error_code] ?? 'Unknown upload error';
        
        //log error to error.txt
        print_r2('File upload error: ' . $error_message . ' (code: ' . $error_code . ')');

        return [
            'success' => false,
            'message' => 'File upload error: ' . $error_message . ' (code: ' . $error_code . ')'
        ];
    }
    
    // Get file information
    $tmp_name = $_FILES[$file_field]['tmp_name'];
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($_FILES[$file_field]['name'], PATHINFO_EXTENSION));
    
    // Check if file is an image
    $check = getimagesize($tmp_name);
    if($check === false) {
        return [
            'success' => false,
            'message' => 'File is not a valid image.'
        ];
    }
    
    // Allow only certain image formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        return [
            'success' => false,
            'message' => 'Only JPG, JPEG and PNG formats are allowed.'
        ];
    }
    
    // Process the image - resize and compress
    list($width, $height) = getimagesize($tmp_name);
    
    // Set maximum dimensions
    $max_width = 300;
    $max_height = 300;
    
    // Calculate new dimensions while maintaining aspect ratio
    $new_width = $width;
    $new_height = $height;
    
    if($width > $max_width || $height > $max_height) {
        $ratio = min($max_width / $width, $max_height / $height);
        $new_width = round($width * $ratio);
        $new_height = round($height * $ratio);
    }
    
    // Create a new image
    $src_image = null;
    switch($imageFileType) {
        case 'jpg':
        case 'jpeg':
            $src_image = imagecreatefromjpeg($tmp_name);
            break;
        case 'png':
            $src_image = imagecreatefrompng($tmp_name);
            break;
        default:
            return [
                'success' => false,
                'message' => 'Unsupported image format.'
            ];
    }
    
    if(!$src_image) {
        return [
            'success' => false,
            'message' => 'Failed to process the image.'
        ];
    }
    
    // Create destination image with new dimensions
    $dst_image = imagecreatetruecolor($new_width, $new_height);
    
    // Preserve transparency for PNG images
    if($imageFileType == 'png') {
        imagealphablending($dst_image, false);
        imagesavealpha($dst_image, true);
        $transparent = imagecolorallocatealpha($dst_image, 255, 255, 255, 127);
        imagefilledrectangle($dst_image, 0, 0, $new_width, $new_height, $transparent);
    }
    
    // Resize the image
    imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    
    // Save the resized image
    $result = false;
    switch($imageFileType) {
        case 'jpg':
        case 'jpeg':
            $result = imagejpeg($dst_image, $target_file, 85); // 85% quality
            break;
        case 'png':
            $result = imagepng($dst_image, $target_file, 8); // Compression level 8
            break;
    }
    
    // Free up memory
    imagedestroy($src_image);
    imagedestroy($dst_image);
    
    if(!$result) {
        return [
            'success' => false,
            'message' => 'Failed to save the processed image.'
        ];
    }
    
    return [
        'success' => true,
        'message' => 'Image uploaded and processed successfully.',
        'file_path' => $target_file
    ];
}

if (!isset($_SESSION['gUSERCODE'])) 	$_SESSION['gUSERCODE']='self';
$_SESSION['gSYS_PARA'] 	= 'JNS_PRSHN';
$_SESSION['sLANG']		= '1';
$_SESSION['gSYS_NAME']     = 'Rainbow Sys';
$_SESSION['gSCR_HEADER']   = 'DASHBOARD';

$cAPP_CODE='YAZA';
if (isset($_GET['_c'])) $cAPP_CODE=$_GET['_c'];
$_SESSION['data_FILTER_CODE'] = $cAPP_CODE;
$cUSERCODE = $_SESSION['gUSERCODE'];

require_once "sys_function.php";
require_once "sys_connect.php";

$cHEADER 		= 'Form Lamaran';

$aGOL_DAR = array(1=> 'A ', 'B ', 'AB', 'O');
$cTHN_MASUK = S_MSG('PA96','Tahun masuk');
$cTHN_KELUAR= S_MSG('PA97','Tahun keluar');
$aSTATUS  = array(1=> S_MSG('PB58','Lajang'), S_MSG('PB59','Kawin'), S_MSG('PB60','Cerai'));

$cGENDER		= 'Gender *';
$cTMP_LAHIR		= S_MSG('PA05','Tempat Lahir');
$cTGL_LAHIR		= S_MSG('PA06','Tanggal Lahir');
$cLBL_PRIA		= S_MSG('PD12','Pria');
$cLBL_WANITA	= S_MSG('PD13','Wanita');

$cNPWP		    = S_MSG('PG83','No. NPWP');
$cBPJS_TK		= S_MSG('PG84','No. BPJS TK');
$cBPJS_KES		= S_MSG('PG85','No. BPJS KES');

$cPENDIDIKAN	= S_MSG('PA91','Pendidikan');
$cAYAH			= S_MSG('PA8D','Ayah');
$cIBU			= S_MSG('PA8E','Ibu');

$cACTION='';
if (isset($_GET['_a'])) $cACTION=$_GET['_a'];
$nRec_id = date_create()->format('Uv');
$cPERSON_CODE = (string)$nRec_id;

$cFILE_IMAGE = $cAPP_CODE.'_PRS_FOTO_'.$cPERSON_CODE.'.jpg';
$cFOLDER_IMG = S_PARA('FTP_PERSON_IMG', '../www/images/person/');
// $cFOLDER_IMG = '../www/images/person/';
// $cFOLDER_IMG = 'foto/';
$cFILE_FOTO = $cFOLDER_IMG.$cFILE_IMAGE;

switch($cACTION){
	default:
        DEF_WINDOW($cHEADER, '', '', 'fe_topbar.php', '*');
            FE_FORM($cHEADER, '?_a=addNew&_c='.$cAPP_CODE);
                TDIV();
                    LABEL([3,3,4,12], '700', 'Nama lengkap ( sesuai KTP) *');
                    INPUT('text', [6,6,6,12], '900', 'ADD_PRSON_NAME', '', 'focus', '', '', 0, '', 'fix');
                    LABEL([3,3,4,6], '700', 'Nama Panggilan');
                    INPUT('text', [4,4,4,6], '900', 'ADD_NICK_NAME', '', '', '', '', 0, '', 'fix');
                    echo '<br>';
                    LABEL([3,3,4,6], '700', $cGENDER);
                    RADIO('ADD_PRSON_GEND', [1,2], [false, false], [$cLBL_PRIA, $cLBL_WANITA]);
                    LABEL([3,3,4,6], '700', 'Alamat KTP *');
                    INPUT('textarea', [3,3,3,12], '900', 'ADD_ADDRESS', '', '', '', '', 0, '', 'fix');
                    LABEL([3,3,3,3], '700', S_MSG('PA36','RT'));
                    INPUT('number', [2,2,2,3], '900', 'ADD_RT', '', '', '999', '', 3, '', '');
                    LABEL([3,3,3,3], '700', S_MSG('PA37','RW'));
                    INPUT('number', [2,2,2,3], '900', 'ADD_RW', '', '', '999', '', 3, '', 'fix');
                    LABEL([3,3,3,3], '700', S_MSG('H650','Kode Pos'));
                    INPUT('textarea', [2,2,2,3], '900', 'ADD_PRS_ZIP', '', '', '99999', '', 5, '', 'fix');
                    echo '<br>';
                    LABEL([3,3,3,3], '700', S_MSG('CB81','Propinsi'). ' *');
                    TDIV(8,8,8,12);
                        SELECT([8,8,8,8], 'ADD_PROPINSI', '', 'prov_s2');
                                $qPROV=OpenTable('TbProvince', "APP_CODE='$cAPP_CODE' and DELETOR=''", '', 'nama');
                                while($aPROV=SYS_FETCH($qPROV)) print "<option value='$aPROV[id_prov]'  >$aPROV[nama]</option>";
                        echo'</select>';
                    eTDIV();
                    CLEAR_FIX();
                    echo '<br>';
?>

                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 col-xs-3 form-label-700" for="kab_s2"><?php echo S_MSG('NL54','Kota')?></label>
                        <div class="col-lg-9 col-sm-9 col-xs-9"><input type="hidden" id="kab_s2" name="ADD_KAB_KOTA"></div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 col-xs-3 form-label-700" for="kec_s2"><?php echo S_MSG('PA39','Kec')?></label>
                        <div class="col-lg-9 col-sm-9 col-xs-9"><input type="hidden" id="kec_s2" name="ADD_PRSN_KEC"></div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 col-xs-3 form-label-700" for="kel_s2"><?php echo S_MSG('PA38','Kelurahan')?></label>
                        <div class="col-lg-9 col-sm-9 col-xs-9"><input type="hidden" id="kel_s2" name="ADD_PRSN_KEL"></div>
                    </div><br>
<?php
                    LABEL([3,3,4,12], '700', 'Alamat Domisili ( jika beda dgn KTP )');
                    INPUT('textarea', [3,3,3,12], '900', 'ADD_DOMISILI', '', '', '', '', 0, '', 'fix');
                    LABEL([3,3,3,5], '700', S_MSG('PA40','No. KTP'));
                    INPUT('number', [2,2,2,7], '900', 'ADD_PRS_KTP', '', '', '9999999999999999', '', 16, '', 'fix');
                    LABEL([3,3,3,5], '700', S_MSG('PA33','No. Telpon'));
                    INPUT('number', [2,2,2,7], '900', 'ADD_HAND_PHN', '', '', '', '', 14, '', 'fix');
                    LABEL([3,3,3,5], '700', 'No. Telegram');
                    INPUT('number', [2,2,2,7], '900', 'ADD_TELEGRAM', '', '', '', '', 14, '', 'fix');
                    LABEL([3,3,3,5], '700', S_MSG('F006','Nomor Telpon'));
                    INPUT('number', [2,2,2,7], '900', 'ADD_HOMEPHN', '', '', '', '', 14, '', 'fix');
                    LABEL([3,3,4,5], '700', $cTMP_LAHIR);
                    INPUT('text', [3,3,3,7], '900', 'ADD_BIRTH_PLC', '', '', '', '', 0, '', 'fix');
                    LABEL([3,3,4,5], '700', $cTGL_LAHIR);
                    INP_DATE([3,3,3,6], '900', 'ADD_BIRTH_DATE', date("d/m/Y"));
					// INPUT_DATE([3,3,3,7], '900', 'ADD_BIRTH_DATE', date("Y-m-d"));
                    LABEL([3,3,4,5], '700', S_MSG('F105','Email Address'));
                    INPUT('email', [3,3,3,7], '900', 'ADD_PRS_EMAIL', '', '', '', '', 0, '', 'fix');
                    LABEL([3,3,4,5], '700', $cNPWP);
                    INPUT('text', [3,3,3,7], '900', 'ADD_PRS_NPWP', '', '', '', '', 0, '', 'fix');
                    LABEL([3,3,4,5], '700', S_MSG('PA07','No. Rekening'));
                    INPUT('text', [3,3,3,7], '900', 'ADD_PRSON_ACCN');
                    LABEL([3,3,4,5], '700', S_MSG('PA08','Nama Bank'));
                    INPUT('text', [3,3,3,7], '900', 'ADD_PRSON_BANK', '', '', '', '', 0, '', 'fix');
                    LABEL([3,3,4,5], '700', $cBPJS_TK);
                    INPUT('text', [3,3,3,7], '900', 'ADD_BPJS_TK');
                    LABEL([3,3,4,5], '700', $cBPJS_KES);
                    INPUT('text', [3,3,4,7], '900', 'ADD_BPJS_KES', '', '', '', '', 0, '', 'fix');
                    LABEL([3,3,4,5], '700', 'No. KTA Satpam');
                    INPUT('text', [3,3,3,7], '900', 'ADD_NO_KTA', '', '', '', '', 0, '', 'fix');
                    LABEL([3,3,4,5], '700', 'Berlaku s/d');
                    INP_DATE([3,3,3,6], '900', 'ADD_TGL_KTA', date("d/m/Y"));
                    // INPUT('date', [3,3,4,7], '900', 'ADD_TGL_KTA', '', '', '', '', 0, '', 'fix');

                    echo '<div class="controls">';
                    LABEL([3,3,4,5], '700', S_MSG('PB50','Agama'));
					SELECT([2,2,3,5], 'ADD_PRSON_RELG');
                        $qQUERY=OpenTable('TbReligion', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
                        while($aREC_AG_DATA=SYS_FETCH($qQUERY)){
                            echo "<option class='col-sm-7 form-label-900' value='$aREC_AG_DATA[KODE]'  >$aREC_AG_DATA[NAMA]</option>";
                        }
                    echo '</select>';
                eTDIV();
                CLEAR_FIX();
                LABEL([3,3,4,5], '700', S_MSG('PB57','Status'));
                SELECT([2,2,3,5], 'ADD_MARRIAGE');
                    for ($S=1; $S<=2; $S++) {
                        echo "<option value=$S>$aSTATUS[$S]</option>";
                    }
                echo '</select>';
                CLEAR_FIX();
                LABEL([3,3,4,5], '700', 'Gol. Darah');
                SELECT([1,1,2,5], 'ADD_BLOOD_GRUP');
                    for ($I=1; $I<=4; $I++) {
                        echo "<option value=$aGOL_DAR[$I]>$aGOL_DAR[$I]</option>";
                    }
                echo '</select>';
                CLEAR_FIX();
                LABEL([3,3,4,5], '700', S_MSG('PB61','Jml. Anak'));
                INPUT('number', [1,2,3,5], '900', 'ADD_CHILD_HAVE', '', '', '99', '', 2, '', 'fix');
                LABEL([3,3,4,5], '700', S_MSG('PB62','Nama Pasangan'));
                INPUT('text', [3,3,3,7], '900', 'ADD_SPOUSE', '', '', '', '', 0, '', 'fix');
                echo '<br>';
                TDIV();
                    LABEL([3,3,4,5], '700', $cPENDIDIKAN);
                    TABLE('myTable');
                        THEAD(['Tingkat Pendidikan', S_MSG('PA95','Nama Pendidikan'), S_MSG('PA9A','Jurusan'), $cTHN_MASUK, $cTHN_KELUAR, S_MSG('PA98','Keterangan')], '', [], '*');
                        echo '<tbody>';
                            for($I=1; $I<=4; $I++):
                                $J=(string)$I;
                                echo '<tr>';
                                    echo '<td>';
                                        SELECT([12,12,12,12], 'ADD_EDU_CODE'.$J);
                                            echo '<option></option>';
                                            $aREC_PEND1=OpenTable('TbEducation');
                                            while($aREC_PNDDKN1=SYS_FETCH($aREC_PEND1)){
                                                echo "<option value='$aREC_PNDDKN1[EDU_CODE]'  >$aREC_PNDDKN1[EDU_NAME]</option>";
                                            }
                                        echo '</select><br>';
                                    echo '</td>';
                                    INPUT('text', [12,12,12,12], '900', 'ADD_EDU_DESC'.$J, '', '', '', '', 50, '', '', '', '', '', '', '', 'td');
                                    INPUT('text', [12,12,12,12], '900', 'ADD_JURUSAN'.$J, '', '', '', '', 50, '', '', '', '', '', '', '', 'td');
                                    INPUT('number', [12,12,12,12], '900', 'ADD_YEAR_IN'.$J, '', '', '', '', 4, '', '', '', '', '', '', '', 'td');
                                    INPUT('number', [12,12,12,12], '900', 'ADD_YEAR_OUT'.$J, '', '', '', '', 4, '', '', '', '', '', '', '', 'td');
                                    INPUT('text', [12,12,12,12], '900', 'ADD_EDU_NOTE'.$J, '', '', '', '', 40, '', '', '', '', '', '', '', 'td');
                                echo '</tr>';
                            endfor;
                        echo '</tbody>';
                    echo '</table><br>';
                    LABEL([8,8,8,8], '700', 'Pengalaman Kerja');
                    TABLE('myTable');
                        THEAD(['Nama Perusahaan','Bidang usaha', 'Jabatan', $cTHN_MASUK, $cTHN_KELUAR, 'Alasan Keluar'], '', [], '*', [3,2,3,2,2,2]); 
                        echo '<tbody>';
                            for($I=1; $I<=2; $I++):
                                $J=(string)$I;
                                echo '<tr>';
                                    INPUT('text', [12,12,12,12], '900', 'ADD_PK_PRSHN'.$J, '', '', '', '', 50, '', '', '', '', '', '', '', 'td');
                                    INPUT('text', [12,12,12,12], '900', 'ADD_PK_USAHA'.$J, '', '', '', '', 50, '', '', '', '', '', '', '', 'td');
                                    INPUT('text', [12,12,12,12], '900', 'ADD_PK_JAB'.$J, '', '', '', '', 50, '', '', '', '', '', '', '', 'td');
                                    INPUT('number', [12,12,12,12], '900', 'ADD_PK_MSK'.$J, '', '', '', '', 50, '', '', '', '', '', '', '', 'td');
                                    INPUT('number', [12,12,12,12], '900', 'ADD_PK_KLR'.$J, '', '', '', '', 50, '', '', '', '', '', '', '', 'td');
                                    INPUT('text', [12,12,12,12], '900', 'ADD_PK_ALASAN'.$J, '', '', '', '', 50, '', '', '', '', '', '', '', 'td');
                                echo '</tr>';
                            endfor;
                        echo '</tbody>';
                    echo '</table><br>';
                    LABEL([3,3,4,12], '700', 'Pendidikan Non Formal');
                    INPUT('text', [5,5,4,12], '900', 'ADD_NONFML', '', '', '', '', 0, '', 'fix', PH: 'Kursus, pelatihan dll .......');
                    echo '<br>';
                    LABEL([3,3,4,6], '700', 'Jabatan yang dilamar');
                    SELECT([3,4,4,6], 'ADD_JOB');
                        $qQUERY=OpenTable('TbOccupation', "APP_CODE='$cAPP_CODE' and DELETOR='' and PUBLIC=1", '', 'JOB_NAME');
                        while($aREC_JOB=SYS_FETCH($qQUERY)){
                            echo "<option value='$aREC_JOB[JOB_CODE]'  >$aREC_JOB[JOB_NAME]</option>";
                        }
                    echo '</select>';
                    CLEAR_FIX();
                    LABEL([3,3,4,9], '700', 'Tinggi Badan (cm)');
                    INPUT('number', [3,3,3,3], '900', 'ADD_HEIGHT', '', '', '999', '', 3, '', 'fix');
                    LABEL([3,3,4,9], '700', 'Berat Badan (kg)');
                    INPUT('number', [3,3,3,3], '900', 'ADD_WEIGHT', '', '', '999', '', 3, '', 'fix');
                    LABEL([3,3,4,9], '700', 'Ukuran Baju');
                    SELECT([1,2,2,3], 'ADD_UK_BAJU');
                        echo "
                            <option value='S'  >S</option>
                            <option value='M'  >M</option>
                            <option value='L'  >L</option>
                            <option value='XL'  >XL</option>
                            <option value='XXL'  >XXL</option>
                            <option value='3XL'  >3XL</option>
                            <option value='4XL'  >4XL</option>
                    </select>";
                    LABEL([3,3,4,9], '700', 'Ukuran Celana');
                    INPUT('number', [1,3,3,3], '900', 'ADD_CELANA', '', '', '99', '', 2, '', 'fix');
                    LABEL([3,3,4,9], '700', 'Ukuran Sepatu');
                    INPUT('number', [1,3,3,3], '900', 'ADD_SEPATU', '', '', '99', '', 2, '', 'fix');
                    echo '<br>';
                    LABEL([3,3,4,6], '700', S_MSG('PG03','Nomor kartu keluarga'));
                    INPUT('number', [2,3,3,6], '900', 'ADD_NO_KKLRG', '', '', '9999999999999999', '', 16, '', 'fix');
                    LABEL([3,3,4,6], '700', S_MSG('PG05','Nama Kepala Kel'));
                    INPUT('text', [3,3,3,6], '900', 'ADD_NM_KKLRG', '', '', '', '', 50, '', 'fix');
                    echo '<br>';
                    LABEL([3,3,4,6], '700', 'Kartu Keluarga');
                    TABLE('my_example');
                        THEAD([S_MSG('PG11','Nama Lengkap'), 'N I K / N I K S', $cGENDER, $cTMP_LAHIR, $cTGL_LAHIR, 'A g a m a', 'P e n d i d i k a n', S_MSG('PA41','Pekerjaan'), 'Status Perkawinan', 'Status dalam keluarga', S_MSG('PG16','Kewarganegaraan'), 'Nama Ayah', 'Nama Ibu'], '', [], '*',
                            [2,2,1,1,2,2,2,2,2,2,2,2,2]); 
                            echo '<tbody>';
                                echo '<tr>';
                                    INPUT('text', [12,12,12,12], '900', 'ADD_FULL_NAME1', '', '', '', '', 30, _17TD : 'td', _WIDTH:'200');
                                    INPUT('number', [12,12,12,12], '900', 'ADD_KK_N_I_K1', '', '', '9999999999999999', '', 16, '', 'fix', _17TD : 'td', _WIDTH:'200');
                                    RADIO('ADD_KK_GENDER1', [1,2], [true, false], [$cLBL_PRIA, $cLBL_WANITA], '', 'td');
                                    INPUT('text', [12,12,12,12], '900', 'ADD_KK_BIRTH_PLCE1', '', '', '', '', 30, _17TD : 'td', _WIDTH:'200');
                                    // INP_DATE([12,12,12,12], '900', 'ADD_KK_BIRTH_DATE1', date('d/m/Y'), _cTD:'td');
                                    echo '<td><input type="date" name="ADD_KK_BIRTH_DATE1" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" data-mask="date" value=<?php echo date("d/m/Y")?>></td>';
                                    ?>
                                                        <td>
															<select name='ADD_KK_RELI_GION1' class="col-lg-12 col-sm-12 col-xs-12 form-label-900 m-bot15" style="width:200px;">
																<?php 
																$qQUERY=OpenTable('TbReligion', "APP_CODE='$cAPP_CODE' and REC_ID not in ( select DEL_ID from logs_delete)");
																	echo "<option value=' '  > </option>";
																	while($aREC_PRS_RLGN=SYS_FETCH($qQUERY)){
																		echo "<option class='form-label-900' value='$aREC_PRS_RLGN[KODE]'  >$aREC_PRS_RLGN[NAMA]</option>";
																	}
																?>
															</select>
														</td>
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
														<td><input type="text" name='ADD_KK_JOB1' class="col-lg-12 col-sm-12 col-xs-12 form-label-900" style="width:200px;"></td>

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
														<td><input type="text" name='ADD_KK_STA_TUS1' class="col-lg-12 col-sm-12 col-xs-12 form-label-900" maxlength="30"></td>
														<td><input type="text" name='ADD_KK_CITI_ZEN1' class="col-lg-12 col-sm-12 col-xs-12 form-label-900" maxlength="20"></td>
														<td><input type="text" name='ADD_FATH_NAME1' class="col-lg-12 col-sm-12 col-xs-12 form-label-900" style="width:200px;" maxlength="50"></td>
														<td><input type="text" name='ADD_MOTH_NAME1' class="col-lg-12 col-sm-12 col-xs-12 form-label-900" style="width:200px;" maxlength="50"></td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_FULL_NAME2'></td>
														<td><input type="number" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_KK_N_I_K2' data-mask="9999999999999999"></td>
														<td>
                                                            <input type="radio" name="ADD_KK_GENDER2" value=1 "checked" /> <?php echo $cLBL_PRIA?>
															<input type="radio" name="ADD_KK_GENDER2" value=2 /> <?php echo $cLBL_WANITA?>
                                                        </td>
														<td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_KK_BIRTH_PLCE2'></td>
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
														<td><input type="text" name='ADD_KK_JOB2' class="col-lg-12 col-sm-12 col-xs-12 form-label-900"></td>

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
														<td><input type="text" name='ADD_KK_STA_TUS2' class="col-lg-12 col-sm-12 col-xs-12 form-label-900" maxlength="30"></td>
														<td><input type="text" name='ADD_KK_CITI_ZEN2' class="col-lg-12 col-sm-12 col-xs-12 form-label-900" maxlength="20"></td>
														<td><input type="text" name='ADD_FATH_NAME2' class="col-lg-12 col-sm-12 col-xs-12 form-label-900" maxlength="50"></td>
														<td><input type="text" name='ADD_MOTH_NAME2' class="col-lg-12 col-sm-12 col-xs-12 form-label-900" maxlength="50"></td>
                                                    </tr>

                                                    <tr>
                                                        <td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_FULL_NAME3'></td>
														<td><input type="number" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_KK_N_I_K3' data-mask="9999999999999999"></td>
														<td>
                                                            <input type="radio" name="ADD_KK_GENDER3" value=1 "checked" /> <?php echo $cLBL_PRIA?>
															<input type="radio" name="ADD_KK_GENDER3" value=2 /> <?php echo $cLBL_WANITA?>
                                                        </td>
														<td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_KK_BIRTH_PLCE3'></td>
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
														<td><input type="text" name='ADD_KK_JOB3' class="col-lg-12 col-sm-12 col-xs-12 form-label-900"></td>

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
														<td><input type="text" name='ADD_KK_STA_TUS3' class="col-lg-12 col-sm-12 col-xs-12 form-label-900" maxlength="30"></td>
														<td><input type="text" name='ADD_KK_CITI_ZEN3' class="col-lg-12 col-sm-12 col-xs-12 form-label-900" maxlength="20"></td>
														<td><input type="text" name='ADD_FATH_NAME3' class="col-lg-12 col-sm-12 col-xs-12 form-label-900" maxlength="50"></td>
														<td><input type="text" name='ADD_MOTH_NAME3' class="col-lg-12 col-sm-12 col-xs-12 form-label-900" maxlength="50"></td>
                                                    </tr>

                                                    <tr>
                                                        <td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_FULL_NAME4'></td>
														<td><input type="number" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_KK_N_I_K4' data-mask="9999999999999999"></td>
														<td>
                                                            <input type="radio" name="ADD_KK_GENDER4" value=1 "checked" /> <?php echo $cLBL_PRIA?>
															<input type="radio" name="ADD_KK_GENDER4" value=2 /> <?php echo $cLBL_WANITA?>
                                                        </td>
														<td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_KK_BIRTH_PLCE4'></td>
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
														<td><input type="text" name='ADD_KK_JOB4' class="col-lg-12 col-sm-12 col-xs-12 form-label-900"></td>

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
														<td><input type="text" name='ADD_KK_STA_TUS4' class="col-lg-12 col-sm-12 col-xs-12 form-label-900" maxlength="30"></td>
														<td><input type="text" name='ADD_KK_CITI_ZEN4' class="col-lg-12 col-sm-12 col-xs-12 form-label-900" maxlength="30"></td>
														<td><input type="text" name='ADD_FATH_NAME4' class="col-lg-12 col-sm-12 col-xs-12 form-label-900" maxlength="50"></td>
														<td><input type="text" name='ADD_MOTH_NAME4' class="col-lg-12 col-sm-12 col-xs-12 form-label-900" maxlength="50"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
											<div class="clearfix"></div>

                                            <label class="col-lg-3 col-sm-3 col-xs-5 form-label-500" for="field-14">Keahlian</label>
                                            <table cellspacing="0" id="tech-companies-1" class="table table-small-font table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th style="background-color:LightGray;"><?php echo S_MSG('PG53','Nama Keahlian')?></th>
                                                        <th style="background-color:LightGray;"><?php echo S_MSG('PA98','Keterangan')?></th>
                                                        <th style="background-color:LightGray;"><?php echo S_MSG('PG67','Tahun')?></th>
                                                        <th style="background-color:LightGray;"><?php echo S_MSG('PG68','No. Sertifikat')?></th>
                                                        <th style="background-color:LightGray;"><?php echo S_MSG('F019','Catatan')?></th>
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
                                                        <td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_SKILL_DESC'></td>
                                                        <td><input type="number" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_YEAR_SKILL'></td>
                                                        <td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_SKILL_SERT'></td>
                                                        <td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_SKILL_NOTE'></td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <label class="col-lg-3 col-sm-3 col-xs-12 form-label-500" for="field-14">Data Orang Tua</label>
                                            <table cellspacing="0" id="tech-companies-1" class="table table-small-font table-bordered table-striped">
                                                <?php THEAD(['*', 'Nama Orang Tua', $cTMP_LAHIR, $cTGL_LAHIR, 'Pekerjaan', 'Alamat', 'Telpon'], '', [], '*'); ?> 
                                               
                                                <tbody>
                                                    <tr>
                                                        <td><label class="col-lg-3 col-sm-3 col-xs-12 form-label-500">Bapak</label></td>
                                                        <td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_NAMA_BPK' style="width:200px;"></td>
                                                        <td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_TMP_LAHIR_BPK' style="width:200px;"></td>
                                                        <td><input type="date" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_TGL_LAHIR_BPK'></td>
                                                        <td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_PEKERJAAN_BPK' style="width:200px;"></td>
                                                        <td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_ALAMAT_BPK' style="width:200px;"></td>
                                                        <td><input type="number" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_TELPON_BPK' style="width:200px;"></td>
                                                    </tr>
                                                    <tr>
                                                        <td><label class="col-lg-3 col-sm-3 col-xs-12 form-label-500">Ibu</label></td>
                                                        <td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_NAMA_IBU' style="width:200px;"></td>
                                                        <td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_TMP_LAHIR_IBU' style="width:200px;"></td>
                                                        <td><input type="date" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_TGL_LAHIR_IBU'></td>
                                                        <td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_PEKERJAAN_IBU' style="width:200px;"></td>
                                                        <td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_ALAMAT_IBU' style="width:200px;"></td>
                                                        <td><input type="number" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_TELPON_IBU' style="width:200px;"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
										    <div class="clearfix"></div><br>
                                            <label class="col-lg-6 col-sm-6 col-xs-12 form-label-500" for="field-14"><?php echo S_MSG('PH72','Data kontak darurat yang bisa dihubungi')?></label>
                                            <table cellspacing="0" id="tech-companies-1" class="table table-small-font table-bordered table-striped">
                                                <?php THEAD([S_MSG('PH73','Nama kontak'), S_MSG('PH74','No. Telpon'), S_MSG('PH75','No. HP'), S_MSG('PH76','No. Telp. Ktr.'), S_MSG('PH77','Hubungan'), S_MSG('F019','Catatan')], '', [], '*'); ?> 
                                                <tbody>
                                                    <tr>
                                                        <td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_NAMA_KONTAK1' style="width:200px;"></td>
                                                        <td><input type="number" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_TELP_KONTAK1' style="width:200px;"></td>
                                                        <td><input type="number" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_HP_KONTAK1' style="width:200px;"></td>
                                                        <td><input type="number" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_KTR_KONTAK1' style="width:200px;"></td>
                                                        <td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_HUB_KONTAK1' style="width:200px;"></td>
                                                        <td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_NOTE_KONTAK1' style="width:200px;"></td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_NAMA_KONTAK2'></td>
                                                        <td><input type="number" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_TELP_KONTAK2'></td>
                                                        <td><input type="number" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_HP_KONTAK2'></td>
                                                        <td><input type="number" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_KTR_KONTAK2'></td>
                                                        <td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_HUB_KONTAK2'></td>
                                                        <td><input type="text" class="col-lg-12 col-sm-12 col-xs-12 form-label-900" name='ADD_NOTE_KONTAK2'></td>
                                                    </tr>
                                                </tbody>
                                            </table>

										    <br><div class="clearfix"></div>
                                        </div>
                                        <label class="col-lg-3 col-sm-3 col-xs-12 form-label-500" for="field-14">Penyakit yang pernah diderita</label>
                                        <input type="text" class="col-lg-5 col-sm-4 col-xs-12 form-label-900" name="ADD_SICK">
                                        <div class="clearfix"></div><br>
                                        <label class="col-lg-3 col-sm-3 col-xs-9 form-label-500" for="field-14">Pernah di rawat di rumah sakit ( hari )</label>
                                        <input type="number" class="col-lg-1 col-sm-4 col-xs-3 form-label-900" name="ADD_RAWAT" data-mask="999">
                                        <div class="clearfix"></div><br>
                                        <div class="form-group">
                                            <label class="form-label" for="field-1">Foto setengah badan</label>
                                            <!-- <span class="desc"></span>																	 -->
                                            <!-- <img class="img-responsive" src="<?php //echo $cFILE_FOTO?>" alt="" style="max-width:220px;"> -->
                                            <div class="controls">
                                                <input name="FOTOIMG" type="file" class="form-control">
                                            </div>
                                        </div>
<?php
				SAVE('submit');
			eTDIV();
        eTFORM();
    END_WINDOW();
    // SYS_DB_CLOSE($DB1);	
	break;

    case 'addNew':
        //============BAGIAN VALIDASI DATA========================

        if($_POST['ADD_PRSON_NAME']==''){
            MSG_INFO('Nama tidak boleh kosong');
            return;
        }
        if($_POST['ADD_PRSON_GEND']==0){
            MSG_INFO('Gender tidak boleh kosong');
            return;
        }

        // if($_POST['ADD_ADDRESS']==''){
        //     MSG_INFO('Alamat tidak boleh kosong');
        //     return;
        // }
        $cDT_BIRTH=$_POST['ADD_BIRTH_DATE'];
        if($cDT_BIRTH==''){
            MSG_INFO('Tanggal lahir belum diisi');
            return;
        }
        $cDT_BIRTH=DMY_YMD($cDT_BIRTH);
        $nAGE=(strtotime(date('Y-m-d')) - strtotime($cDT_BIRTH))/(60*60*24*365);
        if($nAGE<10){
            MSG_INFO('Tanggal lahir belum benar');
            return;
        }

        // Handle photo upload if a file was submitted
        if(isset($_FILES['FOTOIMG']) && $_FILES['FOTOIMG']['error'] != UPLOAD_ERR_NO_FILE) {
            // Ensure target directory exists
            if(!file_exists($cFOLDER_IMG)) {
                mkdir($cFOLDER_IMG, 0755, true);
            }
            
            // Process and upload the image
            $upload_result = upload_file($cFILE_FOTO, 'FOTOIMG');
            
            if(!$upload_result['success']) {
                MSG_INFO('Foto gagal diupload: ' . $upload_result['message'] . 
                    '<br>Pastikan ukuran file tidak terlalu besar dan format file adalah JPG, JPEG, atau PNG. ' .
                    'Jika masalah berlanjut, hubungi administrator sistem.');
                print_r2('File upload error: ' . $upload_result['message'] . ' (code: ' . $upload_result['code'] . ')');

                return;
            }
            
            // Store image path in database
            // RecCreate('PrsonImage', [
            //     'PRSON_CODE', 
            //     'IMAGE_PATH', 
            //     'ENTRY', 
            //     'APP_CODE', 
            //     'REC_ID'
            // ], [
            //     $cPERSON_CODE, 
            //     $cFILE_IMAGE, 
            //     $cUSERCODE, 
            //     $cAPP_CODE, 
            //     NowMSecs()
            // ]);
        } else {
            // No photo uploaded, continue without it
            // If you want to make photo mandatory, uncomment the following:
            MSG_INFO('Foto belum dipilih');
            return;
        }

        //============BAGIAN VALIDASI DATA========================

        $nAGAMA		= 1;
        if ($_POST['ADD_PRSON_RELG']!='') $nAGAMA	= $_POST['ADD_PRSON_RELG'];
        RecCreate('People', ['PEOPLE_CODE', 'PEOPLE_NAME', 'ENTRY', 'DATE_ENTRY', 'APP_CODE'],
            [$cPERSON_CODE, ENCODE($_POST['ADD_PRSON_NAME']), $cUSERCODE, date("Y-m-d H:i:s"), $cAPP_CODE]);

        if($_POST['ADD_NICK_NAME']!=''){
            RecCreate('PeopleNickName', ['PRSON_CODE', 'NICK_NAME', 'ENTRY', 'APP_CODE', 'REC_ID'], [$cPERSON_CODE, ENCODE($_POST['ADD_NICK_NAME']), $cUSERCODE, $cAPP_CODE, NowMSecs()]);
        }
        RecCreate('PersonMain', ['PRSON_CODE', 'PRSON_GEND', 'PRSN_RT', 'PRSN_RW', 'PRS_PHN','PRS_KTP', 'BIRTH_PLC', 'BIRTH_DATE', 'PRSON_ACCN', 'PRSON_BANK', 'PRSON_RELG', 'MARRIAGE', 'PRSON_SLRY', 'ENTRY', 'DATE_ENTRY', 'APP_CODE'],
            [$cPERSON_CODE, $_POST['ADD_PRSON_GEND'], $_POST['ADD_RT'], $_POST['ADD_RW'], $_POST['ADD_HAND_PHN'], $_POST['ADD_PRS_KTP'], ENCODE($_POST['ADD_BIRTH_PLC']),  $cDT_BIRTH, $_POST['ADD_PRSON_ACCN'], $_POST['ADD_PRSON_BANK'], $nAGAMA, $_POST['ADD_MARRIAGE'], 2, $cUSERCODE, date("Y-m-d H:i:s"), $cAPP_CODE]);

        $cJABAT_AN=($_POST['ADD_JOB'] ? $_POST['ADD_JOB'] : '');
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
            RecCreate('PeopleEMail', ['PEOPLE_CODE', 'PPL_EMAIL', 'ENTRY', 'APP_CODE', 'REC_ID'], [$cPERSON_CODE, $cEMAIL, $cUSERCODE, $cAPP_CODE, NowMSecs()]);
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
        $cVLD=($_POST['ADD_TGL_KTA'] == '' ? '0000-00-00' : DMY_YMD($_POST['ADD_TGL_KTA']));
        if ($cKTA!=''){
            RecCreate('PrsMemberCard', ['PERSON_CODE', 'CARD_NUMBER', 'VALID_UNTIL', 'ENTRY', 'APP_CODE', 'REC_ID'], [$cPERSON_CODE, $cKTA, $cVLD, $cUSERCODE, $cAPP_CODE, NowMSecs()]);
        }

		for($I=1; $I<=4; $I++):
            $J=(string)$I;
            $K='ADD_EDU_CODE'.$J;
            $cEDUCAT=$_POST[$K];
            $nYIN=(integer)$_POST['ADD_YEAR_IN'.$J];
            if(!$nYIN)		$nYIN=0;
            $nYOT=(integer)$_POST['ADD_YEAR_OUT'.$J];
            if(!$nYOT)		$nYOT=0;
            if ($cEDUCAT!=''){
                $cREC_ID=NowMSecs();
                $nREC_ID=intval($cREC_ID)+$I;
                $cREC_ID=(string)$nREC_ID;
                RecCreate('PrsEducation', ['PRSON_CODE', 'EDU_CODE', 'EDU_DESC', 'EDU_JRSN', 'YEAR_IN', 'YEAR_OUT', 'EDU_NOTE', 'ENTRY', 'APP_CODE', 'REC_ID'],
                    [$cPERSON_CODE, $cEDUCAT, ENCODE($_POST['ADD_EDU_DESC'.$J]), ENCODE($_POST['ADD_JURUSAN'.$J]), $nYIN, $nYOT, ENCODE($_POST['ADD_EDU_NOTE'.$J]), $cUSERCODE, $cAPP_CODE, $cREC_ID]);
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
            $cSTART= $_POST['ADD_PK_MSK1'];
            $nSTART = ($cSTART ? (integer)$cSTART : 0);
            $cFINISH = $_POST['ADD_PK_KLR1'];
            $nFINISH = ($cFINISH ? (integer)$cFINISH : 0);
            RecCreate('PrsExperience', ['PRSON_CODE', 'COMPANY', 'BUSINESS', 'OCCUPATION', 'START_YEAR', 'FINISH_YEAR', 'OUT_REASON', 'ENTRY', 'APP_CODE', 'REC_ID'],
                [$cPERSON_CODE, $cPENGALAMAN1, $_POST['ADD_PK_USAHA1'], $_POST['ADD_PK_JAB1'], $nSTART, $nFINISH, $_POST['ADD_PK_ALASAN1'], $cUSERCODE, $cAPP_CODE, $cREC_ID]);
        }
        $cPENGALAMAN2=ENCODE($_POST['ADD_PK_PRSHN2']);
        if ($cPENGALAMAN2!=''){
            $nRec_id = date_create()->format('Uv');
            $cREC_ID = (string)$nRec_id+2;
            $cFINISH = $_POST['ADD_PK_KLR2'];
            $nFINISH = ($cFINISH ? (integer)$cFINISH : 0);
            RecCreate('PrsExperience', ['PRSON_CODE', 'COMPANY', 'BUSINESS', 'OCCUPATION', 'START_YEAR', 'FINISH_YEAR', 'OUT_REASON', 'ENTRY', 'APP_CODE', 'REC_ID'],
                [$cPERSON_CODE, $cPENGALAMAN2, ENCODE($_POST['ADD_PK_USAHA2']), ENCODE($_POST['ADD_PK_JAB2']), $_POST['ADD_PK_MSK2'], $nFINISH, ENCODE($_POST['ADD_PK_ALASAN1']), $cUSERCODE, $cAPP_CODE, $cREC_ID]);
        }

        $cSKILL=$_POST['ADD_SKILL_CODE'];
        $cYEARL=$_POST['ADD_YEAR_SKILL'];
        if ($cSKILL!=''){
            RecCreate('PrsSkill', ['PRSON_CODE', 'SKILL_CODE', 'SKILL_DESC', 'YEAR_SKILL', 'SKILL_SERT', 'SKILL_NOTE', 'ENTRY', 'APP_CODE', 'REC_ID'],
                [$cPERSON_CODE, $cSKILL, $_POST['ADD_SKILL_DESC'], ($cYEARL ? (integer)$cYEARL : 0), $_POST['ADD_SKILL_SERT'], 
                ENCODE($_POST['ADD_SKILL_NOTE']), $cUSERCODE, $cAPP_CODE, NowMSecs()]);
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
            $nHEIGHT=(integer)$cHEIGHT;
            $nWEIGT=(integer)$_POST['ADD_WEIGHT'];
            $nSHIRT=(integer)$_POST['ADD_UK_BAJU'];
            $nPANT=(integer)$_POST['ADD_CELANA'];
            $nSHOE=(integer)$_POST['ADD_SEPATU'];
            RecCreate('PrsSize', ['PRSON_CODE', 'PRS_HEIGHT', 'PRS_WEIGHT', 'PRS_SHIRT', 'PRS_SHOE', 'PRS_PDL', 'ENTRY', 'APP_CODE', 'REC_ID'],
                [$cPERSON_CODE, $nHEIGHT, $nWEIGT, $nSHIRT, $nSHOE, $nPANT, $cUSERCODE, $cAPP_CODE, NowMSecs()]);
        }

        $cNO_KK=$_POST['ADD_NO_KKLRG'];
        if ($cNO_KK!=''){
            RecCreate('PrsFamCardHdr', ['PRSON_CODE', 'NO_KKLRG', 'NM_KKLRG', 'ENTRY', 'APP_CODE', 'REC_ID'],
                [$cPERSON_CODE, $cNO_KK, ENCODE($_POST['ADD_NM_KKLRG']), $cUSERCODE, $cAPP_CODE, NowMSecs()]);
        }
        $nLOOP=0;
        for ($I = 1; $I < 5; $I++) {
            $J=(string)$I;
            $cKK=$_POST['ADD_FULL_NAME'.$J];
            if ($cKK!='') {
                $cNIK=substr($_POST['ADD_KK_N_I_K'.$J],0,16);
                $cB_PLACE='';
                $nGENDER = ( $_POST['ADD_KK_GENDER'.$J]!='' ? $_POST['ADD_KK_GENDER'.$J] : 1);
                $cB_PLACE= ( $_POST['ADD_KK_BIRTH_PLCE'.$J]=='' ? '' : ENCODE($_POST['ADD_KK_BIRTH_PLCE'.$J]));
                $cBIRTH=$_POST['ADD_KK_BIRTH_DATE'.$J];
                if(!$cBIRTH)		$cBIRTH='0000-00-00';
                else $cBIRTH=DMY_YMD($cBIRTH);
                $nMAR=(integer)$_POST['ADD_KK_MAR_STATUS'.$J];
                while($nLOOP<10) {
                    $nLOOP++;
                    $nRec_id = date_create()->format('Uv')+$nLOOP;
                    $cREC_ID = (string)$nRec_id;
                    $aFLD = ['PRSON_CODE', 'FULL_NAME', 'N_I_K', 'GENDER', 'BIRTH_PLCE', 'BIRTH_DATE', 'RELI_GION', 'EDUCATE', 'J_O_B', 'STATUS_MAR', 'STA_TUS', 'CITI_ZEN', 'FATH_NAME', 'MOTH_NAME', 'ENTRY', 'APP_CODE', 'REC_ID'];
                    $lCREATE=RecCreate('PrsFamCardDtl', $aFLD,
                        [$cPERSON_CODE, ENCODE($cKK), $cNIK, $nGENDER, $cB_PLACE, $cBIRTH, 
                        $_POST['ADD_KK_RELI_GION'.$J], $_POST['ADD_KK_EDUCATE'.$J], $_POST['ADD_KK_JOB'.$J], 
                        $nMAR, $_POST['ADD_KK_STA_TUS'.$J], $_POST['ADD_KK_CITI_ZEN'.$J], 
                        ENCODE($_POST['ADD_FATH_NAME'.$J]), ENCODE($_POST['ADD_MOTH_NAME'.$J]), $cUSERCODE, $cAPP_CODE, $cREC_ID]);
                    if($lCREATE=='1')    $nLOOP+=10;
                }
            }
        }
    
        // return "Terimakasih. Data Anda sudah di rekam di sistem kami. <script> alert('Terimakasih. Data Anda sudah di rekam di sistem kami.');	</script>";
        // header('location:https://fahlevi.co/');
        header('location:app_finish.php');
        // SYS_DB_CLOSE($DB1);
        break;

}
?>
