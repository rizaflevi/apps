<?php

$_SESSION['gSCR_HEADER'] = 'Registrasi berhasil';

?>

<!DOCTYPE html>
<html class=" ">
	<?php	  require_once("scr_header.php");	?>
    <body class=" ">

        <div class="col-lg-12">
            <section class="box nobox">
                <div class="content-body">    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">

  <!--                           <h1 class="page_error_code text-purple">500</h1>		-->
                            <h1 class="page_error_info">Terimakasih telah melakukan registrasi</h1>

                            <div class="col-md-6 col-sm-6 col-xs-8 col-md-offset-3 col-sm-offset-3 col-xs-offset-2">
                                <form action="javascript:;" method="post" class="page_error_search">
                                    <div class="input-group transparent">
    <!--                                    <span class="input-group-addon">
                                            <i class="fa fa-search icon-purple"></i>
                                        </span>
                                         <input type='submit' value="">	-->
                                    </div>
                                    <div class="text-center page_error_btn">
                                        <a class="btn btn-purple btn-lg" href='login.php'><i class='fa fa-location-arrow'></i> &nbsp; Back to Login</a>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </section>
		</div>
		<?php	require_once("js_framework.php");	?>
        <script src="assets/js/scripts.js" type="text/javascript"></script> 

        <div class="modal" id="section-settings" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
            <div class="modal-dialog animated bounceInDown">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Section Settings</h4>
                    </div>
                    <div class="modal-body">

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



