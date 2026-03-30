<?php
	session_start();
    include "sysfunction.php";
    $cAPP_CODE  = $_SESSION['data_FILTER_CODE'] = $_GET['_app'];
    $cHEADER    = 'Buat Laporan';

    $cACTION= (isset($_GET['_a']) ? $cACTION=$_GET['_a'] : '');
    $cDEVICE = (isset($_GET['_dev']) ? $_GET['_dev'] : '');
    $cPRS_CODE = (isset($_GET['_prs']) ? $cPRS_CODE=$_GET['_prs'] : '');
    $cAPP_CODE=$_GET['_app'];
    // switch($cACTION){
    //     default:
            // FE_WINDOW($cHEADER)
?>
<!DOCTYPE html>
<html>
<title>Rainbow Sys : LAPORAN</title>
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <title>Rainbow Ext</title>
    <!-- <script src="assets/js/dropzone-min.js"></script>
    <link rel="stylesheet" href="assets/css/dropzone.css" rel="stylesheet" type="text/css" /> -->
    <script src="assets/js/jquery-3.7.0.min.js" type="text/javascript"></script> 
    
    <!-- CORE CSS FRAMEWORK - -->
    <!-- <link href="assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen"/>	 -->
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <!-- <link href="assets/plugins/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/> -->

    <!-- Add the v6 core styles and then select the individual styles you need, like Solid and Brands -->
    <link href="assets/fonts/font-awesome/css/fontawesome.css" rel="stylesheet" />
    <!-- <link href="assets/fonts/font-awesome/css/brands.css" rel="stylesheet" /> -->
    <link href="assets/fonts/font-awesome/css/solid.css" rel="stylesheet" />

    <!-- <link href="assets/css/animate.min.css" rel="stylesheet" type="text/css"/> -->
    <!-- <link href="assets/plugins/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" type="text/css"/> -->
    <link href="assets/css/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>
    <link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <?php require_once("fe_topbar.php"); ?>
    <div class="page-container row-fluid">
    <section class="wrapper main-wrapper">
        <div class="col-lg-12">
            <section class="box nobox">
                <div class="content-body">    
                    <div class="row">
                        <!-- <form action ="?_a=save&_dev=< ?php//$cDEVICE?>&_prs=< ?php//$cPRS_CODE?>&_app=< ?php//$cAPP_CODE?>" method="post" enctype="multipart/form-data" > -->
                            <!-- <form action ="fe_report_dzup.php?_dev=< ?=$cDEVICE?>&_prs=< ?=$cPRS_CODE?>&_app=< ?=$cAPP_CODE?>" method="POST" enctype="multipart/form-data" > -->
                                <!-- <form id="dropzone-form" action="rainbow_ext.php?q=< ?php echo $cDEVICE ?>" method="POST" enctype="multipart/form-data"> -->
                                <form id="dropzone-form" action="" method="POST" enctype="multipart/form-data">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                <!-- RecCreate('PrsReport', ['PRSON_CODE', 'REP_TIME', 'REP_CONTENT', 'ENTRY', 'APP_CODE'], 
                                [$cPRS_CODE, date("Y-m-d H:i:s"), $cREPORT, $cPRS_CODE, $cAPP_CODE]); -->
                                <!-- 'PRSON_CODE', 'REP_TIME', 'REP_CONTENT', 'ENTRY', 'APP_CODE' -->
                                <input type="hidden" id="F_DEVICE" name="F_DEVICE" value="<?=$cDEVICE?>">
                                <input type="hidden" id="F_PRSON_CODE" name="F_PRSON_CODE" value="<?=$cPRS_CODE?>">
                                <input type="hidden" id="F_REP_TIME" name="F_REP_TIME" value="<?=date("Y-m-d H:i:s")?>">
                                <input type="hidden" id="F_ENTRY" name="F_ENTRY" value="<?=$cPRS_CODE?>">
                                <input type="hidden" id="F_APP_CODE" name="F_APP_CODE" value="<?=$cAPP_CODE?>">
                                <div class="form-group">
                                    <div class="controls">
                                        <!-- <textarea id="F_REP_CONTENT" name='ADD_REPORT' class="form-control autogrow" id="field-7" placeholder="Deskripsi Laporan ........" style="height:200px;"></textarea> -->
                                        <textarea id="F_REP_CONTENT" name='F_REP_CONTENT' class="form-control autogrow" id="field-7" placeholder="Deskripsi Laporan ........" style="height:200px;"></textarea>
                                    </div>
                                </div>
                                <!-- <div class="dropzone dropzone-previews" id="my-dropzone"> -->
                                    <!-- <input type="file" name="file" multiple/> -->
                                    <!-- <form action="fe_report_dzup.php" class="dropzone" id="my-dropzone"></form> -->
                                <!-- </div> -->
                                <!-- <div id="dZUpload" class="dropzone">
                                    <div class="dz-default dz-message" data-dz-message>
                                        <h1>Tambahkan Foto Laporan (Opsional)</h1><br>
                                        <h2>Maksimal 3 Foto</h2>
                                    </div>
                                </div> -->
                                <div class="dropzone form-group" id="dropzone">
                                    <div class="dz-default dz-message" data-dz-message>
                                        <span>
                                        <i class="fa fa-cloud-upload" style="font-size:50px"></i>
                                        <h2>Tambahkan Foto Laporan (Opsional)</h2><br>
                                        <h3>Maksimal 3 Foto</h3>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="enter_post_btns col-md-12 col-sm-12 col-xs-12">
                                    <!-- <input type="submit" class="btn btn-primary" value="Submit" id="submitbtn"> -->
                                    <input id="submit-dropzone" class="btn btn-primary" type="submit" name="submitDropzone" value="Submit" />
                                    <!-- <input type="submit" name="submit" id="submit-dropzone" class="btn btn-primary" value="Submit"/> -->
                                    <input type="button" class="btn" value="Cancel" onclick=self.history.back()>
                                    <!-- <a href="#" class="btn btn-md pull-right btn-link"><i class="fa-image"></i></a> -->
                                    <!-- <a href="#" class="btn btn-md pull-right btn-link"><i class="fa-map-marker"></i></a> -->
                                </div>
                                
                                <div class="clearfix"></div>

                            </div>
                            
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </section>
</div>
<script>
// $(document).ready(function(){
    // Note that the name "myDropzone" is the camelized
    // id of the form.
    //   Dropzone.autoDiscover = false;
    //   Dropzone.options.myDropzone = {
    //     // Configuration options go here
    //     autoProcessQueue: false,
    //     capture:'camera',
    //     url: '#',
    //     previewsContainer: ".dropzone-previews",
    //     uploadMultiple: true,
    //     parallelUploads: 3,
    //     maxFiles: 3
    //   };
    // });
    // $(document).ready(function () {
    //     Dropzone.autoDiscover = false;
    //     $("#dZUpload").dropzone({
    //         autoProcessQueue: false,
    //         capture:'camera',
    //         url: '#',
    //         previewsContainer: ".dropzone-previews",
    //         uploadMultiple: true,
    //         parallelUploads: 3,
    //         maxFiles: 3,
    //         success: function (file, response) {
    //             var imgName = response;
    //             file.previewElement.classList.add("dz-success");
    //             console.log("Successfully uploaded :" + imgName);
    //         },
    //         error: function (file, response) {
    //             file.previewElement.classList.add("dz-error");
    //         }
    //     });
// });

// $(document).ready(function () {
    Dropzone.autoDiscover = false;
    var myDropzone = new Dropzone("#dropzone", {
        url: "fe_report_dzup.php",
        method: "POST",
        paramName: "file",
        autoProcessQueue: false,
        resizeWidth: 500, resizeHeight: 500,
        resizeMethod: 'contain', resizeQuality: 0.6,
        maxFilesize: 10, //max ukuran file 10 MB
        capture:'camera',
        uploadMultiple: true,
        parallelUploads: 3,
        maxFiles: 3,  // Maksimal file 3 buah
        addRemoveLinks: true,
        dictRemoveFile: 'Hapus foto',
        dictInvalidFileType: "Tipe file salah (hanya jpg dan png yang diperbolehkan).",
        dictCancelUpload: "Batal upload",
        dictCancelUploadConfirmation: "Konfirmasi?",
        dictMaxFilesExceeded: "Maksimal upload 3 foto",
        acceptedFiles: ".jpeg,.jpg,.png"
        // success: function (file, response) {
        //     var imgName = response;
        //     file.previewElement.classList.add("dz-success");
        //     console.log("Berhasil upload :" + imgName);
        // },
        // error: function (file, response) {
        //     file.previewElement.classList.add("dz-error");
        // }
    });
    // $("#submitbtn").click(function (e) {
    //     e.preventDefault();
    //     myDropzone.processQueue();
    // });
    
    // myDropzone.on("sending", function(file, xhr, formData) {
    //         // Will send the filesize along with the file as POST data.
    //     formData.append("filesize", file.size);
    // });
    
myDropzone.on("addedfile", function(file) {
    //console.log(file);
});

myDropzone.on("removedfile", function(file) {
    // console.log(file);
});
    
// Add mmore data to send along with the file as POST data. (optional)
myDropzone.on("sending", function(file, xhr, formData) {
    formData.append("dropzone", "1"); // $_POST["dropzone"] = 1
    formData.append("F_DEVICE", document.getElementById("F_DEVICE").value);  // $_POST["F_DEVICE"]
    formData.append("F_PRSON_CODE", document.getElementById("F_PRSON_CODE").value);  // $_POST["F_PRSON_CODE"]
    formData.append("F_REP_TIME", document.getElementById("F_REP_TIME").value);  
    formData.append("F_REP_CONTENT", document.getElementById("F_REP_CONTENT").value);  
    formData.append("F_ENTRY", document.getElementById("F_ENTRY").value);  // $_POST["F_ENTRY"]
    formData.append("F_APP_CODE", document.getElementById("F_APP_CODE").value);  
});
// PRSON_CODE', 'REP_TIME', 'REP_CONTENT', 'ENTRY', 'APP_CODE

myDropzone.on("error", function(file, response) {
    console.log(response);
});

// on success
myDropzone.on("successmultiple", function(file, response) {
    // get response from successful ajax request
    console.log(response);
    // submit the form after images upload
    // (if u want yo submit rest of the inputs in the form)
    document.getElementById("dropzone-form").submit();
});


// button trigger for processingQueue
var submitDropzone = document.getElementById("submit-dropzone");
submitDropzone.addEventListener("click", function(e) {
    // Make sure that the form isn't actually being sent.
    e.preventDefault();
    e.stopPropagation();

    if (myDropzone.files != "") {
        console.log(myDropzone.files);
        myDropzone.processQueue();
        // document.getElementById("dropzone-form").submit();
    } else {
	    // if no file submit the form    
        // myDropzone.processQueue();
        document.getElementById("dropzone-form").submit();
    }

});

    
// });
</script>
<?php
        //eFE_WINDOW();
        // break;

    // case "save":
    //     $cREPORT = ENCODE($_POST['ADD_REPORT']);
    //     $cPRS_CODE = $_GET['_prs'];
    //     if($cREPORT==''){
    //         MSG_INFO('Laporan masih kosong');
    //         return;
    //     }

        // $storeFolder = 'villas-images';
        // $encpt_data = rand(1000,5000);

        // $datetime = new DateTime($aREC_PERSON['REP_TIME']);
        // $date = $datetime->format('_Y-m-d H_i');
        
        // //relative path untuk mengecek file_exists di server
        // $img_path1 = '../www/images/report/'
        // .$cAPP_CODE.'_ACTIVITY_'
        // .$aREC_PERSON['PRSON_CODE']

        // if (!empty($_FILES)) {  
        //     $tmpFile = $_FILES['file']['tmp_name'];  
        //     $filename = '../www/images/report/'.time().'-'. $_FILES['file']['name'];  
        //     move_uploaded_file($tmpFile,$filename);  
        // }  

        //prs_report table
		// RecCreate('PrsReport', ['PRSON_CODE', 'REP_TIME', 'REP_CONTENT', 'ENTRY', 'APP_CODE'], [$cPRS_CODE, date("Y-m-d H:i:s"), $cREPORT, $cPRS_CODE, $cAPP_CODE]);

//        echo "<script> window.history.back();	</script>";
		// header('location:rainbow_ext.php?q='.$cDEVICE);

        // break;
//}
?>
