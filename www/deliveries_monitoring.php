<?php 
session_start(); 
require './include/config.php';

$template->set('title', 'Welcome');
$template->place('header'); // Set page header code (from inc/templates):

$result_auditlog = PDO_FetchAll("SELECT * FROM `auditlog_tb`");

?>
<link rel="stylesheet" href="css/nocss/jquery-ui-timepicker-addon.css">
    <form method="POST" id="form_summaryreport" action="#" autocomplete="off">
    <!-- Breadcomb area Start-->
    <div class="breadcomb-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="breadcomb-list">
                        <div class="row">
                                <div class="breadcomb-wp">
                                    <div class="breadcomb-icon">
                                        <i class="notika-icon notika-app"></i>
                                    </div>
                                    <div class="breadcomb-ctn">
                                        <h2>Deliveries Monitoring</h2>
                                        <!-- <p>Welcome to Notika <span class="bread-ntd">Admin Template</span></p> -->
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcomb area End-->
    <!-- Breadcomb area Start-->
    <div class="breadcomb-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="breadcomb-list">

                        <div class="row">

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-2 col-md-2">
                                    <b>Product</b>
                                </div>
                                <div class="col-lg-2 col-md-2">
                                    <b>Stocks Before</b>
                                </div>
                                <div class="col-lg-2 col-md-2">
                                    <b>Stocks After</b>
                                </div>
                                <div class="col-lg-4 col-md-4">
                                    <b>Pages</b>
                                </div>
                                <div class="col-lg-2 col-md-2">
                                    <b>Date</b>
                                </div>
                            </div>
                                 
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="print_history" data-date="<?php echo date("Ymd")."-historyreport"; ?>">
                                <?php

                                        $i=0;
                                        if(count($result_auditlog) > 0) {
                                            foreach ($result_auditlog as $row_auditlog) {

                                                ?>
                                                           
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="col-lg-2 col-md-2"><?php echo $row_auditlog['product_name']; ?></div>
                                                        <div class="col-lg-2 col-md-2"><?php echo $row_auditlog['count_beforechange']; ?></div>
                                                        <div class="col-lg-2 col-md-2"><?php echo $row_auditlog['count_afterchange']; ?></div>
                                                        <div class="col-lg-4 col-md-4"><?php echo $row_auditlog['log_sources']; ?></div>
                                                        <div class="col-lg-2 col-md-2"><?php echo $row_auditlog['date_created']; ?></div>
                                                    </div>
                                                </div>

                                                <?php
                                            $i++; } 
                                        } else {
                                            ?>
                                                <div class="alert alert-danger alert-mg-b-0 text-center p-1" role="alert">No data found!</div>
                                            <?php
                                        }

                                ?>                 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
<?php
    // Set page footer code (from inc/templates):
    $template->place('footer');
?>

<script type="text/javascript" src="js\nojs\jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="js\html2canvas.min.js"></script>
<script type="text/javascript" src="js\html2canvas.js"></script>

<script>

function htmltocanvas() {

    html2canvas(document.querySelector("#print_history")).then(canvas => {
        // document.body.appendChild(canvas)
        download(canvas, document.getElementById("print_history").getAttribute("data-date")+'.png');
    });

}

$('#ts_all').on('click', function() {
    $("a[aria-expanded="+$(this).prop("checked")+"]").click();
});

</script>