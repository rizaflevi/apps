<?php

$_SESSION['gSCR_HEADER'] = 'User tidak dikenal';

?>

<!DOCTYPE html>
<html class=" ">
	<?php	  require_once("scr_header.php");	?>
    <body class=" ">
        <div class="col-lg-12">
            <section class="box nobox">
                <div class="content-body">    
					<div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h3 class="page_error_info">Kode User/password yang Anda masukkan tidak ada di data kami, silahkan ulangi memasukkan user dan password Anda</h3>
                            <div class="col-md-6 col-sm-6 col-xs-8 col-md-offset-3 col-sm-offset-3 col-xs-offset-2">
                                <form action="javascript:;" method="post" class="page_error_search">
                                    <div class="input-group transparent"></div>
                                    <div class="text-center page_error_btn">
                                        <a class="btn btn-purple btn-lg" href='bm.php'><i class='fa fa-location-arrow'></i> &nbsp; Back to Login</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
		</div>
<!--
        <script src="assets/js/jquery-1.11.2.min.js" type="text/javascript"></script> 
        <script src="assets/js/jquery.easing.min.js" type="text/javascript"></script> 
        <script src="assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> 
        <script src="assets/plugins/pace/pace.min.js" type="text/javascript"></script>  
        <script src="assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js" type="text/javascript"></script> 
        <script src="assets/plugins/viewport/viewportchecker.js" type="text/javascript"></script>  
-->
        <script src="assets/js/scripts.js" type="text/javascript"></script> 
        <div class="modal" id="section-settings" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
            <div class="modal-dialog animated bounceInDown">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Section Settings</h4>
                    </div>
                    <div class="modal-body">

                        Body goes here...

                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                        <button class="btn btn-success" type="button">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>



