<?php 
session_start(); 
require './include/config.php';

$template->set('title', 'Welcome');
$template->place('header'); // Set page header code (from inc/templates):

if(isset($_POST['check'])) {
    $from = $_POST['from']." ".$_POST['fromtime'];
    $to = $_POST['to']." ".$_POST['totime'];

    $result_transact = PDO_FetchAll("SELECT * FROM `transaction_tb` WHERE (`date_created` BETWEEN '$from' AND '$to')");
} else {
    $result_transact = PDO_FetchAll("SELECT * FROM `transaction_tb` WHERE DATE(`date_created`) LIKE  '%".$date_format."%'");
}
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
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="breadcomb-wp">
                                    <div class="breadcomb-icon">
                                        <i class="notika-icon notika-app"></i>
                                    </div>
                                    <div class="breadcomb-ctn">
                                        <h2>Transaction History</h2>
                                        <!-- <p>Welcome to Notika <span class="bread-ntd">Admin Template</span></p> -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-3">
                                <div class="breadcomb-report">
                                    <a href="summary_report.php"><button type="button" id="reset" data-toggle="tooltip" data-placement="top" title="Reset Filter" class="btn notika-btn-purple waves-effect"><i class="notika-icon notika-close"></i> Reset</button></a>
                                    <button type="submit" name="check" data-toggle="tooltip" data-placement="top" title="Filter Date" class="btn notika-btn-orange waves-effect" data-type="info"><i class="notika-icon notika-checked"></i> Check Dates</button>
                                    <?php if(count($result_transact) > 0) { ?>
                                        <button data-toggle="tooltip" data-placement="top" title="Download Report" class="btn btn-info notika-btn-info waves-effect" onclick="htmltocanvas();"><i class="notika-icon notika-sent"></i></button>
                                    <?php } ?>
                                    <hr />
                                    <div class="toggle-select-act fm-cmp-mg">
                                        <div class="nk-toggle-switch" data-ts-color="pink">
                                            <input id="ts_all" type="checkbox" hidden="hidden" checked>
                                            <label for="ts_all" class="ts-helper"></label>
                                            <label for="ts_all" class="ts-label">Toggle Section Pane</label>
                                        </div>
                                    </div>
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
                                
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <div class="nk-int-mk">
                                            <h2>Date From</h2>
                                        </div>
                                        <div class="form-group ic-cmp-int">
                                            <div class="form-ic-cmp">
                                                <i class="notika-icon notika-calendar"></i>
                                            </div>
                                            <div class="nk-int-st">
                                                <input type="text" class="form-control datepicker" name="from" id="from" data-mask="99/99/9999" placeholder="dd/mm/yyyy" required value='<?php echo @$_POST['from']; ?>'>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <div class="nk-int-mk">
                                            <h2>Date To</h2>
                                        </div>
                                        <div class="form-group ic-cmp-int">
                                            <div class="form-ic-cmp">
                                                <i class="notika-icon notika-calendar"></i>
                                            </div>
                                            <div class="nk-int-st">
                                                <input type="text" class="form-control datepicker" name="to" id="to" data-mask="99/99/9999" placeholder="dd/mm/yyyy" required value='<?php echo @$_POST['to']; ?>'>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <div class="nk-int-mk">
                                            <h2>Time From</h2>
                                        </div>
                                        <div class="form-group ic-cmp-int">
                                            <div class="form-ic-cmp">
                                                <i class="notika-icon notika-refresh"></i>
                                            </div>
                                            <div class="nk-int-st">
                                                <input type="text" class="form-control datepicker" name="fromtime" id="fromtime" data-mask="99:99" placeholder="hh:mm" required value='<?php echo @$_POST['fromtime']; ?>'>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <div class="nk-int-mk">
                                            <h2>Time To</h2>
                                        </div>
                                        <div class="form-group ic-cmp-int">
                                            <div class="form-ic-cmp">
                                                <i class="notika-icon notika-refresh"></i>
                                            </div>
                                            <div class="nk-int-st">
                                                <input type="text" class="form-control datepicker" name="totime" id="totime" data-mask="99:99" placeholder="hh:mm" required value='<?php echo @$_POST['totime']; ?>'>
                                            </div>
                                        </div>
                                    </div>
                                
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-2 col-md-2">
                                    Transaction ID
                                </div>
                                <div class="col-lg-2 col-md-2">
                                    Total Net Sales
                                </div>
                                <div class="col-lg-2 col-md-2">
                                    Discount All
                                </div>
                                <div class="col-lg-2 col-md-2">
                                    Customer Name
                                </div>
                                <div class="col-lg-1 col-md-1">
                                    Completed
                                </div>
                                <div class="col-lg-1 col-md-1">
                                    Voided
                                </div>
                                <div class="col-lg-2 col-md-2">
                                    Date
                                </div>
                            </div>
                                 
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="print_history" data-date="<?php echo date("Ymd")."-historyreport"; ?>">
                                <?php

                                        $i=0;
                                        if(count($result_transact) > 0) {
                                            foreach ($result_transact as $row_transact) {
                                                $phpdate = strtotime( $row_transact['date_created'] );
                                                ?>
                                       

                                                                <div class="accordion-stn">
                                                                    <div class="panel-group" data-collapse-color="nk-green" id="accordion<?php echo $i; ?>" role="tablist" aria-multiselectable="true">
                                                                        <div class="panel panel-collapse notika-accrodion-cus">
                                                                            <div class="panel-heading" role="tab">
                                                                                <h4 class="panel-title">
                                                                                    <a data-toggle="collapse" data-parent="#accordion<?php echo $i; ?>" href="#accordion-pane<?php echo $i; ?>" aria-expanded="false">
                                                                                        <div class="row">
                                                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                                                <div class="col-lg-2 col-md-2"><?php echo str_pad($row_transact['id'], 5, '0', STR_PAD_LEFT); ?></div>
                                                                                                <div class="col-lg-2 col-md-2"><?php echo "Php ".number_format($row_transact['total_amtdisc'], 2); ?></div>
                                                                                                <div class="col-lg-2 col-md-2"><?php echo $row_transact['discount_all']."%"; ?></div>
                                                                                                <div class="col-lg-2 col-md-2"><?php echo $row_transact['customer_name']; ?></div>
                                                                                                <div class="col-lg-1 col-md-1"><?php if($row_transact['tt_completed']>0) { echo '<button class="btn btn-lime lime-icon-notika btn-reco-mg btn-button-mg waves-effect"><i class="notika-icon notika-checked"></i></button>'; } ?></div>
                                                                                                <div class="col-lg-1 col-md-1"><?php if($row_transact['tt_voided']>0) { echo '<button class="btn btn-danger danger-icon-notika btn-reco-mg btn-button-mg waves-effect"><i class="notika-icon notika-checked"></i></button>'; } ?></div>
                                                                                                <div class="col-lg-4 col-md-4"><?php echo date( 'F d, Y H:i:s A', $phpdate ); ?></div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </a>
                                                                                </h4>
                                                                            </div>
                                                                            <div id="accordion-pane<?php echo $i; ?>" class="collapse" role="tabpanel">
                                                                                <div class="panel-body">
                                                                                    <?php

                                                                                        $result_items = PDO_FetchAll("SELECT a.`discount`, b.`product_name`,a.`price`,a.`quantity`,a.`addons_metajson` FROM `transactionitems_tb` a INNER JOIN `product_tb` b ON a.`product_id`=b.`id` WHERE a.`transact_id`=$row_transact[id] AND `ti_voided` IS NULL");
                                                                                   
                                                                                        if(count($result_items) > 0) {
                                                                                            ?>
                                                                                            <table class="table table-sc-ex">
                                                                                                <thead>
                                                                                                    <tr>
                                                                                                        <th>Product Name</th>
                                                                                                        <th>Net Sales</th>
                                                                                                        <th>Discount</th>
                                                                                                        <th>Quantity</th>
                                                                                                    </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                    <?php
                                                                                                        foreach ($result_items as $row_items) {

                                                                                                            $dec = ($row_items['discount'] / 100); //its convert 10 into 0.10
                                                                                                            $mult = $row_items['price'] * $dec; // gives the value for subtract from main 

                                                                                                            echo "<tr><td>".$row_items['product_name']."</td><td>Php ".number_format(($row_items['price']-$mult), 2)."</td><td>".$row_items['discount']."%</td><td>".$row_items['quantity']."</td></tr>";

                                                                                                            if(isJson($row_items['addons_metajson'])) { 

                                                                                                                foreach (json_decode($row_items['addons_metajson']) as $row_subitems => $subitems) {

                                                                                                                    if($row_subitems=="free") {
                                                                                                                        $explode_free = explode("-",$subitems);
                                                                                                                        $row_subitems = $explode_free[0];
                                                                                                                        $subitems = $explode_free[1];
                                                                                                                    } 

                                                                                                                      $result_addons = PDO_FetchRow("SELECT `addons_name`,`price` FROM `addons_tb` WHERE `id`='".$row_subitems."' AND `at_deleted` IS NULL ");

                                                                                                                        echo "<tr><td style='text-indent: 2em;'>".$result_addons['addons_name']."</td><td>Php ".number_format($subitems, 2)."</td><td></td><td>".$row_items['quantity']."</td></tr>";

                                                                                                                }
                                                                                                            
                                                                                                            }

                                                                                                        }
                                                                                                    ?>
                                                                                                </tbody>
                                                                                            </table>
                                                                                            <?php
                                                                                        }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                <?php
                                            $i++; } 
                                        } else {
                                            ?>
                                                <div class="alert alert-danger alert-mg-b-0 text-center p-1" role="alert">No orders for today!</div>
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